<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('kllLipinkaLog', 'kaluli');

/**
 * BasekllLipinkaLog
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $action_id
 * @property integer $table_id
 * @property string $message
 * @property integer $hupu_uid
 * @property string $hupu_username
 * 
 * @method integer       getId()            Returns the current record's "id" value
 * @method integer       getActionId()      Returns the current record's "action_id" value
 * @method integer       getTableId()       Returns the current record's "table_id" value
 * @method string        getMessage()       Returns the current record's "message" value
 * @method integer       getHupuUid()       Returns the current record's "hupu_uid" value
 * @method string        getHupuUsername()  Returns the current record's "hupu_username" value
 * @method kllLipinkaLog setId()            Sets the current record's "id" value
 * @method kllLipinkaLog setActionId()      Sets the current record's "action_id" value
 * @method kllLipinkaLog setTableId()       Sets the current record's "table_id" value
 * @method kllLipinkaLog setMessage()       Sets the current record's "message" value
 * @method kllLipinkaLog setHupuUid()       Sets the current record's "hupu_uid" value
 * @method kllLipinkaLog setHupuUsername()  Sets the current record's "hupu_username" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasekllLipinkaLog extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_lipinka_log');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => 8,
             ));
        $this->hasColumn('action_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'default' => 0,
             'length' => 11,
             ));
        $this->hasColumn('table_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'default' => 0,
             'length' => 11,
             ));
        $this->hasColumn('message', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             'length' => 255,
             ));
        $this->hasColumn('hupu_uid', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('hupu_username', 'string', 30, array(
             'type' => 'string',
             'length' => 30,
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