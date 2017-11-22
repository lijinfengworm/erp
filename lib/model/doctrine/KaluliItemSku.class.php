<?php

/**
 * KaluliItemSku
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class KaluliItemSku extends BaseKaluliItemSku
{
    public static $storeHouses = array(
        1=>'识货仓库',
        2=>'上海',
    );
    public function getAttrForamt()
    {
        return unserialize($this->getAttr());
    }

    public function setAttrForamt()
    {
        $this->setAttr(serialize($this->attr));
    }


    public function postInsert($event)
    {
        $message = array(
            'id' => $this->getItemId(),
            'type' => 'update',
            'channelType' => 'item'
        );
        $this->sendMqMessage($message);
        parent::postInsert($event);
    }

    public function preUpdate($event)
    {
        $new = $this->getModified();
        $modified = array_keys($new);

        $updateFields = array(
            'storehouse_id',
            'total_num',
            'lock_num',
            'status',
        );
        if (array_intersect($updateFields, $modified)) {#消息队列
            $message = array(
                'id' => $this->getItemId(),
                'type' => 'update',
                'channelType' => 'item'
            );
            $this->sendMqMessage($message);
        }

        parent::preUpdate($event);
    }

    public function postDelete($event)
    {
        $message = array(
            'id' => $this->getItemId(),
            'type' => 'update',
            'channelType' => 'item'
        );
        $this->sendMqMessage($message);
        parent::postDelete($event);
    }

    public function sendMqMessage($message)
    {
        $amqpParams = sfConfig::get("app_mabbitmq_options_kaluli");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'], $amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);
        $channel = $connection->channel();
        $arguments = array(
            "x-dead-letter-exchange" => array("S", "amq.topic"),
            "x-message-ttl" => array("I", 2000),
            "x-dead-letter-routing-key" => array("S", "kaluli.item.detail")
        );
        $channel->queue_declare('kaluli_item_deferred', false, true, false, false, false, $arguments);

        $msg = new AMQPMessage(json_encode($message));
        $channel->basic_publish($msg, '', 'kaluli_item_deferred');
    }
}
