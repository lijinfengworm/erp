<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllKol', 'kaluli');

/**
 * BaseKllKol
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $user_id
 * @property string $abstract
 * @property string $home_page
 * @property tinyint $channel_id
 * @property tinyint $benefits_id
 * @property string $account
 * @property tinyint $status
 * @property tinyint $commision
 * @property string $remark
 * @property timestamp $ct_time
 * @property string $user_name
 * @property bigint $mobile
 * @property string $nick_name
 * @property string $head_image
 * 
 * @method integer   getId()          Returns the current record's "id" value
 * @method integer   getUserId()      Returns the current record's "user_id" value
 * @method string    getAbstract()    Returns the current record's "abstract" value
 * @method string    getHomePage()    Returns the current record's "home_page" value
 * @method tinyint   getChannelId()   Returns the current record's "channel_id" value
 * @method tinyint   getBenefitsId()  Returns the current record's "benefits_id" value
 * @method string    getAccount()     Returns the current record's "account" value
 * @method tinyint   getStatus()      Returns the current record's "status" value
 * @method tinyint   getCommision()   Returns the current record's "commision" value
 * @method string    getRemark()      Returns the current record's "remark" value
 * @method timestamp getCtTime()      Returns the current record's "ct_time" value
 * @method string    getUserName()    Returns the current record's "user_name" value
 * @method bigint    getMobile()      Returns the current record's "mobile" value
 * @method string    getNickName()    Returns the current record's "nick_name" value
 * @method string    getHeadImage()   Returns the current record's "head_image" value
 * @method KllKol    setId()          Sets the current record's "id" value
 * @method KllKol    setUserId()      Sets the current record's "user_id" value
 * @method KllKol    setAbstract()    Sets the current record's "abstract" value
 * @method KllKol    setHomePage()    Sets the current record's "home_page" value
 * @method KllKol    setChannelId()   Sets the current record's "channel_id" value
 * @method KllKol    setBenefitsId()  Sets the current record's "benefits_id" value
 * @method KllKol    setAccount()     Sets the current record's "account" value
 * @method KllKol    setStatus()      Sets the current record's "status" value
 * @method KllKol    setCommision()   Sets the current record's "commision" value
 * @method KllKol    setRemark()      Sets the current record's "remark" value
 * @method KllKol    setCtTime()      Sets the current record's "ct_time" value
 * @method KllKol    setUserName()    Sets the current record's "user_name" value
 * @method KllKol    setMobile()      Sets the current record's "mobile" value
 * @method KllKol    setNickName()    Sets the current record's "nick_name" value
 * @method KllKol    setHeadImage()   Sets the current record's "head_image" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllKol extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_kol');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('abstract', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('home_page', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('channel_id', 'tinyint', null, array(
             'type' => 'tinyint',
             'unsigned' => true,
             ));
        $this->hasColumn('benefits_id', 'tinyint', null, array(
             'type' => 'tinyint',
             'unsigned' => true,
             ));
        $this->hasColumn('account', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('status', 'tinyint', null, array(
             'type' => 'tinyint',
             'unsigned' => true,
             ));
        $this->hasColumn('commision', 'tinyint', null, array(
             'type' => 'tinyint',
             ));
        $this->hasColumn('remark', 'string', 1000, array(
             'type' => 'string',
             'length' => 1000,
             ));
        $this->hasColumn('ct_time', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('user_name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('mobile', 'bigint', null, array(
             'type' => 'bigint',
             ));
        $this->hasColumn('nick_name', 'string', 30, array(
             'type' => 'string',
             'length' => 30,
             ));
        $this->hasColumn('head_image', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
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