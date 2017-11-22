<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('kllOrderCustoms', 'kaluli');

/**
 * BasekllOrderCustoms
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $logisticsId
 * @property integer $order_number
 * @property float $order_price
 * @property float $customs_dutys
 * @property string $customs_img
 * @property float $customs_rate
 * @property int $cost_unit
 * @property int $status
 * @property int $check_status
 * @property timestamp $create_time
 * @property datetime $check_time
 * 
 * @method integer         getId()            Returns the current record's "id" value
 * @method string          getLogisticsId()   Returns the current record's "logisticsId" value
 * @method integer         getOrderNumber()   Returns the current record's "order_number" value
 * @method float           getOrderPrice()    Returns the current record's "order_price" value
 * @method float           getCustomsDutys()  Returns the current record's "customs_dutys" value
 * @method string          getCustomsImg()    Returns the current record's "customs_img" value
 * @method float           getCustomsRate()   Returns the current record's "customs_rate" value
 * @method int             getCostUnit()      Returns the current record's "cost_unit" value
 * @method int             getStatus()        Returns the current record's "status" value
 * @method int             getCheckStatus()   Returns the current record's "check_status" value
 * @method timestamp       getCreateTime()    Returns the current record's "create_time" value
 * @method datetime        getCheckTime()     Returns the current record's "check_time" value
 * @method kllOrderCustoms setId()            Sets the current record's "id" value
 * @method kllOrderCustoms setLogisticsId()   Sets the current record's "logisticsId" value
 * @method kllOrderCustoms setOrderNumber()   Sets the current record's "order_number" value
 * @method kllOrderCustoms setOrderPrice()    Sets the current record's "order_price" value
 * @method kllOrderCustoms setCustomsDutys()  Sets the current record's "customs_dutys" value
 * @method kllOrderCustoms setCustomsImg()    Sets the current record's "customs_img" value
 * @method kllOrderCustoms setCustomsRate()   Sets the current record's "customs_rate" value
 * @method kllOrderCustoms setCostUnit()      Sets the current record's "cost_unit" value
 * @method kllOrderCustoms setStatus()        Sets the current record's "status" value
 * @method kllOrderCustoms setCheckStatus()   Sets the current record's "check_status" value
 * @method kllOrderCustoms setCreateTime()    Sets the current record's "create_time" value
 * @method kllOrderCustoms setCheckTime()     Sets the current record's "check_time" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasekllOrderCustoms extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_order_customs');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('logisticsId', 'string', 32, array(
             'type' => 'string',
             'length' => 32,
             ));
        $this->hasColumn('order_number', 'integer', 10, array(
             'type' => 'integer',
             'length' => 10,
             ));
        $this->hasColumn('order_price', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
             ));
        $this->hasColumn('customs_dutys', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
             ));
        $this->hasColumn('customs_img', 'string', 512, array(
             'type' => 'string',
             'length' => 512,
             ));
        $this->hasColumn('customs_rate', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
             ));
        $this->hasColumn('cost_unit', 'int', 4, array(
             'type' => 'int',
             'length' => 4,
             ));
        $this->hasColumn('status', 'int', 4, array(
             'type' => 'int',
             'length' => 4,
             ));
        $this->hasColumn('check_status', 'int', 4, array(
             'type' => 'int',
             'length' => 4,
             ));
        $this->hasColumn('create_time', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('check_time', 'datetime', null, array(
             'type' => 'datetime',
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