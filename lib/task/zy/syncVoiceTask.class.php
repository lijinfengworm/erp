<?php

class syncVoiceTask extends sfBaseTask
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

        $this->namespace        = 'zy';
        $this->name             = 'sync_voice';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
同步vioce数据
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        sfContext::createInstance($this->configuration);    
        $databaseManager = new sfDatabaseManager($this->configuration);

        $voiceTable    = ZyVoiceTable::getInstance();

        $lastid = $voiceTable->getLastId();
        if(!$lastid) {
            $lastid = 1386550;
        }

        echo "\n\nTASK BEGIN >>>> \n";
        echo "lastid : $lastid \n";

        $this->getVoiceApi($lastid);
        echo "\n TASK END <<<<<< ";
    }

    function getVoiceApi($fromid=1386550) {
        $url = "http://voice.hupu.com/api/get_voice_more_msgid_msg?msg_id={$fromid}&limit=100";
        echo "voice api url : $url \n";
        $options = array(
            'http' => array(
                'method' => "GET",
                'timeout' => 3,
            ),
        );

        $rs = file_get_contents($url, false, stream_context_create($options));
        $tagTable     = ZyVoiceTagTable::getInstance();
        $objectTable     = ZyObjectTable::getInstance();
        $campTable     = ZyVoiceCampTable::getInstance();
        $mentionTable = ZyVoiceMentionTable::getInstance();

        $rs = json_decode($rs, true);
        $objectNotificationTable = ZyObjectNotificationTable::getInstance();

        foreach($rs["data"] as $item) {
            $zyVoice = new ZyVoice();
            foreach($item as $k => $v) {
                if(in_array($k, array("id", "m_url", "tag_list", "origin", "origin_url", "img_url"))) {
                    continue;
                }
                $names = explode("_", $k);
                $setname = "set";
                foreach($names as &$name) {
                    $setname .= ucfirst($name);
                    $i ++;
                }
                $zyVoice->$setname($v);
            }

            $zyVoice->setVoiceId($item["id"]);
            $zyVoice->setReplyCount(0);
            $zyVoice->setLightCount(0);

            //todo 
            $zyVoice->save();

            echo "\n======================ITEM BEGIN===================\n";
            echo "item info : " . $zyVoice->getId() . ", " . $zyVoice->getVoiceId() . "\n";

            $tag_list = $item["tag_list"];

            echo "tag info : ";

            if($tag_list) {
                foreach($tag_list as $tag) {
                    $group_id_arr = array();
                    if(isset($tag["category"])) {
                        $groupTagTable     = ZyGroupTagTable::getInstance();
                        $groupTable     = ZyGroupTable::getInstance();
                        $tag_object = $groupTagTable->searchByCategory($tag["category"]);
                        if($tag_object) {
                            $group_name_arr = explode(",", $tag_object->getObjectNames());
                            foreach($group_name_arr as $group_name) {
                                $group = $groupTable->getByName($group_name);
                                if($group) {
                                    $group_id_arr[] = $group->getId();
                                }
                            }
                        }
                    }

                    if($voice_tag = $tagTable->search($tag["tag_name"])) {
                        $tag_name = $voice_tag->getObjectName();
                    } else {
                        $tag_name = $tag["tag_name"];
                    }

                    $object = $objectTable->getByNameAndFullName($tag_name, $group_id_arr);
                    if($object) {
                        echo  $object->getName() . ", ";
                        $mentionTable->addForVoice($object->getId(), $zyVoice->getId());
                        $campTable->autoUpdate($object->getId(), $zyVoice->getId());
                        $objectNotificationTable->addForVoiceNew($object->getId(), $zyVoice->getId());
                    }
                }
            }

            echo "\n======================ITEM END===================\n";
        }
    }

}
