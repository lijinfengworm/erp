<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KaluliOrderWarelog', 'kaluli');

/**
 * BaseKaluliOrderWarelog
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $order_number
 * @property integer $order_id
 * @property string $msg
 * @property integer $hupu_uid
 * @property string $username
 * @property integer $ware_type
 * @property integer $status
 * @property integer $ware_order_type
 * 
 * @method integer            getId()              Returns the current record's "id" value
 * @method string             getOrderNumber()     Returns the current record's "order_number" value
 * @method integer            getOrderId()         Returns the current record's "order_id" value
 * @method string             getMsg()             Returns the current record's "msg" value
 * @method integer            getHupuUid()         Returns the current record's "hupu_uid" value
 * @method string             getUsername()        Returns the current record's "username" value
 * @method integer            getWareType()        Returns the current record's "ware_type" value
 * @method integer            getStatus()          Returns the current record's "status" value
 * @method integer            getWareOrderType()   Returns the current record's "ware_order_type" value
 * @method KaluliOrderWarelog setId()              Sets the current record's "id" value
 * @method KaluliOrderWarelog setOrderNumber()     Sets the current record's "order_number" value
 * @method KaluliOrderWarelog setOrderId()         Sets the current record's "order_id" value
 * @method KaluliOrderWarelog setMsg()             Sets the current record's "msg" value
 * @method KaluliOrderWarelog setHupuUid()         Sets the current record's "hupu_uid" value
 * @method KaluliOrderWarelog setUsername()        Sets the current record's "username" value
 * @method KaluliOrderWarelog setWareType()        Sets the current record's "ware_type" value
 * @method KaluliOrderWarelog setStatus()          Sets the current record's "status" value
 * @method KaluliOrderWarelog setWareOrderType()   Sets the current record's "ware_order_type" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKaluliOrderWarelog extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_order_warelog');
        $this->hasColumn('id', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 10,
             ));
        $this->hasColumn('order_number', 'string', 255, array(
             'type' => 'string',
             'unsigned' => true,
             'length' => 255,
             ));
        $this->hasColumn('order_id', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 10,
             ));
        $this->hasColumn('msg', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('hupu_uid', 'integer', 8, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 8,
             ));
        $this->hasColumn('username', 'string', 20, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 20,
             ));
        $this->hasColumn('ware_type', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'length' => 1,
             ));
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'length' => 1,
             ));
        $this->hasColumn('ware_order_type', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'length' => 1,
             ));


        $this->index('order_number', array(
             'fields' => 
             array(
              0 => 'order_number',
             ),
             ));
        $this->index('order_id', array(
             'fields' => 
             array(
              0 => 'order_id',
             ),
             ));
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}