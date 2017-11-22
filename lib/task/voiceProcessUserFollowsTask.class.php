<?php
    class voiceProcessUserFollowsTask extends sfBaseTask{
        
        public static $myRedis = null;
        const r_key = 'follow_last_userid';

        public function configure() {
            
            $this->addOptions(array(
                new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
                new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
                new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'voice'),
                // add your own options here
            ));
            
            $this->namespace = 'voice';
            $this->name = 'proc-user-follows';
            $this->briefDescription = 'insert datas to userObjects db from userFollows db';
            $this->detailedDescription = <<<EOF
[php symfony voice:proc-user-follows|INFO]
EOF;
            
        }
        
        public function execute($arguments = array(), $options = array()) {
            sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration('star', $options['env'], true));
            $databaseManager = new sfDatabaseManager($this->configuration);
            $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
            
            if(!self::$myRedis){
                self::$myRedis = sfContext::getInstance()->getDatabaseConnection('voiceRedis');
            }
            
            $this->log('the proc now start!');
            
            $successNum = 0; //成功处理次数
            $errorNum = 0; //失败处理次数

            while(1){
                $uid = $this->getLastUserIdByRedis(); //处理的开始的用户id
                $this->log('the current proc begin userId is '. $uid);
                
                $userFollowTags = $this->getUserFollowTags($uid);
                
                if(empty($userFollowTags)){
                    $this->log('the success proc results is over');
                    break;
                }

                foreach($userFollowTags as $userFollowTag){
                    if($userFollowTag['voice_tag_ids']){//存在用户关注的tag
                        $tagIds = explode(',', trim($userFollowTag['voice_tag_ids']));
                        $tagIds = array_unique($tagIds);//获取tagid

                        foreach($tagIds as $tagId){
                            
                            $userObjects = $this->getUserObjectByTagId($tagId);//获取tagid对应的对象ids
                            
                            if(!empty($userObjects)){//如果存在相应的对象ids
                                
                                foreach($userObjects as $userObject){
                                    
                                    $object_id = (int)$userObject['voice_object_id'];
                                    $isExists = $this->isExistsUserFollowObject($userFollowTag['user_id'], $object_id);
                                    if(!$isExists){
                                        try{
                                            $userObject = new voiceUserObject();
                                            $userObject->fromArray(array(
                                                'user_id' => $userFollowTag['user_id'],
                                                'voice_object_id' => $object_id,
                                            ));
                                            $userObject->save();
                                            $successNum +=1;

                                        }  catch (sfDoctrineException $e){
                                            $errorNum +=1;
                                        }
                                    }
                                    
                                }
                            }

                        }

                    }
                }
                
                sleep(1);
            }
            
            $this->log('the success proc results is '. $successNum);
            $this->log('the error proc results is '. $errorNum);
            
            $this->log('the proc ended!');
        }
        
        /*
         * 获取用户所关注的tag信息
         */
        public function getUserFollowTags($uid){
            $queryResult = voiceUserFollowTable::getInstance()->createQuery('f')                 
                        ->select('f.user_id, f.voice_tag_ids')
                        ->where('f.user_id > ?',$uid)
                        ->orderBy('f.user_id asc')
                        ->limit(100)
                        ->fetchArray();
            
            if(!empty($queryResult)){
                $lastUid = $queryResult[count($queryResult)-1]['user_id'];
                $this->setLastUserIdByRedis($lastUid);
            }  
            
            return $queryResult;
        }
        
        /*
         * 获取关注tag的对应对象信息
         */
        public function getUserObjectByTagId($tagId){
            return voiceObjectTagTable::getInstance()->createQuery('r')
                    ->where('r.voice_tag_id =?',$tagId)
                    ->fetchArray();
        }
        
        /*
         * 判断用户是否已关注了该对象
         */
        public function isExistsUserFollowObject($uid,$objectId){
            $query = voiceUserObjectTable::getInstance()->createQuery('r')
                    ->where('r.user_id = ?',$uid)
                    ->andWhere('r.voice_object_id =?',$objectId)
                    ->fetchOne();
            
            if($query){
                return true;//已关注
            }else{
                return false;//未关注
            }
        }
        
        /*
         * redis获取最后的处理的用户id
         */
        public function getLastUserIdByRedis(){
            return self::$myRedis->get(self::r_key) ? self::$myRedis->get(self::r_key) : 0;
        }
        
         /*
         * redis记录最后的处理的用户id
         */
        public function setLastUserIdByRedis($uid = 0){
            self::$myRedis->set(self::r_key,$uid);
        }
    }
