<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllXbuyItem', 'kaluli');

/**
 * BaseKllXbuyItem
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $activity_id
 * @property integer $item_id
 * @property integer $number
 * @property float $price
 * @property integer $status
 * @property string $title
 * @property float $origin_price
 * 
 * @method integer     getId()           Returns the current record's "id" value
 * @method integer     getActivityId()   Returns the current record's "activity_id" value
 * @method integer     getItemId()       Returns the current record's "item_id" value
 * @method integer     getNumber()       Returns the current record's "number" value
 * @method float       getPrice()        Returns the current record's "price" value
 * @method integer     getStatus()       Returns the current record's "status" value
 * @method string      getTitle()        Returns the current record's "title" value
 * @method float       getOriginPrice()  Returns the current record's "origin_price" value
 * @method KllXbuyItem setId()           Sets the current record's "id" value
 * @method KllXbuyItem setActivityId()   Sets the current record's "activity_id" value
 * @method KllXbuyItem setItemId()       Sets the current record's "item_id" value
 * @method KllXbuyItem setNumber()       Sets the current record's "number" value
 * @method KllXbuyItem setPrice()        Sets the current record's "price" value
 * @method KllXbuyItem setStatus()       Sets the current record's "status" value
 * @method KllXbuyItem setTitle()        Sets the current record's "title" value
 * @method KllXbuyItem setOriginPrice()  Sets the current record's "origin_price" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllXbuyItem extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_xbuy_item');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('activity_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('item_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('number', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('price', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
             ));
        $this->hasColumn('status', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('origin_price', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
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