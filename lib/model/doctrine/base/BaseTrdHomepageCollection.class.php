<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdHomepageCollection', 'trade');

/**
 * BaseTrdHomepageCollection
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $title
 * @property string $logo
 * @property string $link
 * @property integer $status
 * 
 * @method integer               getId()     Returns the current record's "id" value
 * @method string                getTitle()  Returns the current record's "title" value
 * @method string                getLogo()   Returns the current record's "logo" value
 * @method string                getLink()   Returns the current record's "link" value
 * @method integer               getStatus() Returns the current record's "status" value
 * @method TrdHomepageCollection setId()     Sets the current record's "id" value
 * @method TrdHomepageCollection setTitle()  Sets the current record's "title" value
 * @method TrdHomepageCollection setLogo()   Sets the current record's "logo" value
 * @method TrdHomepageCollection setLink()   Sets the current record's "link" value
 * @method TrdHomepageCollection setStatus() Sets the current record's "status" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdHomepageCollection extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_homepage_collection');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('title', 'string', 50, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 50,
             ));
        $this->hasColumn('logo', 'string', 500, array(
             'type' => 'string',
             'length' => 500,
             ));
        $this->hasColumn('link', 'string', 500, array(
             'type' => 'string',
             'length' => 500,
             ));
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
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