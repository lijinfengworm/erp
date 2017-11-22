<?php

class bbsSyncTask extends sfBaseTask
{
    protected function configure()
    {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            // add your own options here
        ));

        $this->namespace        = 'eric';
        $this->name             = 'bbs_sync';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF

EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        sfContext::createInstance($this->configuration);    
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
        $messageTable = ErMessageTable::getInstance();
        $roleTable = ErRoleTable::getInstance();
        $objectTable = ErObjectTable::getInstance();
        $messages = $messageTable->getSyncMessages();

        foreach($messages as $message) {
            $parent_message = $message;
            $thread_object = $message->getObject();
            $replies = $this->getThreadReplies($message->getTid());
            $old_replies = $messageTable->getByParentId($message->getId());
            $parent_id = $message->getId();

            if($thread_object->getType() == "足球") {
                $object_type = "英超";
            } else {
                $object_type = $thread_object->getType();
            }

            if($replies) {
                foreach($replies as $reply) {
                    $reply_object = false;
                    echo "TIME : " .$reply["postdate"];
                    echo "TYPE : " . $object_type;
                    echo "NAME : " . mb_convert_encoding($reply["author"], 'utf-8', 'gbk') . "\n";
                    echo "ID : " . $reply["authorid"] . "\n";
                    echo "TEAM : " . $this->getUserTeam($reply["authorid"], $object_type) . "\n";
                    echo "CONTENT : " . strip_tags(mb_convert_encoding($reply["content"], 'utf-8', 'gbk')) . "\n\n";
                    echo "CONTENT :" . mb_convert_encoding($reply["content"], 'utf-8', 'gbk') . "\n\n";


                    $reply_content = mb_convert_encoding($reply["content"], 'utf-8', 'gbk');
                    $reply_content = $this->transfromContent($reply_content, $parent_id);

                    $find = false;
                    foreach($old_replies as $r) {
                        if(trim($reply_content) == trim($r->getContent())) {
                            $find = true;
                            echo "CONTENT old : " . trim($r->getContent()) . "\n\n";
                            echo "CONTENT new : " . trim($reply_content) . "\n\n";
                            break;
                        }
                    }

                    if($find) {
                        continue;
                    }

                    $team_name =  $this->getUserTeam($reply["authorid"], $object_type);
                    $reply_username = mb_convert_encoding($reply["author"], 'utf-8', 'gbk');

                    if($team_name != -1) {
                        $reply_object = $objectTable->getByName($team_name);
                        if($reply_object) {
                            $status = $roleTable->autoJoin($reply["authorid"], $reply_username, $reply_object->getId());
                            $reply_role = $roleTable->getUserRole($reply["authorid"], $reply_object->getId());
                        }
                    }

                    if(!$reply_object) {
                        $reply_role = $roleTable->randomMessageJoin($thread_object->getType(), $reply["authorid"], $reply_username, $parent_id);
                        $reply_object = $reply_role->getObject();
                    }

                    if(!$reply_object) {
                        continue;
                    }

                    $viewer_key = "message_viewers_" . "$parent_id";
                    if(!($viewers = $memcache->get($viewer_key))) {
                        $viewers = array();
                    }

                    $viewers[$reply_role->getId()] = "1";

                    $memcache->set($viewer_key, $viewers, 0, 0);


                    $Message = new ErMessage();
                    $Message->setContent($reply_content);
                    $Message->setRoleId($reply_role->getId());
                    $Message->setParentId($parent_id);
                    $Message->setObjectId($parent_message->getObject()->getId());
                    $Message->setLastReplyTime($reply["postdate"]);
                    $Message->save();

                    $this->atMsg($reply_content, $parent_id, $reply_role->getId());

                    //更新话题更新时间
                    $messageTable = ErMessageTable::getInstance();
                    $parent_message = $messageTable->find($parent_id);
                    $parent_message->updateMsgShow($reply["postdate"]);
                }
            }
        }

        echo "OK";
    }

    private function getThreadReplies($tid) {
        if (!preg_match("/\d*/", $tid)) {
            return array();
        }

        // 接口名称
        $apiname = 'getthreadreplies';

        // 验证参数
        $appid = '100';
        $time = time();
        $key = 'e8303e590012e67';
        $sign = md5(md5($appid) . $time . $key);

        // 接口请求地址拼接
        $params = array('tid' => $tid, 'sort' => 1, "pagecount" => 2000);
        $result = snsInterface::getContents($apiname, $appid, $key, $params);

        if (is_numeric($result) ) {
            $info = array();
        } else {
            $info = $result;
        }

        return $info;
    }

    private function transfromContent($content, $message_id = 0) {
        $roleTable = ErRoleTable::getInstance();
        $objectTable = ErObjectTable::getInstance();
        preg_match_all('/[@]\<a class="u" target="_blank" href="http:\/\/my.hupu.com\/[\w]+">([^@\s,\.，。:：!！]+)<\/a>/u', $content, $matches);
        /*
        echo "\n~~~~~~~~content~~~~~~~~~~~~~~~~\n<pre>";
        print_r($content);
        echo "</pre>\n~~matches~~~~~~~~~~~~~~~~~~~~~\n";
         */
        if(isset($matches[1]) && $matches[1]) {

            foreach($matches[1] as $name) {
                $role = $roleTable->guessByMessasge($name, $message_id);
                if($role) {
                    $at_name = $role->getName();
                    $content = preg_replace('/([@]\<a class="u" target="_blank" href="http:\/\/my.hupu.com\/[\w]+">'.$name.'<\/a>)/u', "@" . $at_name . " ", $content);
                }

            } 
        }

        return $content;
    }

    private function getUserTeam($uid, $type = "NBA") {
//        $key = "user_team_{$type}_" . "$uid";
//        $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
//        if($value = $memcache->get($key)) {
//            return $value;
//        }
//
//        $url = "http://my.hupu.com/" . $uid;
//        $contents = file_get_contents($url);
//        $contents = strip_tags(mb_convert_encoding($contents, 'utf-8', 'gbk'));
//
//        preg_match_all('/' . $type . '主队：([\S]+)/', $contents, $matches);
//
//        if(isset($matches[1][0]) && $matches[1][0]) {
//            $memcache->set($key, $matches[1][0], 0, 0);
//            return $matches[1][0];
//
//        } else {
//            $memcache->set($key, "-1", 0, 0);
//        }

        return '-1';
    }

    private function atMsg($content, $parent_id, $role_id) {
        $names = array();
        preg_match_all("/[@]([^@\s,\.，。:：!！]+)/u", $content, $matches);
        if($matches[1]) {
            foreach($matches[1] as $item) {
                //去重
                $names[$item] = "";
            }
        }
        
        $objectTable = ErObjectTable::getInstance();
        $roleTable = ErRoleTable::getInstance();
        $messageTable = ErMessageTable::getInstance();
        $parent_message = $messageTable->find($parent_id);

        if($names) {
            foreach($names as $name=>$v) {
                $Message = new ErMessage();
                $Message->setRoleId($role_id);
                $Message->setParentId($parent_id);
                $Message->setLastReplyTime(time());
                $Message->setIsMention(1);
                $Message->setIsMentionShow(1);

                if(strstr($name, "-") != false) {
                    //role
                    $role = $roleTable->getByName($name);
                    if($role && $role->getObject()->getId() !== $parent_message->getObject()->getId()) {
                        $Message->setObjectId($role->getObject()->getId());
                        $Message->save();
                        $Message->updateMentionShow($role->getObject()->getId(), $parent_message->getId());
                    }

                } else {
                    //object
                    $object = $objectTable->getByName($name);
                    if($object && $object->getId() != $parent_message->getObject()->getId()) {
                        $Message->setObjectId($object->getId());
                        $Message->save();

                        $Message->updateMentionShow($object->getId(), $parent_message->getId());
                    }

                }

            }
        } else {
            $Message = new ErMessage();
            $Message->setRoleId($role_id);
            $Message->setParentId($parent_id);
            $Message->setLastReplyTime(time());
            $Message->setIsMention(1);
            $Message->setIsMentionShow(1);
            $role = $roleTable->find($role_id);
            $Message->setObjectId($role->getObject()->getId());
            $Message->save();
            $Message->updateMentionShow($role->getObject()->getId(), $parent_message->getId());
        }
    }
}
