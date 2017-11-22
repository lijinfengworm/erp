<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllKolChannel', 'kaluli');

/**
 * BaseKllKolChannel
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $title
 * @property string $abstract
 * @property string $channel_code
 * @property integer $times
 * @property tinyint $range
 * @property tinyint $discount
 * @property float $toplimit
 * @property integer $start_time
 * @property integer $end_time
 * @property tinyint $commision
 * @property tinyint $status
 * 
 * @method integer       getId()           Returns the current record's "id" value
 * @method string        getTitle()        Returns the current record's "title" value
 * @method string        getAbstract()     Returns the current record's "abstract" value
 * @method string        getChannelCode()  Returns the current record's "channel_code" value
 * @method integer       getTimes()        Returns the current record's "times" value
 * @method tinyint       getRange()        Returns the current record's "range" value
 * @method tinyint       getDiscount()     Returns the current record's "discount" value
 * @method float         getToplimit()     Returns the current record's "toplimit" value
 * @method integer       getStartTime()    Returns the current record's "start_time" value
 * @method integer       getEndTime()      Returns the current record's "end_time" value
 * @method tinyint       getCommision()    Returns the current record's "commision" value
 * @method tinyint       getStatus()       Returns the current record's "status" value
 * @method KllKolChannel setId()           Sets the current record's "id" value
 * @method KllKolChannel setTitle()        Sets the current record's "title" value
 * @method KllKolChannel setAbstract()     Sets the current record's "abstract" value
 * @method KllKolChannel setChannelCode()  Sets the current record's "channel_code" value
 * @method KllKolChannel setTimes()        Sets the current record's "times" value
 * @method KllKolChannel setRange()        Sets the current record's "range" value
 * @method KllKolChannel setDiscount()     Sets the current record's "discount" value
 * @method KllKolChannel setToplimit()     Sets the current record's "toplimit" value
 * @method KllKolChannel setStartTime()    Sets the current record's "start_time" value
 * @method KllKolChannel setEndTime()      Sets the current record's "end_time" value
 * @method KllKolChannel setCommision()    Sets the current record's "commision" value
 * @method KllKolChannel setStatus()       Sets the current record's "status" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllKolChannel extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_kol_channel');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('abstract', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('channel_code', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('times', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('range', 'tinyint', 3, array(
             'type' => 'tinyint',
             'length' => 3,
             ));
        $this->hasColumn('discount', 'tinyint', 3, array(
             'type' => 'tinyint',
             'length' => 3,
             ));
        $this->hasColumn('toplimit', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
             ));
        $this->hasColumn('start_time', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('end_time', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('commision', 'tinyint', null, array(
             'type' => 'tinyint',
             ));
        $this->hasColumn('status', 'tinyint', null, array(
             'type' => 'tinyint',
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