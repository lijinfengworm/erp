<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllCpsUser', 'kaluliCPS');

/**
 * BaseKllCpsUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $union_id
 * @property integer $hupu_uid
 * @property string $hupu_username
 * @property integer $type
 * @property integer $status
 * 
 * @method integer    getId()            Returns the current record's "id" value
 * @method string     getUnionId()       Returns the current record's "union_id" value
 * @method integer    getHupuUid()       Returns the current record's "hupu_uid" value
 * @method string     getHupuUsername()  Returns the current record's "hupu_username" value
 * @method integer    getType()          Returns the current record's "type" value
 * @method integer    getStatus()        Returns the current record's "status" value
 * @method KllCpsUser setId()            Sets the current record's "id" value
 * @method KllCpsUser setUnionId()       Sets the current record's "union_id" value
 * @method KllCpsUser setHupuUid()       Sets the current record's "hupu_uid" value
 * @method KllCpsUser setHupuUsername()  Sets the current record's "hupu_username" value
 * @method KllCpsUser setType()          Sets the current record's "type" value
 * @method KllCpsUser setStatus()        Sets the current record's "status" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllCpsUser extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_cps_user');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('union_id', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('hupu_uid', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('hupu_username', 'string', 30, array(
             'type' => 'string',
             'length' => 30,
             ));
        $this->hasColumn('type', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 1,
             ));
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 1,
             ));


        $this->index('union_id', array(
             'fields' => 
             array(
              0 => 'union_id',
             ),
             'type' => 'unique',
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