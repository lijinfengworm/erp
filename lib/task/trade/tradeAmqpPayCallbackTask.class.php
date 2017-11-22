<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeAmqpPayCallbackTask extends sfBaseTask
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
        $this->name             = 'AmqpPayCallback';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:CreateCiTieForWP|INFO] task does things.
Call it with:

  [php symfony trade:Amqp|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);

        ini_set('memory_limit','64M');

        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);

        $channel = $connection->channel();
        $channel->queue_declare('shihuo.pay.callback', false, true, false, false, false);
        $channel->queue_bind('shihuo.pay.callback', "amq.topic","shihuo.pay.callback.error");
        $channel->basic_consume('shihuo.pay.callback', '', false, false, false, false, 'tradeAmqpPayCallbackTask::callback');

        while(count($channel->callbacks) ) {
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
            $payId = $msgBody['pay_id'];
            $paySign = $msgBody['sign'];
            $trdPayOrderTable = TrdPayOrderTable::getInstance();
            $payOrder = $trdPayOrderTable->findOneBy('id', $payId);

            if($payOrder){
                $res = tradeCommon::requestUrl($payOrder->getNotifyUrl(),'POST',http_build_query($msgBody),NULL,5);
                echo $res;
                if(!self::is_not_json($res)) $res = json_decode($res,true);

                if(isset($res['status']) && $res['status']){
                    echo '订单ID'.$payId.'回调成功;sign为'.$paySign;
                }else{
                    if($payOrder->getCallbackErrorNum() < self::ERROR_NUM){
                        $payOrder->setCallbackErrorNum($payOrder->getCallbackErrorNum()+1);                       //支付回调
                        $payOrder->save();

                        self::_sendPayCallbackErrorMessage($msg->body);                                           //失败写入错误队列
                    }
                }

            }
//            foreach (Doctrine_Manager::getInstance()->getConnections() as $con )
//            {
//                $con->close();
//            }

        }

        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }


    private static function _sendPayCallbackErrorMessage($message)
    {
        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);
        $channel = $connection->channel();
        $arguments = array(
            "x-dead-letter-exchange" => array("S", "amq.topic"),
            "x-message-ttl" => array("I", 1000*60),                                                    //延迟一分钟
            "x-dead-letter-routing-key" => array("S", "shihuo.pay.callback.error")
        );
        $channel->queue_declare('shihuo.pay.callback2', false, true, false, false, false, $arguments);

        $msg = new AMQPMessage($message);
        $channel->basic_publish($msg, '', 'shihuo.pay.callback2');
    }

    private static  function is_not_json($str){
        return is_null(json_decode($str));
    }
}
