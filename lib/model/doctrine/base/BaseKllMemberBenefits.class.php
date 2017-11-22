<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllMemberBenefits', 'kaluli');

/**
 * BaseKllMemberBenefits
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $code
 * @property string $title
 * @property string $abstract
 * @property string $link
 * @property integer $times
 * @property tinyint $type
 * @property tinyint $range
 * @property tinyint $discount
 * @property float $toplimit
 * @property integer $start_time
 * @property integer $end_time
 * @property tinyint $status
 * 
 * @method integer           getId()         Returns the current record's "id" value
 * @method string            getCode()       Returns the current record's "code" value
 * @method string            getTitle()      Returns the current record's "title" value
 * @method string            getAbstract()   Returns the current record's "abstract" value
 * @method string            getLink()       Returns the current record's "link" value
 * @method integer           getTimes()      Returns the current record's "times" value
 * @method tinyint           getType()       Returns the current record's "type" value
 * @method tinyint           getRange()      Returns the current record's "range" value
 * @method tinyint           getDiscount()   Returns the current record's "discount" value
 * @method float             getToplimit()   Returns the current record's "toplimit" value
 * @method integer           getStartTime()  Returns the current record's "start_time" value
 * @method integer           getEndTime()    Returns the current record's "end_time" value
 * @method tinyint           getStatus()     Returns the current record's "status" value
 * @method KllMemberBenefits setId()         Sets the current record's "id" value
 * @method KllMemberBenefits setCode()       Sets the current record's "code" value
 * @method KllMemberBenefits setTitle()      Sets the current record's "title" value
 * @method KllMemberBenefits setAbstract()   Sets the current record's "abstract" value
 * @method KllMemberBenefits setLink()       Sets the current record's "link" value
 * @method KllMemberBenefits setTimes()      Sets the current record's "times" value
 * @method KllMemberBenefits setType()       Sets the current record's "type" value
 * @method KllMemberBenefits setRange()      Sets the current record's "range" value
 * @method KllMemberBenefits setDiscount()   Sets the current record's "discount" value
 * @method KllMemberBenefits setToplimit()   Sets the current record's "toplimit" value
 * @method KllMemberBenefits setStartTime()  Sets the current record's "start_time" value
 * @method KllMemberBenefits setEndTime()    Sets the current record's "end_time" value
 * @method KllMemberBenefits setStatus()     Sets the current record's "status" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllMemberBenefits extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_member_benefits');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('code', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('abstract', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('link', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('times', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('type', 'tinyint', 3, array(
             'type' => 'tinyint',
             'length' => 3,
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