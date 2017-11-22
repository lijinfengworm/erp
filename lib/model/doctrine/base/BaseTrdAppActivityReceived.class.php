<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdAppActivityReceived', 'trade');

/**
 * BaseTrdAppActivityReceived
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $activity_id
 * @property integer $detail_id
 * @property string $account
 * @property string $mobile
 * @property datetime $start_time
 * @property datetime $end_time
 * @property integer $hupu_uid
 * @property string $hupu_username
 * @property datetime $received_time
 * 
 * @method integer                getId()            Returns the current record's "id" value
 * @method integer                getActivityId()    Returns the current record's "activity_id" value
 * @method integer                getDetailId()      Returns the current record's "detail_id" value
 * @method string                 getAccount()       Returns the current record's "account" value
 * @method string                 getMobile()        Returns the current record's "mobile" value
 * @method datetime               getStartTime()     Returns the current record's "start_time" value
 * @method datetime               getEndTime()       Returns the current record's "end_time" value
 * @method integer                getHupuUid()       Returns the current record's "hupu_uid" value
 * @method string                 getHupuUsername()  Returns the current record's "hupu_username" value
 * @method datetime               getReceivedTime()  Returns the current record's "received_time" value
 * @method TrdAppActivityReceived setId()            Sets the current record's "id" value
 * @method TrdAppActivityReceived setActivityId()    Sets the current record's "activity_id" value
 * @method TrdAppActivityReceived setDetailId()      Sets the current record's "detail_id" value
 * @method TrdAppActivityReceived setAccount()       Sets the current record's "account" value
 * @method TrdAppActivityReceived setMobile()        Sets the current record's "mobile" value
 * @method TrdAppActivityReceived setStartTime()     Sets the current record's "start_time" value
 * @method TrdAppActivityReceived setEndTime()       Sets the current record's "end_time" value
 * @method TrdAppActivityReceived setHupuUid()       Sets the current record's "hupu_uid" value
 * @method TrdAppActivityReceived setHupuUsername()  Sets the current record's "hupu_username" value
 * @method TrdAppActivityReceived setReceivedTime()  Sets the current record's "received_time" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdAppActivityReceived extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_app_activity_received');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('activity_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             ));
        $this->hasColumn('detail_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             ));
        $this->hasColumn('account', 'string', 30, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 30,
             ));
        $this->hasColumn('mobile', 'string', 16, array(
             'type' => 'string',
             'length' => 16,
             ));
        $this->hasColumn('start_time', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('end_time', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('hupu_uid', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             ));
        $this->hasColumn('hupu_username', 'string', 30, array(
             'type' => 'string',
             'length' => 30,
             ));
        $this->hasColumn('received_time', 'datetime', null, array(
             'type' => 'datetime',
             'notnull' => true,
             ));


        $this->index('activity_id_index', array(
             'fields' => 
             array(
              0 => 'activity_id',
             ),
             ));
        $this->index('hupu_uid_index', array(
             'fields' => 
             array(
              0 => 'hupu_uid',
             ),
             ));
        $this->index('account_index', array(
             'fields' => 
             array(
              0 => 'account',
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