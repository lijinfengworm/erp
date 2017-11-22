<?php

//hcRabbitMQPublisher::getInstance('direct_test')->publish(new hcAMQPMessage('1111111.22222222.3333333.a'));
//hcRabbitMQPublisher::getInstance('fanout_test')->publish(new hcAMQPMessage('1111111.22222222.3333333.a'));
//hcRabbitMQPublisher::getInstance('topic_test')->publish(new hcAMQPMessage('1111111.22222222.3333333.a'),'h.b');

class hcRabbitMQPublisher {

    private static $instance = null;
    protected $conn;
    protected $channel;
    protected $options;

    /**
     * Singleton accessor
     *
     * @return hcRabbitMQPublisher
     */
    public static function getInstance($queue) {
        if (isset(self::$instance[$queue]) && self::$instance[$queue] != null) {
            return self::$instance[$queue];
        }

        self::$instance[$queue] = new hcRabbitMQPublisher(hcRabbitMQUtils::getPublisherOptions($queue));

        return self::$instance[$queue];
    }

    private function __construct($options) {
        $this->options = $options;

        $this->conn = new AMQPConnection($this->options['host'], $this->options['port'],
                        $this->options['user'], $this->options['pass']);

        $this->channel = $this->conn->channel();
        $this->channel->access_request($this->options['vhost'], false, false, true, true);
    }

    public function publish($msg, $routing_key="") {
        if ($this->options['type'] == 'topic' && empty($routing_key)) {
            throw new Exception('you are using type topic but without $routing_key,please define the key first');
        }
        if(!empty($this->options['exchange']))
        {
            //创建对应默认路由
            $this->channel->exchange_declare($this->options['exchange'], $this->options['type'], false, true, false);
        }else{
            //如果没指定exchange 就根据 type指定默认的
            switch ($this->options['type'])
            {
                case 'direct':
                    $this->options['exchange'] = 'amq.direct';
                    break;
                case 'fanout':
                    $this->options['exchange'] = 'amq.fanout';
                    break;
                case 'headers':
                    $this->options['exchange'] = 'amq.headers';
                    break;
                case 'topic':
                    $this->options['exchange'] = 'amq.topic';
                    break;
            }
        }
        if(!empty($this->options['queue'])){
            $this->channel->queue_declare($this->options['queue'], false, true, false, false);
            $this->channel->queue_bind($this->options['queue'], $this->options['exchange'],  $routing_key);
        }
        
        //$this->channel->basic_publish(new AMQPMessage($msg->getJSON()), $this->options['exchange'], $routing_key);
        if ($msg instanceof PBMessage) {
            $this->channel->basic_publish(new AMQPMessage($msg->SerializeToString(), array('content_type' => 'application/x-protobuf', 'delivery_mode' => 1)), $this->options['exchange'], $routing_key);
        } else {
            $this->channel->basic_publish(new AMQPMessage($msg->getJSON(), $msg->getOptions()), $this->options['exchange'], $routing_key);
        }
//$this->channel->close();
//$conn->close();
        //$this->channel->basic_publish(new AMQPMessage($msg->getSerialized(), $msg->getOptions()),
        //                      $this->options['exchange']);
    }

}