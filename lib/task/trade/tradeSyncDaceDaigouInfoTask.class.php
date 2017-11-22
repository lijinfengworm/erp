<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeSyncDaceDaigouInfoTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace        = 'trade';
        $this->name             = 'SyncDaceDaigouInfo';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:CreateCiTieForWP|INFO] task does things.
Call it with:

  [php symfony trade:Amqp|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);
        ini_set('memory_limit','128M');

        tradeSyncDaceDaigouInfoTask::sync();
    }

    private static $daceUrl;
    private static $redis;
    private static $mqChannel;
    private static $updateTime =  10800;                                                         //更新间隔 3小时
    private static $tmpFlagKey = 'trade:daigou:tmp:flag';                                        //临时标志位
    private static $allDaigouKey = 'trade:daigou:all';                                           //所有代购ID
    private static $syncFinishTimeKey = 'trade:daigou:sync:finish:time';                         //同步完成时间


    private static function sync(){
        if(!self::$redis){
            $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
            $redis->select(5);
            self::$redis = $redis;
        }

       $dace_api = sfConfig::get('app_dace_api');
       self::$daceUrl = $dace_api['url'].'/dace-api/services/shihuo/product_kpi.json';

        while(true){
            if(tradeSyncDaceDaigouInfoTask::isSync()){
                $tmpFlag = (int)self::$redis->get(self::$tmpFlagKey);
                if(!$tmpFlag) self::$redis->del(self::$allDaigouKey);                            //为0清空，所有代购ID

                while(true){
                    $daceUrl = self::$daceUrl.'?'.http_build_query(array(
                            'limit'=>1000,
                            'offset'=>$tmpFlag,
                        ));

                    $dataJson = tradeCommon::requestUrl($daceUrl, 'GET', NUll, NULL, 10);
                    $data = json_decode($dataJson, true);

                    if($data){

                        foreach($data as $data_val){
                            ++$tmpFlag;

                            self::$redis->hset(self::$allDaigouKey, $data_val['product_id'], $data_val['compand_rank']);
                            self::$redis->set(self::$tmpFlagKey, $tmpFlag);

                            echo 'ID:'.$data_val['product_id'].',flag:'.$tmpFlag.PHP_EOL;
                            self::sendToMQ(array(
                                'id' => $data_val['product_id'],
                                'type' => 'update',
                                'channelType'=>'daigou'
                            ));

                        }

                        sleep(2);
                    }else{
                        self::$redis->set(self::$tmpFlagKey, 0);
                        self::$redis->set(self::$syncFinishTimeKey, time());

                        break;
                    }
                }
            }

            echo 'running...'.PHP_EOL;
            sleep(60);
        }

        exit;
    }


    /*
    * 是否开始同步
    **/
    private static function isSync(){
        $time = time();
        $finishTime = self::$redis->get(self::$syncFinishTimeKey);
        $tmpFlag = self::$redis->get(self::$tmpFlagKey);

        if($tmpFlag || !$finishTime || ($time - $finishTime > self::$updateTime)){
            return true;
        }else{
            return false;
        }
    }

    /*
    *往消息队列 发消息
    *
    **/
    private static function sendToMQ($message){
        if(!self::$mqChannel){
            $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
            $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'], $amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);

            $channel = $connection->channel();
            $arguments = array(
                "x-dead-letter-exchange" => array("S", "amq.topic"),
                "x-message-ttl" => array("I", 2000),
                "x-dead-letter-routing-key" => array("S", "shihuo.product.detail")
            );
            $channel->queue_declare('daigou_deferred', false, true, false, false, false, $arguments);

            self::$mqChannel = $channel;
        }


        $msg = new AMQPMessage(json_encode($message));
        self::$mqChannel->basic_publish($msg, '', 'daigou_deferred');
    }
}
