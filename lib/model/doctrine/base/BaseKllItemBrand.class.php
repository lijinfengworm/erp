<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllItemBrand', 'kaluli');

/**
 * BaseKllItemBrand
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property integer $weight
 * @property string $logo
 * @property string $banner
 * @property string $place
 * @property string $place_en
 * @property string $place_flag
 * @property text $description
 * @property integer $status
 * @property timestamp $ct_time
 * 
 * @method integer      getId()          Returns the current record's "id" value
 * @method string       getName()        Returns the current record's "name" value
 * @method integer      getWeight()      Returns the current record's "weight" value
 * @method string       getLogo()        Returns the current record's "logo" value
 * @method string       getBanner()      Returns the current record's "banner" value
 * @method string       getPlace()       Returns the current record's "place" value
 * @method string       getPlaceEn()     Returns the current record's "place_en" value
 * @method string       getPlaceFlag()   Returns the current record's "place_flag" value
 * @method text         getDescription() Returns the current record's "description" value
 * @method integer      getStatus()      Returns the current record's "status" value
 * @method timestamp    getCtTime()      Returns the current record's "ct_time" value
 * @method KllItemBrand setId()          Sets the current record's "id" value
 * @method KllItemBrand setName()        Sets the current record's "name" value
 * @method KllItemBrand setWeight()      Sets the current record's "weight" value
 * @method KllItemBrand setLogo()        Sets the current record's "logo" value
 * @method KllItemBrand setBanner()      Sets the current record's "banner" value
 * @method KllItemBrand setPlace()       Sets the current record's "place" value
 * @method KllItemBrand setPlaceEn()     Sets the current record's "place_en" value
 * @method KllItemBrand setPlaceFlag()   Sets the current record's "place_flag" value
 * @method KllItemBrand setDescription() Sets the current record's "description" value
 * @method KllItemBrand setStatus()      Sets the current record's "status" value
 * @method KllItemBrand setCtTime()      Sets the current record's "ct_time" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllItemBrand extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_item_brand');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('name', 'string', 128, array(
             'type' => 'string',
             'length' => 128,
             ));
        $this->hasColumn('weight', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('logo', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('banner', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('place', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('place_en', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('place_flag', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('description', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('status', 'integer', 2, array(
             'type' => 'integer',
             'length' => 2,
             ));
        $this->hasColumn('ct_time', 'timestamp', null, array(
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