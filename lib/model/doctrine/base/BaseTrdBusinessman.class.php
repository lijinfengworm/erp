<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdBusinessman', 'trade');

/**
 * BaseTrdBusinessman
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $username
 * @property integer $hupu_uid
 * @property string $hupu_username
 * @property integer $phone
 * @property string $email
 * @property string $qq
 * @property string $shop_url
 * @property string $shop_name
 * @property string $wanwan
 * @property integer $alliance
 * @property string $alliance_trdno
 * 
 * @method integer        getId()             Returns the current record's "id" value
 * @method string         getUsername()       Returns the current record's "username" value
 * @method integer        getHupuUid()        Returns the current record's "hupu_uid" value
 * @method string         getHupuUsername()   Returns the current record's "hupu_username" value
 * @method integer        getPhone()          Returns the current record's "phone" value
 * @method string         getEmail()          Returns the current record's "email" value
 * @method string         getQq()             Returns the current record's "qq" value
 * @method string         getShopUrl()        Returns the current record's "shop_url" value
 * @method string         getShopName()       Returns the current record's "shop_name" value
 * @method string         getWanwan()         Returns the current record's "wanwan" value
 * @method integer        getAlliance()       Returns the current record's "alliance" value
 * @method string         getAllianceTrdno()  Returns the current record's "alliance_trdno" value
 * @method TrdBusinessman setId()             Sets the current record's "id" value
 * @method TrdBusinessman setUsername()       Sets the current record's "username" value
 * @method TrdBusinessman setHupuUid()        Sets the current record's "hupu_uid" value
 * @method TrdBusinessman setHupuUsername()   Sets the current record's "hupu_username" value
 * @method TrdBusinessman setPhone()          Sets the current record's "phone" value
 * @method TrdBusinessman setEmail()          Sets the current record's "email" value
 * @method TrdBusinessman setQq()             Sets the current record's "qq" value
 * @method TrdBusinessman setShopUrl()        Sets the current record's "shop_url" value
 * @method TrdBusinessman setShopName()       Sets the current record's "shop_name" value
 * @method TrdBusinessman setWanwan()         Sets the current record's "wanwan" value
 * @method TrdBusinessman setAlliance()       Sets the current record's "alliance" value
 * @method TrdBusinessman setAllianceTrdno()  Sets the current record's "alliance_trdno" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdBusinessman extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_bussinessman');
        $this->hasColumn('id', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 10,
             ));
        $this->hasColumn('username', 'string', 30, array(
             'type' => 'string',
             'length' => 30,
             ));
        $this->hasColumn('hupu_uid', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('hupu_username', 'string', 30, array(
             'type' => 'string',
             'length' => 30,
             ));
        $this->hasColumn('phone', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('email', 'string', 30, array(
             'type' => 'string',
             'length' => 30,
             ));
        $this->hasColumn('qq', 'string', 20, array(
             'type' => 'string',
             'length' => 20,
             ));
        $this->hasColumn('shop_url', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('shop_name', 'string', 20, array(
             'type' => 'string',
             'length' => 20,
             ));
        $this->hasColumn('wanwan', 'string', 20, array(
             'type' => 'string',
             'length' => 20,
             ));
        $this->hasColumn('alliance', 'integer', 1, array(
             'type' => 'integer',
             'length' => 1,
             ));
        $this->hasColumn('alliance_trdno', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));


        $this->index('hupu_uid', array(
             'fields' => 
             array(
              0 => 'hupu_uid',
             ),
             ));
        $this->index('hupu_username', array(
             'fields' => 
             array(
              0 => 'hupu_username',
             ),
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