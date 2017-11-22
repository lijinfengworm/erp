<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllOrderActivityDetail', 'kaluli');

/**
 * BaseKllOrderActivityDetail
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $order_number
 * @property integer $activity_id
 * @property string $attr
 * @property integer $type
 * @property integer $refund_type
 * 
 * @method integer                getId()           Returns the current record's "id" value
 * @method string                 getOrderNumber()  Returns the current record's "order_number" value
 * @method integer                getActivityId()   Returns the current record's "activity_id" value
 * @method string                 getAttr()         Returns the current record's "attr" value
 * @method integer                getType()         Returns the current record's "type" value
 * @method integer                getRefundType()   Returns the current record's "refund_type" value
 * @method KllOrderActivityDetail setId()           Sets the current record's "id" value
 * @method KllOrderActivityDetail setOrderNumber()  Sets the current record's "order_number" value
 * @method KllOrderActivityDetail setActivityId()   Sets the current record's "activity_id" value
 * @method KllOrderActivityDetail setAttr()         Sets the current record's "attr" value
 * @method KllOrderActivityDetail setType()         Sets the current record's "type" value
 * @method KllOrderActivityDetail setRefundType()   Sets the current record's "refund_type" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllOrderActivityDetail extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_order_activity_detail');
        $this->hasColumn('id', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 10,
             ));
        $this->hasColumn('order_number', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('activity_id', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 10,
             ));
        $this->hasColumn('attr', 'string', 10000, array(
             'type' => 'string',
             'length' => 10000,
             ));
        $this->hasColumn('type', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('refund_type', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));


        $this->index('order_number', array(
             'fields' => 
             array(
              0 => 'order_number',
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