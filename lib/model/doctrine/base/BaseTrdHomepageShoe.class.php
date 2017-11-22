<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdHomepageShoe', 'trade');

/**
 * BaseTrdHomepageShoe
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $title
 * @property float $price
 * @property string $logo1
 * @property string $logo2
 * @property string $link
 * @property integer $status
 * 
 * @method integer         getId()     Returns the current record's "id" value
 * @method string          getTitle()  Returns the current record's "title" value
 * @method float           getPrice()  Returns the current record's "price" value
 * @method string          getLogo1()  Returns the current record's "logo1" value
 * @method string          getLogo2()  Returns the current record's "logo2" value
 * @method string          getLink()   Returns the current record's "link" value
 * @method integer         getStatus() Returns the current record's "status" value
 * @method TrdHomepageShoe setId()     Sets the current record's "id" value
 * @method TrdHomepageShoe setTitle()  Sets the current record's "title" value
 * @method TrdHomepageShoe setPrice()  Sets the current record's "price" value
 * @method TrdHomepageShoe setLogo1()  Sets the current record's "logo1" value
 * @method TrdHomepageShoe setLogo2()  Sets the current record's "logo2" value
 * @method TrdHomepageShoe setLink()   Sets the current record's "link" value
 * @method TrdHomepageShoe setStatus() Sets the current record's "status" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdHomepageShoe extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_homepage_shoe');
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
        $this->hasColumn('price', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
             ));
        $this->hasColumn('logo1', 'string', 500, array(
             'type' => 'string',
             'length' => 500,
             ));
        $this->hasColumn('logo2', 'string', 500, array(
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