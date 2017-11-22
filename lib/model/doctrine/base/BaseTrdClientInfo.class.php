<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdClientInfo', 'trade');

/**
 * BaseTrdClientInfo
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $user_id
 * @property string $client_str
 * @property string $client_token
 * @property string $wpclient_str
 * @property string $wp_url
 * @property integer $first_virst
 * @property integer $last_virst
 * @property integer $type
 * @property integer $ios_type
 * @property integer $push_switch
 * @property integer $status
 * 
 * @method integer       getId()           Returns the current record's "id" value
 * @method integer       getUserId()       Returns the current record's "user_id" value
 * @method string        getClientStr()    Returns the current record's "client_str" value
 * @method string        getClientToken()  Returns the current record's "client_token" value
 * @method string        getWpclientStr()  Returns the current record's "wpclient_str" value
 * @method string        getWpUrl()        Returns the current record's "wp_url" value
 * @method integer       getFirstVirst()   Returns the current record's "first_virst" value
 * @method integer       getLastVirst()    Returns the current record's "last_virst" value
 * @method integer       getType()         Returns the current record's "type" value
 * @method integer       getIosType()      Returns the current record's "ios_type" value
 * @method integer       getPushSwitch()   Returns the current record's "push_switch" value
 * @method integer       getStatus()       Returns the current record's "status" value
 * @method TrdClientInfo setId()           Sets the current record's "id" value
 * @method TrdClientInfo setUserId()       Sets the current record's "user_id" value
 * @method TrdClientInfo setClientStr()    Sets the current record's "client_str" value
 * @method TrdClientInfo setClientToken()  Sets the current record's "client_token" value
 * @method TrdClientInfo setWpclientStr()  Sets the current record's "wpclient_str" value
 * @method TrdClientInfo setWpUrl()        Sets the current record's "wp_url" value
 * @method TrdClientInfo setFirstVirst()   Sets the current record's "first_virst" value
 * @method TrdClientInfo setLastVirst()    Sets the current record's "last_virst" value
 * @method TrdClientInfo setType()         Sets the current record's "type" value
 * @method TrdClientInfo setIosType()      Sets the current record's "ios_type" value
 * @method TrdClientInfo setPushSwitch()   Sets the current record's "push_switch" value
 * @method TrdClientInfo setStatus()       Sets the current record's "status" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdClientInfo extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_client_info');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('client_str', 'string', 128, array(
             'type' => 'string',
             'length' => 128,
             ));
        $this->hasColumn('client_token', 'string', 128, array(
             'type' => 'string',
             'length' => 128,
             ));
        $this->hasColumn('wpclient_str', 'string', 128, array(
             'type' => 'string',
             'length' => 128,
             ));
        $this->hasColumn('wp_url', 'string', 500, array(
             'type' => 'string',
             'length' => 500,
             ));
        $this->hasColumn('first_virst', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 11,
             ));
        $this->hasColumn('last_virst', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 11,
             ));
        $this->hasColumn('type', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('ios_type', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 1,
             'length' => 1,
             ));
        $this->hasColumn('push_switch', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
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