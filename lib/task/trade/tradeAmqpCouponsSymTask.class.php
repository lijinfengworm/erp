<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeAmqpCouponsSymTask extends sfBaseTask
{
    CONST WEB_SITE = 'http://www.shihuo.cn';
    CONST ERROR_NUM = 10;
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace        = 'trade';
        $this->name             = 'AmqpCouponsSym';
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

        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);

        $channel = $connection->channel();
        $queue = 'lipinka_queue_use_consume';
        $channel->queue_declare($queue, false, true, false, false, false);
        $channel->queue_bind($queue, "amq.topic","shihuo.lipinka.use");
        $channel->basic_consume($queue, '', false, false, false, false, 'tradeAmqpCouponsSymTask::callback');

        while(count($channel->callbacks) )
        {
            $nowmem = memory_get_usage()/1024/1024;
            if($nowmem <60){

                $channel->wait();
            }else{

                break;
            }
        }
    }

    /*
     *回调
     **/
    public static function callback($msg)
    {
        echo "msg:", $msg->body, "\n";
        $msgBody = json_decode($msg->body, true);

        if($msgBody){
            $account = $msgBody['card'];
            $uid = $msgBody['user_id'];
            $status = $msgBody['status'];
            if(empty($account) || empty($uid) || empty($status))   goto failed;
            try
            {
                $row = TrdCouponsReceviedTable::getInstance()->createQuery()
                    ->where('account = ?',$account)
                    ->andWhere('hupu_uid = ?',$uid)
                    ->andWhere('root_type = ?',1)
                    ->fetchOne();
                if(empty($row) )
                {
                    goto failed;
                }
                else
                {
                    $row->status = $status;
                    $row->recevied_date = time();
                    $row->save();
                    echo 'ok!';
                }
//                TrdCouponsReceviedTable::getInstance()->getConnection()->close();
            }
            catch(Exception $e)
            {
                goto failed;
            }

        }
        failed:
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

}
