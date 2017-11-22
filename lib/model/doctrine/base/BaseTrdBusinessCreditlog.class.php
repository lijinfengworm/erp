<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdBusinessCreditlog', 'trade');

/**
 * BaseTrdBusinessCreditlog
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $uid
 * @property integer $admin_id
 * @property integer $type
 * @property float $num
 * @property string $note
 * @property integer $date
 * 
 * @method integer              getId()       Returns the current record's "id" value
 * @method integer              getUid()      Returns the current record's "uid" value
 * @method integer              getAdminId()  Returns the current record's "admin_id" value
 * @method integer              getType()     Returns the current record's "type" value
 * @method float                getNum()      Returns the current record's "num" value
 * @method string               getNote()     Returns the current record's "note" value
 * @method integer              getDate()     Returns the current record's "date" value
 * @method TrdBusinessCreditlog setId()       Sets the current record's "id" value
 * @method TrdBusinessCreditlog setUid()      Sets the current record's "uid" value
 * @method TrdBusinessCreditlog setAdminId()  Sets the current record's "admin_id" value
 * @method TrdBusinessCreditlog setType()     Sets the current record's "type" value
 * @method TrdBusinessCreditlog setNum()      Sets the current record's "num" value
 * @method TrdBusinessCreditlog setNote()     Sets the current record's "note" value
 * @method TrdBusinessCreditlog setDate()     Sets the current record's "date" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdBusinessCreditlog extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_business_creditlog');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('uid', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('admin_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('type', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 1,
             ));
        $this->hasColumn('num', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
             ));
        $this->hasColumn('note', 'string', 100, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 100,
             ));
        $this->hasColumn('date', 'integer', null, array(
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