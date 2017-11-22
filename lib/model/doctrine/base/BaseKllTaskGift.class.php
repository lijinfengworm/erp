<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllTaskGift', 'kaluli');

/**
 * BaseKllTaskGift
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $user_id
 * @property tinyint $type
 * @property tinyint $task
 * @property timestamp $ct_time
 * 
 * @method integer     getId()      Returns the current record's "id" value
 * @method integer     getUserId()  Returns the current record's "user_id" value
 * @method tinyint     getType()    Returns the current record's "type" value
 * @method tinyint     getTask()    Returns the current record's "task" value
 * @method timestamp   getCtTime()  Returns the current record's "ct_time" value
 * @method KllTaskGift setId()      Sets the current record's "id" value
 * @method KllTaskGift setUserId()  Sets the current record's "user_id" value
 * @method KllTaskGift setType()    Sets the current record's "type" value
 * @method KllTaskGift setTask()    Sets the current record's "task" value
 * @method KllTaskGift setCtTime()  Sets the current record's "ct_time" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllTaskGift extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_task_gift');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('type', 'tinyint', null, array(
             'type' => 'tinyint',
             ));
        $this->hasColumn('task', 'tinyint', null, array(
             'type' => 'tinyint',
             ));
        $this->hasColumn('ct_time', 'timestamp', null, array(
             'type' => 'timestamp',
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