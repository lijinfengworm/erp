<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdAppActivityRemind', 'trade');

/**
 * BaseTrdAppActivityRemind
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $activity_id
 * @property string $mobile
 * @property boolean $status
 * @property string $title
 * @property datetime $start_time
 * @property datetime $created_time
 * 
 * @method integer              getId()           Returns the current record's "id" value
 * @method integer              getActivityId()   Returns the current record's "activity_id" value
 * @method string               getMobile()       Returns the current record's "mobile" value
 * @method boolean              getStatus()       Returns the current record's "status" value
 * @method string               getTitle()        Returns the current record's "title" value
 * @method datetime             getStartTime()    Returns the current record's "start_time" value
 * @method datetime             getCreatedTime()  Returns the current record's "created_time" value
 * @method TrdAppActivityRemind setId()           Sets the current record's "id" value
 * @method TrdAppActivityRemind setActivityId()   Sets the current record's "activity_id" value
 * @method TrdAppActivityRemind setMobile()       Sets the current record's "mobile" value
 * @method TrdAppActivityRemind setStatus()       Sets the current record's "status" value
 * @method TrdAppActivityRemind setTitle()        Sets the current record's "title" value
 * @method TrdAppActivityRemind setStartTime()    Sets the current record's "start_time" value
 * @method TrdAppActivityRemind setCreatedTime()  Sets the current record's "created_time" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdAppActivityRemind extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_app_activity_remind');
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
        $this->hasColumn('mobile', 'string', 16, array(
             'type' => 'string',
             'length' => 16,
             ));
        $this->hasColumn('status', 'boolean', null, array(
             'type' => 'boolean',
             'default' => '0',
             ));
        $this->hasColumn('title', 'string', 200, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 200,
             ));
        $this->hasColumn('start_time', 'datetime', null, array(
             'type' => 'datetime',
             'notnull' => true,
             ));
        $this->hasColumn('created_time', 'datetime', null, array(
             'type' => 'datetime',
             'notnull' => true,
             ));


        $this->index('activity_id_index', array(
             'fields' => 
             array(
              0 => 'activity_id',
             ),
             ));
        $this->index('status_index', array(
             'fields' => 
             array(
              0 => 'status',
             ),
             ));
        $this->index('start_time_index', array(
             'fields' => 
             array(
              0 => 'start_time',
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