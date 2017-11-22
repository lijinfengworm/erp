<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdUserItem', 'trade');

/**
 * BaseTrdUserItem
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $user_id
 * @property integer $item_id
 * @property integer $item_all_id
 * @property integer $baoliao_id
 * @property integer $status
 * @property TrdUser $User
 * @property TrdItem $Item
 * @property TrdItemAll $ItemAll
 * 
 * @method integer     getId()          Returns the current record's "id" value
 * @method integer     getUserId()      Returns the current record's "user_id" value
 * @method integer     getItemId()      Returns the current record's "item_id" value
 * @method integer     getItemAllId()   Returns the current record's "item_all_id" value
 * @method integer     getBaoliaoId()   Returns the current record's "baoliao_id" value
 * @method integer     getStatus()      Returns the current record's "status" value
 * @method TrdUser     getUser()        Returns the current record's "User" value
 * @method TrdItem     getItem()        Returns the current record's "Item" value
 * @method TrdItemAll  getItemAll()     Returns the current record's "ItemAll" value
 * @method TrdUserItem setId()          Sets the current record's "id" value
 * @method TrdUserItem setUserId()      Sets the current record's "user_id" value
 * @method TrdUserItem setItemId()      Sets the current record's "item_id" value
 * @method TrdUserItem setItemAllId()   Sets the current record's "item_all_id" value
 * @method TrdUserItem setBaoliaoId()   Sets the current record's "baoliao_id" value
 * @method TrdUserItem setStatus()      Sets the current record's "status" value
 * @method TrdUserItem setUser()        Sets the current record's "User" value
 * @method TrdUserItem setItem()        Sets the current record's "Item" value
 * @method TrdUserItem setItemAll()     Sets the current record's "ItemAll" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdUserItem extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_user_item');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             ));
        $this->hasColumn('item_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => false,
             ));
        $this->hasColumn('item_all_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => false,
             ));
        $this->hasColumn('baoliao_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => false,
             ));
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));

        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('TrdUser as User', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $this->hasOne('TrdItem as Item', array(
             'local' => 'item_id',
             'foreign' => 'id'));

        $this->hasOne('TrdItemAll as ItemAll', array(
             'local' => 'item_all_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}