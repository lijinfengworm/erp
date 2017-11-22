<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllWarehousesExpressAreaProvince', 'kaluli');

/**
 * BaseKllWarehousesExpressAreaProvince
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $ware_express
 * @property integer $ware_express_area_id
 * @property integer $province
 * 
 * @method integer                          getId()                   Returns the current record's "id" value
 * @method integer                          getWareExpress()          Returns the current record's "ware_express" value
 * @method integer                          getWareExpressAreaId()    Returns the current record's "ware_express_area_id" value
 * @method integer                          getProvince()             Returns the current record's "province" value
 * @method KllWarehousesExpressAreaProvince setId()                   Sets the current record's "id" value
 * @method KllWarehousesExpressAreaProvince setWareExpress()          Sets the current record's "ware_express" value
 * @method KllWarehousesExpressAreaProvince setWareExpressAreaId()    Sets the current record's "ware_express_area_id" value
 * @method KllWarehousesExpressAreaProvince setProvince()             Sets the current record's "province" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllWarehousesExpressAreaProvince extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_warehouses_express_area_province');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('ware_express', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('ware_express_area_id', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('province', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
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