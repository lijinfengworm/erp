<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllCardWare', 'kaluli');

/**
 * BaseKllCardWare
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $code
 * @property string $title
 * @property integer $hupu_uid
 * @property string $hupu_username
 * @property integer $status
 * @property integer $stime
 * @property integer $etime
 * @property string $phone
 * @property integer $is_delete
 * 
 * @method integer     getId()            Returns the current record's "id" value
 * @method string      getCode()          Returns the current record's "code" value
 * @method string      getTitle()         Returns the current record's "title" value
 * @method integer     getHupuUid()       Returns the current record's "hupu_uid" value
 * @method string      getHupuUsername()  Returns the current record's "hupu_username" value
 * @method integer     getStatus()        Returns the current record's "status" value
 * @method integer     getStime()         Returns the current record's "stime" value
 * @method integer     getEtime()         Returns the current record's "etime" value
 * @method string      getPhone()         Returns the current record's "phone" value
 * @method integer     getIsDelete()      Returns the current record's "is_delete" value
 * @method KllCardWare setId()            Sets the current record's "id" value
 * @method KllCardWare setCode()          Sets the current record's "code" value
 * @method KllCardWare setTitle()         Sets the current record's "title" value
 * @method KllCardWare setHupuUid()       Sets the current record's "hupu_uid" value
 * @method KllCardWare setHupuUsername()  Sets the current record's "hupu_username" value
 * @method KllCardWare setStatus()        Sets the current record's "status" value
 * @method KllCardWare setStime()         Sets the current record's "stime" value
 * @method KllCardWare setEtime()         Sets the current record's "etime" value
 * @method KllCardWare setPhone()         Sets the current record's "phone" value
 * @method KllCardWare setIsDelete()      Sets the current record's "is_delete" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllCardWare extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_cardware');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => 8,
             ));
        $this->hasColumn('code', 'string', 128, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             'length' => 128,
             ));
        $this->hasColumn('title', 'string', 255, array(
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
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('stime', 'integer', 8, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'default' => 0,
             'length' => 8,
             ));
        $this->hasColumn('etime', 'integer', 8, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'default' => 0,
             'length' => 8,
             ));
        $this->hasColumn('phone', 'string', 32, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             'length' => 32,
             ));
        $this->hasColumn('is_delete', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'default' => 0,
             'length' => 1,
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