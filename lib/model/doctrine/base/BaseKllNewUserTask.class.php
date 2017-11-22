<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllNewUserTask', 'kaluli');

/**
 * BaseKllNewUserTask
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $user_id
 * @property integer $invitor
 * @property tinyint $section
 * @property timestamp $ct_time
 * @property text $content
 * @property bigint $order_number
 * 
 * @method integer        getId()           Returns the current record's "id" value
 * @method integer        getUserId()       Returns the current record's "user_id" value
 * @method integer        getInvitor()      Returns the current record's "invitor" value
 * @method tinyint        getSection()      Returns the current record's "section" value
 * @method timestamp      getCtTime()       Returns the current record's "ct_time" value
 * @method text           getContent()      Returns the current record's "content" value
 * @method bigint         getOrderNumber()  Returns the current record's "order_number" value
 * @method KllNewUserTask setId()           Sets the current record's "id" value
 * @method KllNewUserTask setUserId()       Sets the current record's "user_id" value
 * @method KllNewUserTask setInvitor()      Sets the current record's "invitor" value
 * @method KllNewUserTask setSection()      Sets the current record's "section" value
 * @method KllNewUserTask setCtTime()       Sets the current record's "ct_time" value
 * @method KllNewUserTask setContent()      Sets the current record's "content" value
 * @method KllNewUserTask setOrderNumber()  Sets the current record's "order_number" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllNewUserTask extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_new_user_task');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('invitor', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('section', 'tinyint', null, array(
             'type' => 'tinyint',
             ));
        $this->hasColumn('ct_time', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('content', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('order_number', 'bigint', null, array(
             'type' => 'bigint',
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