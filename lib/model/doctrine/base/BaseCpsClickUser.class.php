<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('CpsClickUser', 'tradeCPS');

/**
 * BaseCpsClickUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $cookie
 * @property string $union_id
 * @property string $mid
 * @property string $euid
 * @property string $referer
 * @property string $to
 * @property string $ip
 * @property integer $click_time
 * @property integer $hupu_uid
 * 
 * @method integer      getId()         Returns the current record's "id" value
 * @method string       getCookie()     Returns the current record's "cookie" value
 * @method string       getUnionId()    Returns the current record's "union_id" value
 * @method string       getMid()        Returns the current record's "mid" value
 * @method string       getEuid()       Returns the current record's "euid" value
 * @method string       getReferer()    Returns the current record's "referer" value
 * @method string       getTo()         Returns the current record's "to" value
 * @method string       getIp()         Returns the current record's "ip" value
 * @method integer      getClickTime()  Returns the current record's "click_time" value
 * @method integer      getHupuUid()    Returns the current record's "hupu_uid" value
 * @method CpsClickUser setId()         Sets the current record's "id" value
 * @method CpsClickUser setCookie()     Sets the current record's "cookie" value
 * @method CpsClickUser setUnionId()    Sets the current record's "union_id" value
 * @method CpsClickUser setMid()        Sets the current record's "mid" value
 * @method CpsClickUser setEuid()       Sets the current record's "euid" value
 * @method CpsClickUser setReferer()    Sets the current record's "referer" value
 * @method CpsClickUser setTo()         Sets the current record's "to" value
 * @method CpsClickUser setIp()         Sets the current record's "ip" value
 * @method CpsClickUser setClickTime()  Sets the current record's "click_time" value
 * @method CpsClickUser setHupuUid()    Sets the current record's "hupu_uid" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCpsClickUser extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('cps_click_user');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('cookie', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('union_id', 'string', 30, array(
             'type' => 'string',
             'length' => 30,
             ));
        $this->hasColumn('mid', 'string', 30, array(
             'type' => 'string',
             'length' => 30,
             ));
        $this->hasColumn('euid', 'string', 200, array(
             'type' => 'string',
             'length' => 200,
             ));
        $this->hasColumn('referer', 'string', 512, array(
             'type' => 'string',
             'length' => 512,
             ));
        $this->hasColumn('to', 'string', 512, array(
             'type' => 'string',
             'length' => 512,
             ));
        $this->hasColumn('ip', 'string', 15, array(
             'type' => 'string',
             'length' => 15,
             ));
        $this->hasColumn('click_time', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 10,
             ));
        $this->hasColumn('hupu_uid', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));


        $this->index('cookie', array(
             'fields' => 
             array(
              0 => 'cookie',
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