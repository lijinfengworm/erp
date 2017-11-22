<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdFindClickInfo', 'trade');

/**
 * BaseTrdFindClickInfo
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property bigint $id
 * @property integer $item_id
 * @property string $vid
 * @property datetime $clicktime
 * 
 * @method bigint           getId()        Returns the current record's "id" value
 * @method integer          getItemId()    Returns the current record's "item_id" value
 * @method string           getVid()       Returns the current record's "vid" value
 * @method datetime         getClicktime() Returns the current record's "clicktime" value
 * @method TrdFindClickInfo setId()        Sets the current record's "id" value
 * @method TrdFindClickInfo setItemId()    Sets the current record's "item_id" value
 * @method TrdFindClickInfo setVid()       Sets the current record's "vid" value
 * @method TrdFindClickInfo setClicktime() Sets the current record's "clicktime" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdFindClickInfo extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_find_click_info');
        $this->hasColumn('id', 'bigint', 22, array(
             'type' => 'bigint',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 22,
             ));
        $this->hasColumn('item_id', 'integer', 8, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 8,
             ));
        $this->hasColumn('vid', 'string', 100, array(
             'type' => 'string',
             'default' => '',
             'length' => 100,
             ));
        $this->hasColumn('clicktime', 'datetime', null, array(
             'type' => 'datetime',
             ));


        $this->index('item_id', array(
             'fields' => 
             array(
              0 => 'item_id',
             ),
             ));
        $this->index('clicktime', array(
             'fields' => 
             array(
              0 => 'clicktime',
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