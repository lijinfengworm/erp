<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdUserReport', 'trade');

/**
 * BaseTrdUserReport
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $item_id
 * @property integer $user_id
 * 
 * @method integer       getId()      Returns the current record's "id" value
 * @method integer       getItemId()  Returns the current record's "item_id" value
 * @method integer       getUserId()  Returns the current record's "user_id" value
 * @method TrdUserReport setId()      Sets the current record's "id" value
 * @method TrdUserReport setItemId()  Sets the current record's "item_id" value
 * @method TrdUserReport setUserId()  Sets the current record's "user_id" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdUserReport extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_user_report');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('item_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
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