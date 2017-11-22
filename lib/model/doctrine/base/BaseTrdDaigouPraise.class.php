<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdDaigouPraise', 'trade');

/**
 * BaseTrdDaigouPraise
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $product_id
 * @property integer $hupu_uid
 * @property string $hupu_username
 * @property datetime $create_time
 * @property integer $is_delete
 * 
 * @method integer         getId()            Returns the current record's "id" value
 * @method integer         getProductId()     Returns the current record's "product_id" value
 * @method integer         getHupuUid()       Returns the current record's "hupu_uid" value
 * @method string          getHupuUsername()  Returns the current record's "hupu_username" value
 * @method datetime        getCreateTime()    Returns the current record's "create_time" value
 * @method integer         getIsDelete()      Returns the current record's "is_delete" value
 * @method TrdDaigouPraise setId()            Sets the current record's "id" value
 * @method TrdDaigouPraise setProductId()     Sets the current record's "product_id" value
 * @method TrdDaigouPraise setHupuUid()       Sets the current record's "hupu_uid" value
 * @method TrdDaigouPraise setHupuUsername()  Sets the current record's "hupu_username" value
 * @method TrdDaigouPraise setCreateTime()    Sets the current record's "create_time" value
 * @method TrdDaigouPraise setIsDelete()      Sets the current record's "is_delete" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdDaigouPraise extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_daigou_praise');
        $this->hasColumn('id', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 10,
             ));
        $this->hasColumn('product_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => false,
             ));
        $this->hasColumn('hupu_uid', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('hupu_username', 'string', 30, array(
             'type' => 'string',
             'length' => 30,
             ));
        $this->hasColumn('create_time', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('is_delete', 'integer', 1, array(
             'type' => 'integer',
             'default' => 0,
             'length' => 1,
             ));


        $this->index('hupu_uid', array(
             'fields' => 
             array(
              0 => 'hupu_uid',
             ),
             ));
        $this->index('item_id', array(
             'fields' => 
             array(
              0 => 'product_id',
             ),
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