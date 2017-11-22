<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdShop', 'trade');

/**
 * BaseTrdShop
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $external_id
 * @property string $name
 * @property string $owner_name
 * @property string $link
 * @property integer $item_count
 * @property smallint $src
 * @property integer $status
 * @property datetime $ban_start_time
 * @property datetime $ban_end_time
 * 
 * @method integer  getId()             Returns the current record's "id" value
 * @method integer  getExternalId()     Returns the current record's "external_id" value
 * @method string   getName()           Returns the current record's "name" value
 * @method string   getOwnerName()      Returns the current record's "owner_name" value
 * @method string   getLink()           Returns the current record's "link" value
 * @method integer  getItemCount()      Returns the current record's "item_count" value
 * @method smallint getSrc()            Returns the current record's "src" value
 * @method integer  getStatus()         Returns the current record's "status" value
 * @method datetime getBanStartTime()   Returns the current record's "ban_start_time" value
 * @method datetime getBanEndTime()     Returns the current record's "ban_end_time" value
 * @method TrdShop  setId()             Sets the current record's "id" value
 * @method TrdShop  setExternalId()     Sets the current record's "external_id" value
 * @method TrdShop  setName()           Sets the current record's "name" value
 * @method TrdShop  setOwnerName()      Sets the current record's "owner_name" value
 * @method TrdShop  setLink()           Sets the current record's "link" value
 * @method TrdShop  setItemCount()      Sets the current record's "item_count" value
 * @method TrdShop  setSrc()            Sets the current record's "src" value
 * @method TrdShop  setStatus()         Sets the current record's "status" value
 * @method TrdShop  setBanStartTime()   Sets the current record's "ban_start_time" value
 * @method TrdShop  setBanEndTime()     Sets the current record's "ban_end_time" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdShop extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_shops');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('external_id', 'integer', null, array(
             'type' => 'integer',
             'unique' => true,
             'notnull' => true,
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'unique' => true,
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('owner_name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('link', 'string', 2000, array(
             'type' => 'string',
             'length' => 2000,
             ));
        $this->hasColumn('item_count', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             ));
        $this->hasColumn('src', 'smallint', null, array(
             'type' => 'smallint',
             ));
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('ban_start_time', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('ban_end_time', 'datetime', null, array(
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