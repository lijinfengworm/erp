<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllBBMq', 'kaluliBB');

/**
 * BaseKllBBMq
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property int $id
 * @property varchar $msg_type
 * @property varchar $msg_id
 * @property varchar $msg_channel
 * @property text $msg_body
 * @property integer $msg_status
 * @property varchar $order_number
 * @property integer $zt
 * @property timestamp $msg_time
 * @property text $msg_response
 * @property text $notify_response
 * 
 * @method int       getId()              Returns the current record's "id" value
 * @method varchar   getMsgType()         Returns the current record's "msg_type" value
 * @method varchar   getMsgId()           Returns the current record's "msg_id" value
 * @method varchar   getMsgChannel()      Returns the current record's "msg_channel" value
 * @method text      getMsgBody()         Returns the current record's "msg_body" value
 * @method integer   getMsgStatus()       Returns the current record's "msg_status" value
 * @method varchar   getOrderNumber()     Returns the current record's "order_number" value
 * @method integer   getZt()              Returns the current record's "zt" value
 * @method timestamp getMsgTime()         Returns the current record's "msg_time" value
 * @method text      getMsgResponse()     Returns the current record's "msg_response" value
 * @method text      getNotifyResponse()  Returns the current record's "notify_response" value
 * @method KllBBMq   setId()              Sets the current record's "id" value
 * @method KllBBMq   setMsgType()         Sets the current record's "msg_type" value
 * @method KllBBMq   setMsgId()           Sets the current record's "msg_id" value
 * @method KllBBMq   setMsgChannel()      Sets the current record's "msg_channel" value
 * @method KllBBMq   setMsgBody()         Sets the current record's "msg_body" value
 * @method KllBBMq   setMsgStatus()       Sets the current record's "msg_status" value
 * @method KllBBMq   setOrderNumber()     Sets the current record's "order_number" value
 * @method KllBBMq   setZt()              Sets the current record's "zt" value
 * @method KllBBMq   setMsgTime()         Sets the current record's "msg_time" value
 * @method KllBBMq   setMsgResponse()     Sets the current record's "msg_response" value
 * @method KllBBMq   setNotifyResponse()  Sets the current record's "notify_response" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllBBMq extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_mq');
        $this->hasColumn('id', 'int', 10, array(
             'type' => 'int',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 10,
             ));
        $this->hasColumn('msg_type', 'varchar', 255, array(
             'type' => 'varchar',
             'length' => 255,
             ));
        $this->hasColumn('msg_id', 'varchar', 255, array(
             'type' => 'varchar',
             'length' => 255,
             ));
        $this->hasColumn('msg_channel', 'varchar', 255, array(
             'type' => 'varchar',
             'length' => 255,
             ));
        $this->hasColumn('msg_body', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('msg_status', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 11,
             ));
        $this->hasColumn('order_number', 'varchar', 255, array(
             'type' => 'varchar',
             'length' => 255,
             ));
        $this->hasColumn('zt', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 11,
             ));
        $this->hasColumn('msg_time', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('msg_response', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('notify_response', 'text', null, array(
             'type' => 'text',
             ));

        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}