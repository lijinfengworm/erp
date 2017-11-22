<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdMarketingActivityGroup', 'trade');

/**
 * BaseTrdMarketingActivityGroup
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $activity_id
 * @property integer $item_id
 * @property integer $stime
 * @property integer $etime
 * @property integer $version
 * 
 * @method integer                   getId()          Returns the current record's "id" value
 * @method integer                   getActivityId()  Returns the current record's "activity_id" value
 * @method integer                   getItemId()      Returns the current record's "item_id" value
 * @method integer                   getStime()       Returns the current record's "stime" value
 * @method integer                   getEtime()       Returns the current record's "etime" value
 * @method integer                   getVersion()     Returns the current record's "version" value
 * @method TrdMarketingActivityGroup setId()          Sets the current record's "id" value
 * @method TrdMarketingActivityGroup setActivityId()  Sets the current record's "activity_id" value
 * @method TrdMarketingActivityGroup setItemId()      Sets the current record's "item_id" value
 * @method TrdMarketingActivityGroup setStime()       Sets the current record's "stime" value
 * @method TrdMarketingActivityGroup setEtime()       Sets the current record's "etime" value
 * @method TrdMarketingActivityGroup setVersion()     Sets the current record's "version" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdMarketingActivityGroup extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_marketing_activity_group');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('activity_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('item_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('stime', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('etime', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('version', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));


        $this->index('idx_item_id', array(
             'fields' => 
             array(
              0 => 'item_id',
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