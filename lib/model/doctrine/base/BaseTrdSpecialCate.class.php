<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdSpecialCate', 'kaluli');

/**
 * BaseTrdSpecialCate
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property Doctrine_Collection $TrdSpecialCate
 * 
 * @method integer             getId()             Returns the current record's "id" value
 * @method string              getName()           Returns the current record's "name" value
 * @method Doctrine_Collection getTrdSpecialCate() Returns the current record's "TrdSpecialCate" collection
 * @method TrdSpecialCate      setId()             Sets the current record's "id" value
 * @method TrdSpecialCate      setName()           Sets the current record's "name" value
 * @method TrdSpecialCate      setTrdSpecialCate() Sets the current record's "TrdSpecialCate" collection
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdSpecialCate extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_special_cate');
        $this->hasColumn('id', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 10,
             ));
        $this->hasColumn('name', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));

        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('TrdSpecial as TrdSpecialCate', array(
             'local' => 'id',
             'foreign' => 'cateid'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $this->actAs($timestampable0);
        $this->actAs($softdelete0);
    }
}