<?php

class syncVideoTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addArguments(array(
            new sfCommandArgument('start'),
            new sfCommandArgument('end'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            // add your own options here
        ));

        $this->namespace        = 'zy';
        $this->name             = 'sync_video';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
同步video数据
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        sfContext::createInstance($this->configuration);    
        $databaseManager = new sfDatabaseManager($this->configuration);

        $end = 0;
        $start = time() - 60 * 5;

        if(isset($arguments["start"])) {
            $start = $arguments["start"];
        }

        if(isset($arguments["end"])) {
            $end = $arguments["end"];
        }

        $this->getVideoApi($start, $end);
    }

    function getVideoApi($starttime = 1386550, $endtime = 0) {
        $channel_arr = array("", "nba", "soccer", "f1", "tennis", "nfl", "mma");

        $tagTable     = ZyVoiceTagTable::getInstance();
        $objectTable  = ZyObjectTable::getInstance();
        $mentionTable = ZyVideoMentionTable::getInstance();
        $objectNotificationTable = ZyObjectNotificationTable::getInstance();

        foreach($channel_arr as $channel) {
            if($channel) {
                $channel = $channel . "/";
            }

            $url ="http://v.hupu.com/{$channel}index.php?m=interface&a=getAllNewVideo&time={$starttime}";
            if($endtime) {
                $url .= "&endtime=" . $endtime;;
            }

            $options = array(
                'http' => array(
                    'method' => "GET",
                    'timeout' => 3,
                ),
            );

            $rs = file_get_contents($url, false, stream_context_create($options));
            $rs = json_decode($rs, true);
            if($rs) {
                foreach($rs as $item) {
                    $zyVideo = new ZyVideo();

                    foreach($item as $k => $v) {
                        if(in_array($k, array("vid", "title", "description", "cover", "localcover", "dateline", "author", "playtime"))) {
                            $names = explode("_", $k);
                            $setname = "set";
                            foreach($names as &$name) {
                                $setname .= ucfirst($name);
                                $i ++;
                            }
                            $zyVideo->$setname($v);
                        }
                    }

                    $zyVideo->setFromUrl($item["fromurl"]);

                    $zyVideo->save();

                    $tag_list = explode(",", $item["tag"]);

                    if($tag_list) {
                        foreach($tag_list as $tag) {
                            if($video_tag = $tagTable->search($tag["tag_name"])) {
                                $tag_name = $video_tag->getObjectName();
                            } else {
                                $tag_name = $tag;
                            }

                            $object = $objectTable->getByNameAndFullName($tag_name);

                            if($object) {
                                echo $object->getName();
                                $mentionTable->add($object->getId(), $zyVideo->getId());
                                //$campTable->autoUpdate($object->getId(), $zyVide->getId());
                                $objectNotificationTable->addForVideoNew($object->getId(), $zyVideo->getId());
                            }
                        }
                    }
                }
            }
        }

    }

}
