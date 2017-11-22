<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllWarehousesExpressArea', 'kaluli');

/**
 * BaseKllWarehousesExpressArea
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $ware_express_id
 * @property integer $provinces
 * @property integer $is_default
 * @property float $first_price
 * @property float $additional_price
 * @property timestamp $ct_time
 * 
 * @method integer                  getId()               Returns the current record's "id" value
 * @method integer                  getWareExpressId()    Returns the current record's "ware_express_id" value
 * @method integer                  getProvinces()        Returns the current record's "provinces" value
 * @method integer                  getIsDefault()        Returns the current record's "is_default" value
 * @method float                    getFirstPrice()       Returns the current record's "first_price" value
 * @method float                    getAdditionalPrice()  Returns the current record's "additional_price" value
 * @method timestamp                getCtTime()           Returns the current record's "ct_time" value
 * @method KllWarehousesExpressArea setId()               Sets the current record's "id" value
 * @method KllWarehousesExpressArea setWareExpressId()    Sets the current record's "ware_express_id" value
 * @method KllWarehousesExpressArea setProvinces()        Sets the current record's "provinces" value
 * @method KllWarehousesExpressArea setIsDefault()        Sets the current record's "is_default" value
 * @method KllWarehousesExpressArea setFirstPrice()       Sets the current record's "first_price" value
 * @method KllWarehousesExpressArea setAdditionalPrice()  Sets the current record's "additional_price" value
 * @method KllWarehousesExpressArea setCtTime()           Sets the current record's "ct_time" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllWarehousesExpressArea extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_warehouses_express_area');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('ware_express_id', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('provinces', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('is_default', 'integer', 2, array(
             'type' => 'integer',
             'length' => 2,
             ));
        $this->hasColumn('first_price', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
             ));
        $this->hasColumn('additional_price', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
             ));
        $this->hasColumn('ct_time', 'timestamp', null, array(
             'type' => 'timestamp',
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