<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('kllOrderBirdex', 'kaluli');

/**
 * BasekllOrderBirdex
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $zt
 * @property integer $send_birdex
 * @property string $ibilling_number
 * @property string $order_number
 * @property timestamp $update_time
 * @property timestamp $create_time
 * @property timestamp $send_birdex_date
 * 
 * @method integer        getId()               Returns the current record's "id" value
 * @method integer        getZt()               Returns the current record's "zt" value
 * @method integer        getSendBirdex()       Returns the current record's "send_birdex" value
 * @method string         getIbillingNumber()   Returns the current record's "ibilling_number" value
 * @method string         getOrderNumber()      Returns the current record's "order_number" value
 * @method timestamp      getUpdateTime()       Returns the current record's "update_time" value
 * @method timestamp      getCreateTime()       Returns the current record's "create_time" value
 * @method timestamp      getSendBirdexDate()   Returns the current record's "send_birdex_date" value
 * @method kllOrderBirdex setId()               Sets the current record's "id" value
 * @method kllOrderBirdex setZt()               Sets the current record's "zt" value
 * @method kllOrderBirdex setSendBirdex()       Sets the current record's "send_birdex" value
 * @method kllOrderBirdex setIbillingNumber()   Sets the current record's "ibilling_number" value
 * @method kllOrderBirdex setOrderNumber()      Sets the current record's "order_number" value
 * @method kllOrderBirdex setUpdateTime()       Sets the current record's "update_time" value
 * @method kllOrderBirdex setCreateTime()       Sets the current record's "create_time" value
 * @method kllOrderBirdex setSendBirdexDate()   Sets the current record's "send_birdex_date" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasekllOrderBirdex extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_order_birdex');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('zt', 'integer', 10, array(
             'type' => 'integer',
             'length' => 10,
             ));
        $this->hasColumn('send_birdex', 'integer', 10, array(
             'type' => 'integer',
             'length' => 10,
             ));
        $this->hasColumn('ibilling_number', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('order_number', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('update_time', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('create_time', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('send_birdex_date', 'timestamp', null, array(
             'type' => 'timestamp',
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