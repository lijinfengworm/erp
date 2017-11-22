<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdGoClickInfo', 'trade');

/**
 * BaseTrdGoClickInfo
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $uid
 * @property string $username
 * @property string $cooick_id
 * @property string $referer
 * @property string $destination
 * 
 * @method integer        getId()          Returns the current record's "id" value
 * @method integer        getUid()         Returns the current record's "uid" value
 * @method string         getUsername()    Returns the current record's "username" value
 * @method string         getCooickId()    Returns the current record's "cooick_id" value
 * @method string         getReferer()     Returns the current record's "referer" value
 * @method string         getDestination() Returns the current record's "destination" value
 * @method TrdGoClickInfo setId()          Sets the current record's "id" value
 * @method TrdGoClickInfo setUid()         Sets the current record's "uid" value
 * @method TrdGoClickInfo setUsername()    Sets the current record's "username" value
 * @method TrdGoClickInfo setCooickId()    Sets the current record's "cooick_id" value
 * @method TrdGoClickInfo setReferer()     Sets the current record's "referer" value
 * @method TrdGoClickInfo setDestination() Sets the current record's "destination" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdGoClickInfo extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_go_click_info');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('uid', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => false,
             ));
        $this->hasColumn('username', 'string', 45, array(
             'type' => 'string',
             'default' => '',
             'length' => 45,
             ));
        $this->hasColumn('cooick_id', 'string', 40, array(
             'type' => 'string',
             'default' => '',
             'length' => 40,
             ));
        $this->hasColumn('referer', 'string', 255, array(
             'type' => 'string',
             'default' => '',
             'length' => 255,
             ));
        $this->hasColumn('destination', 'string', 255, array(
             'type' => 'string',
             'default' => '',
             'length' => 255,
             ));

        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $this->actAs($timestampable0);
        $this->actAs($softdelete0);
    }
}