<?php

//hcRabbitMQPublisher::getInstance('direct_test')->publish(new hcAMQPMessage('1111111.22222222.3333333.a'));
//hcRabbitMQPublisher::getInstance('fanout_test')->publish(new hcAMQPMessage('1111111.22222222.3333333.a'));
//hcRabbitMQPublisher::getInstance('topic_test')->publish(new hcAMQPMessage('1111111.22222222.3333333.a'),'h.b');

class hcRabbitMQConsumer {

    private static $instance = null;
    protected $conn;
    protected $channel;
    protected $options;

    /**
     * Singleton accessor
     *
     * @return hcRabbitMQConsumer
     */
    public static function getInstance($queue) {
         //register_shutdown_function(array($this,'shutdown'));
        if (isset(self::$instance[$queue]) && self::$instance[$queue] != null) {
            return self::$instance[$queue];
        }
        self::$instance[$queue] = new hcRabbitMQConsumer(hcRabbitMQUtils::getPublisherOptions($queue));
        
       
        return self::$instance[$queue];
    }
    
    public function shutdown(){
        $e = error_get_last();
        echo $e;
        return;
    }

    private function __construct($options) {
        $this->options = $options;

        $this->conn = new AMQPConnection($this->options['host'], $this->options['port'],
                        $this->options['user'], $this->options['pass']);
        $this->channel = $this->conn->channel();
        $this->channel->access_request($this->options['vhost'], false, false, true, true);
    }

    public function consumer() {
        if ($this->options['type'] == 'topic' && empty($routing_key)) {
            throw new Exception('you are using type topic but without $routing_key,please define the key first');
        }

        $this->channel->exchange_declare($this->options['exchange'], $this->options['type'], false, true, false);
        $this->channel->queue_declare($this->options['queue'], false, true, false, false);
        $this->channel->queue_bind($this->options['queue'], $this->options['exchange']);

        $this->channel->basic_consume($this->options['queue'], 'consumer', false, false, false, false, array($this, 'process_message'));
        while(count($this->channel->callbacks)) {
            $this->channel->wait();
        }

//        $ch->close();
//        $conn->close();

    }
    public function process_message($msg) {
        static $mysphinxDb = null;
        if(!is_object($msg)){
            echo '队列异常，获取不到消息\n';
            return;
        }
        
        $data = json_decode($msg->body,true);
        
        if($mysphinxDb == null){
             $mysphinxDb = new tradeSpinxMysql();
        }
        
        $flag = 0;
       
        $result = '';
        if (isset($data['shoe']) && !empty($data['shoe']) && isset($data['shoe_id'])){//初始化鞋类导入数据到sphinx
            $allitem =  TrdItemTable::getInstance()->findOneById($data['shoe_id']);
            $brands = array(
                '1' => 3,
                '2' => 4,
                '3' => 5,
                '4' => 6,
                '5' => 8,
                '6' => 9,
                '14' => 10,
                '15' => 13,
                '16' => 11,
                '17' => 12,
                '18' => 20,
                '13' => 20,
                '12' => 20,
                '11' => 20,
                '10' => 20,
                '9' => 20,
                '7' => 20,
                '19' => 20,
                '8' => 7,
            );
            $categorys = array(
                '1' => 14,
                '2' => 15,
                '3' => 16,
                '9' => 17,
                '4' => 20,
                '5' => 18,
                '8' => 19,
                '10' => 20,
                '7' => 20,
                '6' => 20,
                '11' => 20,
            );
            if ($allitem && $allitem->getId()){
                $brandid = $allitem->getBrandId();
                $categoryid = $allitem->getCategoryId();
                $info = 'R1 C8 ';
                if (!empty($brandid) && !empty($brands[$brandid])){
                    $info .= 'G1A'.$brands[$brandid].' ';
                }
                if (!empty($categoryid) && !empty($categorys[$categoryid])){
                    $info .= 'G2A'.$categorys[$categoryid].' ';
                }
                $info .= 'G3A1';
                $param = array(
                    'id' => $data['id'],
                    'title' => $allitem->getTitle(),
                    'info' => $info,
                    'hot' => $allitem->getClickCount(),
                    'price' => $allitem->getPrice(),
                    'time' => $allitem->getPublishDate(),
                    'infos' => $allitem->getTitle().' '.$info,
                );
                $result = $mysphinxDb->saveData($param);
                if(! $result){
                    var_dump($param);
                    echo '\n运动鞋保存到sphinx失败\n';
                    return;
                }
            }
            $flag = 1;
        } else if (isset($data['shoe']) && !empty($data['shoe'])){//初始化其他类商品导入数据到sphinx
            $allitem =  TrdItemAllTable::getInstance()->find($data['id']);
            $category = array(
                '2' => "R1 C9",
                '3' => "R1",
                '4' => "R1",
                '5' => "R3",
                '6' => "R7",
            );
            if ($allitem && $allitem->getId()){
                $categoryid = $allitem->getCategoryAllId();
                if ($categoryid && $categoryid != 1){
                    $info = $category[$categoryid];
                }
                $param = array(
                    'id' => $data['id'],
                    'title' => $allitem->getTitle(),
                    'info' => $info,
                    'hot' => $allitem->getClickCount(),
                    'price' => $allitem->getPrice(),
                    'time' => $allitem->getPublishDate(),
                    'infos' => $allitem->getTitle().' '.$info,
                );
                $result = $mysphinxDb->saveData($param);
                if(! $result){
                    var_dump($param);
                    echo '\n其他数据保存到sphinx失败\n';
                    return;
                }
            } 
            $flag = 2;
        } else {
            if ($data['type'] == 1){//删除
                $result = $mysphinxDb->deleteOne($data['id']);
                if(! $result){
                    echo '\n删除sphinx中id为'.$data['id'].'失败\n';
                    return;
                }
            } else {//新增、修改
                $item = TrdItemAllTable::getInstance()->find($data['id']);
                if ($item){
                    $info = '';
                    if ($item->getRootId() && $item->getChildrenId()){
                        $info = 'R'.$item->getRootId().' C'.$item->getChildrenId().' '.str_replace('-','',str_replace(',', ' ', $item->getAttrCollect()));
                    }
                    $param = array(
                        'id' => $item->getId(),
                        'title' => $item->getTitle(),
                        'info' => $info,
                        'hot' => $item->getClickCount(),
                        'price' => $item->getPrice(),
                        'time' => $item->getPublishDate(),
                        'infos' => $item->getTitle().' '.$info,
                    );
                    $result = $mysphinxDb->saveData($param);
                    if(! $result){
                        var_dump($param);
                        echo '\n正常流程数据保存到sphinx失败\n';
                        return;
                    }
                }
            }
            $flag = 3;
        }
        $time = date('Y-m-d H:i:s',time());
        file_put_contents('/tmp/shihuo_rabbitmq_'.date('Ymd').'.log', $time.'|||'.$data['id'].'|||'.$data['type'].'|||'.$flag.PHP_EOL, FILE_APPEND);
        echo $time.'|||'.$data['id'].'|||'.$data['type'].'|||'.$flag.PHP_EOL;
        if($result) $this->channel->basic_ack($msg->delivery_info['delivery_tag']);
        // Cancel callback
        if ($msg->body === 'quit') {
            $ch->basic_cancel('consumer');
        }
    }

}