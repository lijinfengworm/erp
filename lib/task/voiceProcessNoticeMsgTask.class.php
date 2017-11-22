<?php
    class voiceProcessNoticeMsgTask extends sfBaseTask{
        
        public function configure() {
            
            $this->addOptions(array(
                new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
                new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
                new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'voice'),
                // add your own options here
            ));
            
            $this->namespace = 'voice';
            $this->name = 'process-notice';
            $this->briefDescription = 'insert notice db and add redis hash from redis list';
            $this->detailedDescription = <<<EOF
[php symfony voice:process-notice|INFO]
EOF;
               
        }
        
        public function execute($arguments = array(), $options = array()) {
            sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration('star', $options['env'], true));
            $databaseManager = new sfDatabaseManager($this->configuration);
            $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
            
            $this->log('the process notice start!');
            
            /*
             * 信息处理
             */
            $myRedis =new voiceUserRoomRedis();
            $result = $myRedis->insertDbAndAddHash();
            
            if($result && isset($result['right']) && isset($result['error'])){
                $this->log('the success processed notice messages have '.$result['right']);
                $this->log('the error processed notice messages have '.$result['error']);
            }
            
            $this->log('the process notice over!');

        }
        
    }
