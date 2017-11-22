<?php

/**
 * KaluliItem
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class KaluliItem extends BaseKaluliItem
{

    public static function getInstance()
    {
        return Doctrine_Core::getTable('KaluliItem');
    }

    public function getContent(){
        $content = false;
        if($this->getId()){
            $attr = KaluliItemAttrTable::getInstance()->createQuery()->where('item_id = ?',$this->getId())->fetchOne();
            $content = $attr['content'];
        }
        return $content;
    }

    public function getReview(){
        $content = false;
        if($this->getId()){
            $attr = KaluliItemAttrTable::getInstance()->createQuery()->where('item_id = ?',$this->getId())->fetchOne();
            $content = $attr['review'];
        }
        return $content;
    }

    public function getPicDetail(){
        $data = false;
        if($this->getId()){
            $attr = KaluliItemAttrTable::getInstance()->createQuery()->where('item_id = ?',$this->getId())->fetchOne();
            $data = unserialize($attr['pic_detail']);
        }
        return $data;
    }

    public function getAttrs(){
        $data = false;
        if($this->getId()){
            $attr = KaluliItemAttrTable::getInstance()->createQuery()->where('item_id = ?',$this->getId())->fetchOne();
            $data = unserialize($attr['attrs']);
        }
        return $data;
    }

//    public function setContent($val){
//        if($this->getId()){
//            $attr = KaluliArticleAttrTable::getInstance()->createQuery()->where('article_id = ?',$this->getId())->fetchOne();
//            $attr->setContent($val);
//            $attr->save();
//        }
//        $this->attribulte_array = $val;
//        return true;
//    }

    public function toArray($deep = true, $prefixKey = false) {
        $result = parent::toArray($deep, $prefixKey);
        $result['content'] = $this->getContent();
        $result['review'] = $this->getReview();
        $result['pic_detail'] = $this->getPicDetail();
        return $result;
    }

    public function postInsert($event)
    {
        $message = array(
            'id' => $this->getId(),
            'type' => 'create',
            'channelType' => 'item'
        );
        $this->sendMqMessage($message);
        parent::postInsert($event);
    }

    public function preUpdate($event)
    {
        $new = $this->getModified();
        $modified = array_keys($new);

        $updateFields = array('title','intro','discount_price','status','brand_id','created_at','status_es');
//        //判断能否被ES搜索
        $new_status_es=$this->getStatusEs();
         if(array_intersect($updateFields,$modified)){
            if($new_status_es==1)
            {
                $message = array(
                    'id' => $this->getId(),
                    'type' => 'update',
                    'channelType' => 'item'
                );
            }
            else
            {
                $message = array(
                    'id' => $this->getId(),
                    'type' => 'delete',
                    'channelType' => 'item'
                );
            }
            $this->sendMqMessage($message);
        }
        parent::preUpdate($event);
    }

    public function postDelete($event)
    {
        $message = array(
            'id' => $this->getId(),
            'type' => 'delete',
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
