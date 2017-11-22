<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdNotices', 'trade');

/**
 * BaseTrdNotices
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $type
 * @property integer $uid
 * @property integer $sender_uid
 * @property integer $time
 * 
 * @method integer    getId()         Returns the current record's "id" value
 * @method integer    getType()       Returns the current record's "type" value
 * @method integer    getUid()        Returns the current record's "uid" value
 * @method integer    getSenderUid()  Returns the current record's "sender_uid" value
 * @method integer    getTime()       Returns the current record's "time" value
 * @method TrdNotices setId()         Sets the current record's "id" value
 * @method TrdNotices setType()       Sets the current record's "type" value
 * @method TrdNotices setUid()        Sets the current record's "uid" value
 * @method TrdNotices setSenderUid()  Sets the current record's "sender_uid" value
 * @method TrdNotices setTime()       Sets the current record's "time" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdNotices extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_notices');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('type', 'integer', 2, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 2,
             ));
        $this->hasColumn('uid', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('sender_uid', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('time', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));


        $this->index('uid_type', array(
             'fields' => 
             array(
              0 => 'uid',
              1 => 'type',
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