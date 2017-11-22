<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdGroup', 'trade');

/**
 * BaseTrdGroup
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property integer $sort
 * @property integer $type
 * @property integer $flag
 * @property integer $menu_id
 * @property string $attr
 * @property integer $usage
 * @property Doctrine_Collection $trd_attr_group
 * 
 * @method integer             getId()             Returns the current record's "id" value
 * @method string              getName()           Returns the current record's "name" value
 * @method integer             getSort()           Returns the current record's "sort" value
 * @method integer             getType()           Returns the current record's "type" value
 * @method integer             getFlag()           Returns the current record's "flag" value
 * @method integer             getMenuId()         Returns the current record's "menu_id" value
 * @method string              getAttr()           Returns the current record's "attr" value
 * @method integer             getUsage()          Returns the current record's "usage" value
 * @method Doctrine_Collection getTrdAttrGroup()   Returns the current record's "trd_attr_group" collection
 * @method TrdGroup            setId()             Sets the current record's "id" value
 * @method TrdGroup            setName()           Sets the current record's "name" value
 * @method TrdGroup            setSort()           Sets the current record's "sort" value
 * @method TrdGroup            setType()           Sets the current record's "type" value
 * @method TrdGroup            setFlag()           Sets the current record's "flag" value
 * @method TrdGroup            setMenuId()         Sets the current record's "menu_id" value
 * @method TrdGroup            setAttr()           Sets the current record's "attr" value
 * @method TrdGroup            setUsage()          Sets the current record's "usage" value
 * @method TrdGroup            setTrdAttrGroup()   Sets the current record's "trd_attr_group" collection
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdGroup extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_group');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('name', 'string', 30, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 30,
             ));
        $this->hasColumn('sort', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 1,
             ));
        $this->hasColumn('type', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('flag', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('menu_id', 'integer', 3, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 3,
             ));
        $this->hasColumn('attr', 'string', 5000, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             'length' => 5000,
             ));
        $this->hasColumn('usage', 'integer', 1, array(
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
        $this->hasMany('TrdAttrGroup as trd_attr_group', array(
             'local' => 'id',
             'foreign' => 'trd_group_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}