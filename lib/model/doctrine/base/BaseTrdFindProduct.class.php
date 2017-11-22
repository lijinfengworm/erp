<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdFindProduct', 'trade');

/**
 * BaseTrdFindProduct
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $title
 * @property string $memo
 * @property float $price
 * @property string $tag
 * @property integer $root_id
 * @property integer $children_id
 * @property string $root_name
 * @property string $children_name
 * @property string $attr_collect
 * @property integer $is_showsports
 * @property timestamp $publish_date
 * 
 * @method integer        getId()            Returns the current record's "id" value
 * @method string         getTitle()         Returns the current record's "title" value
 * @method string         getMemo()          Returns the current record's "memo" value
 * @method float          getPrice()         Returns the current record's "price" value
 * @method string         getTag()           Returns the current record's "tag" value
 * @method integer        getRootId()        Returns the current record's "root_id" value
 * @method integer        getChildrenId()    Returns the current record's "children_id" value
 * @method string         getRootName()      Returns the current record's "root_name" value
 * @method string         getChildrenName()  Returns the current record's "children_name" value
 * @method string         getAttrCollect()   Returns the current record's "attr_collect" value
 * @method integer        getIsShowsports()  Returns the current record's "is_showsports" value
 * @method timestamp      getPublishDate()   Returns the current record's "publish_date" value
 * @method TrdFindProduct setId()            Sets the current record's "id" value
 * @method TrdFindProduct setTitle()         Sets the current record's "title" value
 * @method TrdFindProduct setMemo()          Sets the current record's "memo" value
 * @method TrdFindProduct setPrice()         Sets the current record's "price" value
 * @method TrdFindProduct setTag()           Sets the current record's "tag" value
 * @method TrdFindProduct setRootId()        Sets the current record's "root_id" value
 * @method TrdFindProduct setChildrenId()    Sets the current record's "children_id" value
 * @method TrdFindProduct setRootName()      Sets the current record's "root_name" value
 * @method TrdFindProduct setChildrenName()  Sets the current record's "children_name" value
 * @method TrdFindProduct setAttrCollect()   Sets the current record's "attr_collect" value
 * @method TrdFindProduct setIsShowsports()  Sets the current record's "is_showsports" value
 * @method TrdFindProduct setPublishDate()   Sets the current record's "publish_date" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdFindProduct extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_find_product');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('title', 'string', 200, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             'length' => 200,
             ));
        $this->hasColumn('memo', 'string', 500, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             'length' => 500,
             ));
        $this->hasColumn('price', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
             ));
        $this->hasColumn('tag', 'string', 500, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             'length' => 500,
             ));
        $this->hasColumn('root_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('children_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('root_name', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('children_name', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('attr_collect', 'string', 1500, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 1500,
             ));
        $this->hasColumn('is_showsports', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 1,
             'length' => 1,
             ));
        $this->hasColumn('publish_date', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => true,
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