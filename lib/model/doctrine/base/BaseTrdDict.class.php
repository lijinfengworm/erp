<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdDict', 'trade');

/**
 * BaseTrdDict
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $trans_from
 * @property string $trans_to
 * 
 * @method integer getId()         Returns the current record's "id" value
 * @method string  getTransFrom()  Returns the current record's "trans_from" value
 * @method string  getTransTo()    Returns the current record's "trans_to" value
 * @method TrdDict setId()         Sets the current record's "id" value
 * @method TrdDict setTransFrom()  Sets the current record's "trans_from" value
 * @method TrdDict setTransTo()    Sets the current record's "trans_to" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdDict extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_dict');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('trans_from', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('trans_to', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
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