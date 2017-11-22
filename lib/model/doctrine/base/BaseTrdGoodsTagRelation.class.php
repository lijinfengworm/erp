<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdGoodsTagRelation', 'trade');

/**
 * BaseTrdGoodsTagRelation
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $goods_id
 * @property integer $group_id
 * @property integer $tag_id
 * 
 * @method integer             getId()       Returns the current record's "id" value
 * @method integer             getGoodsId()  Returns the current record's "goods_id" value
 * @method integer             getGroupId()  Returns the current record's "group_id" value
 * @method integer             getTagId()    Returns the current record's "tag_id" value
 * @method TrdGoodsTagRelation setId()       Sets the current record's "id" value
 * @method TrdGoodsTagRelation setGoodsId()  Sets the current record's "goods_id" value
 * @method TrdGoodsTagRelation setGroupId()  Sets the current record's "group_id" value
 * @method TrdGoodsTagRelation setTagId()    Sets the current record's "tag_id" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdGoodsTagRelation extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_goods_tag_relation');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('goods_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('group_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('tag_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
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