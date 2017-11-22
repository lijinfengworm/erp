<?php
/**
 * 卡路里AmpqMQ
 *  消息对列
 */
class KllAmqpMQ
{
    private $conn;
    private $redis;

    //用于存放实例化的对象
    static private $_instance;


    public function __construct()
    {
        //连接
        $amqpParams = sfConfig::get("app_mabbitmq_options_kaluli");
        $this->conn = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'], $amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);
        //初始化redis服务
        $this->redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $this->redis->select(10);
    }

    //公共静态方法获取实例化的对象
    static public function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    //发送任务(工作队列)
    public function setMqTask($argv, $key = 'kaluli_common_task'){
        if(empty($key)) throw new Exception("默认队列名不能为空~", 1);
        $channel = $this->conn->channel();
        //直连交换机
        $arguments = array(
            //设置ttl超时转发到kaluli.topic交换机上
            "x-dead-letter-exchange" => array("S", "kaluli.topic"),
            "x-message-ttl" => array("I", 2000),
            //设置ttl超时转发到key为kaluli.common.exchange的队列上 
            "x-dead-letter-routing-key" => array("S", "kaluli.common.exchange")
        );
        $channel->queue_declare($key, false, true, false, false);
        //待处理参数
        $data = $argv;
        $msg = new AMQPMessage(json_encode($data));
        $channel->basic_publish($msg, '', $key);
    }
    //workers
    public function comMqWorker($type, $key='kaluli_common_task'){
        $channel = $this->conn->channel();
        $channel->queue_declare($key, false, true, false, false, false);
        // $channel->queue_bind($key, "kaluli.topic","kaluli.common.exchange");
        // $channel->basic_qos(null, 1, null);
        switch ($type) {
            case 'log':
                $channel->basic_consume($key, '', false, false, false, false, 'KllAmqpMQ::callbackLog');
                break;
            default:
                $channel->basic_consume($key, '', false, false, false, false, 'KllAmqpMQ::callback');
                break;
        }
        while(count($channel->callbacks)) {
            $channel->wait();
        }
        
    }
    //日志回调函数
    public static function callbackLog($msg){
        echo "msg:", $msg->body, "\n";
        $msgBody = json_decode($msg->body, true);
        if($msgBody){
            $message = $msgBody['msg'];
            if(!empty($message)){
                //处理逻辑
                logKaluliService::writeLog($message);
                return true;
            } 
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            
        }
    }
    //支付回调函数
    public static function callbackPay($msg){
        echo "msg:", $msg->body, "\n";
        $msgBody = json_decode($msg->body, true);
        if($msgBody){
            $order_number = $msgBody['order_number'];
            
            if(!empty($order_number)){
                //处理逻辑
                $item = KllBBMainOrderTable::getOneByOrderNumber($order_number);
                $serviceRequest = new kaluliServiceClient();
                $mainOrderAttrObj = KllBBMainOrderAttrTable::getInstance()->findOneByOrderNumber($order_number);
                if(!empty($mainOrderAttrObj)){
                    $item['real_name'] = $mainOrderAttrObj->getRealName();
                    $item['card_code'] = $mainOrderAttrObj->getCardCode();
                }
                $serviceRequest->setMethod('bb.sendXml');
                $serviceRequest->setApiParam('type', 'PUSH');
                $serviceRequest->setApiParam('main_order', $item);
                $serviceRequest->setVersion('1.0');
                $response = $serviceRequest->execute();
                if ($response->hasError()) {
                    //写入日志，其实也不用写，错就抛出异常就行了
                    throw new Exception("未知错误", 1);
                }
                return true;
            } 
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            
        }
    }
    //公共回调函数
    public static function callback($msg){
        echo ' [x] ',$msg->delivery_info['routing_key'], ':', $msg->body, "\n";
        echo "msg:", $msg->body, "\n";
        exit;
        $msgBody = json_decode($msg->body, true);

        if($msgBody){
            $account = $msgBody['arg'];
            var_dump($account);exit;
            if(empty($account)){
                //$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            } else{
                //处理逻辑
            }
            
        }
    }
    //主题交换机
    public function setExchangeMqTast($routing_key, $send_data){
        ini_set('memory_limit','120M');
        $channel = $this->conn->channel();

        $channel->exchange_declare('topic_logs_test', 'topic', false, false, false);

        $routing_key = isset($routing_key) ? $routing_key : 'anonymous.info';
        $data = json_encode($send_data);
        $msg = new AMQPMessage($data);

        $channel->basic_publish($msg, 'topic_logs_test', $routing_key);
        echo " [x] Sent log mq message ",$routing_key,':'," \n";
    }
    //接收主题交换机
    public function getExchangeMqWorker($opt){
        $type = $opt['type'];

        $channel = $this->conn->channel();

        $channel->exchange_declare('topic_logs_test', 'topic', false, false, false);

        list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);
        //binding_keys接收的应该是固定key的参数。这里是接收来自erp的所有消息
        $binding_keys = ["kaluli.erp.".$type];
        if( empty($binding_keys)) {
            exit(1);
        }

        foreach($binding_keys as $binding_key) {
            $channel->queue_bind($queue_name, 'topic_logs_test', $binding_key);
        }

        echo ' [*] Waiting for logs. To exit press CTRL+C', "\n";
       
        switch ($type) {
            case 'log':
                $channel->basic_consume($queue_name, '', false, false, false, false, 'KllAmqpMQ::callbackLog');
                break;
            case 'pay':
                $channel->basic_consume($queue_name, '', false, false, false, false, 'KllAmqpMQ::callbackPay');
                break;
            default:
                $channel->basic_consume($queue_name, '', false, false, false, false, 'KllAmqpMQ::callback');
                break;
        }
        // $channel->basic_consume($queue_name, '', false, true, false, false, 'KllAmqpMQ::callback');
        while(count($channel->callbacks) ) {
            $nowmem = memory_get_usage()/1024/1024;
            if($nowmem <60){
                $channel->wait();
            }else{
                break;
            }
        }
        
    }
    
}