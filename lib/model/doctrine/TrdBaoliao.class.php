<?php

/**
 * TrdBaoliao
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class TrdBaoliao extends BaseTrdBaoliao
{
    
    function getShopLink() {
        $link = "";

        if($this->getShopId()) {
            $shop = TrdShopTable::getInstance()->find($this->getShopId());
            if($shop) {
                if($shop->getExternalId()) {
                    $link = "http://shop" . $shop->getExternalId() . ".taobao.com";
                }
            }

        }

        return $link;
    }
    
    //根据id获取爆料商品
    function get_by_id_all($baoliao_id) {
        $item = TrdBaoliaoTable::getInstance()
            ->createQuery('t')
            ->where('t.id = ?', $baoliao_id)
            ->fetchOne();

        return $item;
    }

    public function preUpdate($event)
    {
        //注释爆料送金币
//        $new = $this->getModified();
//        $old = $this->getModified(true);
//        if($new['status'] == 2 && $old['status'] == 0)
//        {
//            $message = array();
//            $message['uid'] = $this->getHupuUid();
//            $message['username'] = $this->getHupuUsername();
//            $message['actionid'] = $this->getId();
//            $message['action'] = "BaoliaoThrough";
//            $this->sendMqMessage($message);
//        }
        parent::preUpdate($event);
    }

    private function sendMqMessage($message)
    {
        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);
        $channel = $connection->channel();
        $arguments = array(
            "x-dead-letter-exchange" => array("S", "amq.topic"),
            "x-message-ttl" => array("I", 2000),
            "x-dead-letter-routing-key" => array("S", "shihuo.baoliao")
        );
        $channel->queue_declare('shihuo.baoliao.deferred2', false, true, false, false, false, $arguments);
        $msg = new AMQPMessage(json_encode($message));
        $channel->basic_publish($msg, '', 'shihuo.baoliao.deferred2');
    }
}
