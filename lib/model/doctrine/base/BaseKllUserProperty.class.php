<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllUserProperty', 'kaluli');

/**
 * BaseKllUserProperty
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $user_id
 * @property string $user_name
 * @property string $mail
 * @property tinyint $status
 * @property tingyint $sex
 * @property integer $province
 * @property integer $city
 * @property integer $profession
 * @property string $info
 * @property integer $register_time
 * @property integer $last_login_time
 * @property integer $pwd_level
 * 
 * @method integer         getUserId()          Returns the current record's "user_id" value
 * @method string          getUserName()        Returns the current record's "user_name" value
 * @method string          getMail()            Returns the current record's "mail" value
 * @method tinyint         getStatus()          Returns the current record's "status" value
 * @method tingyint        getSex()             Returns the current record's "sex" value
 * @method integer         getProvince()        Returns the current record's "province" value
 * @method integer         getCity()            Returns the current record's "city" value
 * @method integer         getProfession()      Returns the current record's "profession" value
 * @method string          getInfo()            Returns the current record's "info" value
 * @method integer         getRegisterTime()    Returns the current record's "register_time" value
 * @method integer         getLastLoginTime()   Returns the current record's "last_login_time" value
 * @method integer         getPwdLevel()        Returns the current record's "pwd_level" value
 * @method KllUserProperty setUserId()          Sets the current record's "user_id" value
 * @method KllUserProperty setUserName()        Sets the current record's "user_name" value
 * @method KllUserProperty setMail()            Sets the current record's "mail" value
 * @method KllUserProperty setStatus()          Sets the current record's "status" value
 * @method KllUserProperty setSex()             Sets the current record's "sex" value
 * @method KllUserProperty setProvince()        Sets the current record's "province" value
 * @method KllUserProperty setCity()            Sets the current record's "city" value
 * @method KllUserProperty setProfession()      Sets the current record's "profession" value
 * @method KllUserProperty setInfo()            Sets the current record's "info" value
 * @method KllUserProperty setRegisterTime()    Sets the current record's "register_time" value
 * @method KllUserProperty setLastLoginTime()   Sets the current record's "last_login_time" value
 * @method KllUserProperty setPwdLevel()        Sets the current record's "pwd_level" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllUserProperty extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_user_property');
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             ));
        $this->hasColumn('user_name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('mail', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('status', 'tinyint', 3, array(
             'type' => 'tinyint',
             'length' => 3,
             ));
        $this->hasColumn('sex', 'tingyint', 2, array(
             'type' => 'tingyint',
             'length' => 2,
             ));
        $this->hasColumn('province', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('city', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('profession', 'integer', 10, array(
             'type' => 'integer',
             'length' => 10,
             ));
        $this->hasColumn('info', 'string', 10000, array(
             'type' => 'string',
             'length' => 10000,
             ));
        $this->hasColumn('register_time', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('last_login_time', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('pwd_level', 'integer', 3, array(
             'type' => 'integer',
             'length' => 3,
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