<?php
/**
 * 每分钟运行一次 处理 需要更新的objlist
 */
class voiceObjectUpdateListTask extends sfBaseTask {

    protected function configure()
    {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'star'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'voice'),
                // add your own options here
        ));

        $this->namespace = 'voice';
        $this->name = 'voiceObjectUpdateList';
        $this->briefDescription = '更新object';
        $this->detailedDescription = <<<EOF
The [voiceObjectUpdateList|INFO] task does things.
Call it with:
  
  [php symfony voiceObjectUpdateList|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration('star', $options['env'], true));
        $list = new voiceTagUpdateRedisList();
    
        $startTiem = time();
        $this->log('start:');

        //获取更新list
        $redis = sfContext::getInstance()->getDatabaseConnection('voiceRedis');
        $last_update_key = voiceObject::$last_update_key;
        
        while (1)
        {
            $infos = $list->getData(20);

            //需要更新对象的头条列表数组
            $upObjFrontPageListArray = array();
            //需要更新message列表数组
            $msgIds = array();

            if (!empty($infos))
            {
                foreach ($infos as $info)
                {
                    //$this->log($info);

                    if (($info['key'] == 'message_frontpage_add') || ($info['key'] == 'message_frontpage_update'))
                    {
                        $msgIds[] = $info['id'];
                    } elseif (($info['key'] == 'del_Object_FrontPage') || ($info['key'] == 'add_Object_FrontPage'))
                    {
                        $upObjFrontPageListArray[] = $info['id'];
                    }
                }

                $msgIds = array_unique($msgIds);

                //处理msg 同步 到frontpage 
                if (!empty($msgIds))
                {
                    $existsFrontPageMsgIds = voiceFrontPageTable::isExistsFrontPageByVoiceMsges($msgIds);

                    if (count($existsFrontPageMsgIds))
                    {//头条存在这些msg，但该些msg相关tag有更改操作，所以进行对象头条的关系调整
                        $msgInfos = twitterMessageTable::getMessagesByids($existsFrontPageMsgIds);
                        $frontPageObjectInfos = voiceFrontPageTable::getFrontPageInfoByVoiceMsges($existsFrontPageMsgIds);

                        if (count($msgInfos))
                        {
                            foreach ($msgInfos as $key => $msg)
                            {//头条对象关系调整
                                $frontPageId = isset($frontPageObjectInfos[$key]) ? $frontPageObjectInfos[$key]['f_id'] : 0;

                                if ($frontPageId)
                                {
                                    $updateObjects = $msg['objects'];
                                    $originalObjects = isset($frontPageObjectInfos[$key]) ? $frontPageObjectInfos[$key]['f_objects'] : array();
                                    $addObjects = array_diff($updateObjects, $originalObjects);
                                    $delObjects = array_diff($originalObjects, $updateObjects);
                                    if (count($addObjects))
                                    {//头条新增对象
                                        foreach ($addObjects as $object)
                                        {
                                            $voiceObjectFrontpage = new voiceObjectFrontPage();
                                            $voiceObjectFrontpage->setVoiceObjectId($object);
                                            $voiceObjectFrontpage->setVoiceFrontPageId($frontPageId);
                                            $voiceObjectFrontpage->save();
                                            $this->log('add one object about frontpage objectid:' . $object . '-frontpageId:' . $frontPageId);
                                        }
                                    }

                                    if (count($delObjects))
                                    {//头条减少对象
                                        voiceObjectFrontPageTable::delObjectByFrontPageIds($delObjects, $frontPageId);
                                        $this->log('del ' . count($delObjects) . ' objects by frontpageid ' . $frontPageId);
                                    }
                                }
                            }
                        }
                    }

                    $newFrontPageMsgIds = array_diff($msgIds, $existsFrontPageMsgIds);
                    if (count($newFrontPageMsgIds))
                    {   //不存在头条中的msg 
                        $msgInfos = twitterMessageTable::getMessagesByids($newFrontPageMsgIds);
                        if (count($msgInfos))
                        {
                            foreach ($msgInfos as $key => $msg)
                            {   
                                //没有对象的新闻不进行处理
                                if(empty($msg['objects']))
                                {
                                    continue;
                                }
                                //同步到头条
                                $voiceFrontPage = new voiceFrontPage();
                                $voiceFrontPage->setPublisherUid($msg['uid']);
                                $voiceFrontPage->setPublisherName($msg['uname']);
                                $voiceFrontPage->setLink($msg['url']);
                                $voiceFrontPage->setType('voice_message');
                                $voiceFrontPage->setTypeId($key);
                                $voiceFrontPage->setSupport(1);
                                $voiceFrontPage->set_frontpage_attr('attr_sync_comment', 1);
                                $voiceFrontPage->setTitle($msg['title']);
                                $voiceFrontPage->save();
                                $this->log('add one msg to frontpage msgid:' . $key);

                                //头条支持操作记录
                                $voiceFrontPageSupportAgaist = new voiceFrontPageSupportAgaist();
                                $voiceFrontPageSupportAgaist->setUid($msg['uid']);
                                $voiceFrontPageSupportAgaist->setFId((int) $voiceFrontPage->getId());
                                $voiceFrontPageSupportAgaist->setSupportAgaist(1);
                                $voiceFrontPageSupportAgaist->save();

                                if (!empty($msg['objects']))
                                {//同步到对象头条关系表中
                                    $frontPageId = $voiceFrontPage->getId();
                                    foreach ($msg['objects'] as $object)
                                    {
                                        $voiceObjectFrontpage = new voiceObjectFrontPage();
                                        $voiceObjectFrontpage->setVoiceObjectId($object);
                                        $voiceObjectFrontpage->setVoiceFrontPageId($frontPageId);
                                        $voiceObjectFrontpage->save();
                                        $this->log('add one object about frontpage objectid:' . $object . '-frontpageId:' . $frontPageId);
                                    }
                                }
                            }
                        }
                    }
                }

                //更新对象的头条列表
                $upObjFrontPageListIds = array_unique($upObjFrontPageListArray);
                if ($upObjFrontPageListIds)
                {
                    $objs = voiceObjectTable::getInstance()->getSomeObject($upObjFrontPageListIds);
                    foreach ($objs as $obj)
                    {
                        $this->log('update frontpagelist objId:' . $obj->getId());
                        $obj->updateFrontPageListToRedis();
                        $redis->ZADD($last_update_key,  time(),  json_encode(array('id'=>$obj->getId(),'type'=>'object_front_page_list_update_time')));
                    }
                    $this->log('update frontpagelist count  ' . count($objs));
                }
            } else
            {
                $this->log('list no data');
            }
            
            //更新比较旧得对象列表
            if (time() - $startTiem > 60)
            {
                $count = $redis->ZCARD($last_update_key);
                if($count == 0)
                {//队列没有值 查询所有的对象 然后插入到更新队列
                    $ids = voiceObjectTable::getInstance()->getAllObjIds();
                    foreach($ids as $id)
                    {
                        $redis->ZADD($last_update_key,  time(),  json_encode(array('id'=>$id,'type'=>'object_front_page_list_update_time')));
                    }
                }
                //获取更新时间是12小时前的
                $olds = $redis->ZRANGEBYSCORE($last_update_key,0,  time()-60*60*12);
                
                foreach ($olds as $update_task)
                {
                    
                    $update_info = json_decode($update_task,true);
                    
                    $this->log('update old list  ' . $update_info['type'].'  -'.$update_info['id']);
                    //根据类型更新对象的数据
                    if ($update_info['type'] == 'object_front_page_list_update_time') {
                        $voiceObject = voiceObjectTable::getInstance()->find($update_info['id']);
                        if($voiceObject)
                        {   
                            $voiceObject->updateFrontPageListToRedis();
                            //更新redis 有序队列
                            $redis->ZADD($last_update_key,  time(),  json_encode(array('id'=>$update_info['id'],'type'=>'object_front_page_list_update_time')));
                        }
                    }
                }
                
                //把所有的objid 获取到存取到redis里面 nodejs用到了这个数据
                $ids = voiceObjectTable::getInstance()->getAllObjIds();
                //$this->log($ids);
                $all_object_ids_key = 'voice_all_object_ids';
                $redis->del($all_object_ids_key);
                $redis->hmset($all_object_ids_key, $ids);
                $this->log('exit');
                break;
            }

            sleep(3);

            $this->log('sleep');
        }
    }

}
