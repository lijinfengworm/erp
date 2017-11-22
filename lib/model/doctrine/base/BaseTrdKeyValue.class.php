<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdKeyValue', 'trade');

/**
 * BaseTrdKeyValue
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $tkey
 * @property string $tvalue
 * 
 * @method integer     getId()     Returns the current record's "id" value
 * @method string      getTkey()   Returns the current record's "tkey" value
 * @method string      getTvalue() Returns the current record's "tvalue" value
 * @method TrdKeyValue setId()     Sets the current record's "id" value
 * @method TrdKeyValue setTkey()   Sets the current record's "tkey" value
 * @method TrdKeyValue setTvalue() Sets the current record's "tvalue" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdKeyValue extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_key_value');
        $this->hasColumn('id', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 10,
             ));
        $this->hasColumn('tkey', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('tvalue', 'string', 20000, array(
             'type' => 'string',
             'length' => 20000,
             ));


        $this->index('tkey', array(
             'fields' => 
             array(
              0 => 'tkey',
             ),
             ));
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}