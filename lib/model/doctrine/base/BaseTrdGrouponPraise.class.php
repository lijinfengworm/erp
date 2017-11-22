<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdGrouponPraise', 'trade');

/**
 * BaseTrdGrouponPraise
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $client_id
 * @property string $client_str
 * @property integer $type
 * @property integer $groupon_id
 * @property datetime $create_time
 * @property integer $is_delete
 * 
 * @method integer          getId()          Returns the current record's "id" value
 * @method integer          getClientId()    Returns the current record's "client_id" value
 * @method string           getClientStr()   Returns the current record's "client_str" value
 * @method integer          getType()        Returns the current record's "type" value
 * @method integer          getGrouponId()   Returns the current record's "groupon_id" value
 * @method datetime         getCreateTime()  Returns the current record's "create_time" value
 * @method integer          getIsDelete()    Returns the current record's "is_delete" value
 * @method TrdGrouponPraise setId()          Sets the current record's "id" value
 * @method TrdGrouponPraise setClientId()    Sets the current record's "client_id" value
 * @method TrdGrouponPraise setClientStr()   Sets the current record's "client_str" value
 * @method TrdGrouponPraise setType()        Sets the current record's "type" value
 * @method TrdGrouponPraise setGrouponId()   Sets the current record's "groupon_id" value
 * @method TrdGrouponPraise setCreateTime()  Sets the current record's "create_time" value
 * @method TrdGrouponPraise setIsDelete()    Sets the current record's "is_delete" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdGrouponPraise extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_groupon_praise');
        $this->hasColumn('id', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 10,
             ));
        $this->hasColumn('client_id', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 10,
             ));
        $this->hasColumn('client_str', 'string', 128, array(
             'type' => 'string',
             'length' => 128,
             ));
        $this->hasColumn('type', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('groupon_id', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('create_time', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('is_delete', 'integer', 1, array(
             'type' => 'integer',
             'default' => 0,
             'length' => 1,
             ));


        $this->index('client_id', array(
             'fields' => 
             array(
              0 => 'client_id',
             ),
             ));
        $this->index('client_str', array(
             'fields' => 
             array(
              0 => 'client_str',
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