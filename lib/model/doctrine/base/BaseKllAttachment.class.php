<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllAttachment', 'kaluliCMS');

/**
 * BaseKllAttachment
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property tinyint $aid
 * @property tinyint $type
 * @property string $original
 * @property string $medium
 * @property string $small
 * @property tinyint $is_use
 * 
 * @method integer       getId()       Returns the current record's "id" value
 * @method tinyint       getAid()      Returns the current record's "aid" value
 * @method tinyint       getType()     Returns the current record's "type" value
 * @method string        getOriginal() Returns the current record's "original" value
 * @method string        getMedium()   Returns the current record's "medium" value
 * @method string        getSmall()    Returns the current record's "small" value
 * @method tinyint       getIsUse()    Returns the current record's "is_use" value
 * @method KllAttachment setId()       Sets the current record's "id" value
 * @method KllAttachment setAid()      Sets the current record's "aid" value
 * @method KllAttachment setType()     Sets the current record's "type" value
 * @method KllAttachment setOriginal() Sets the current record's "original" value
 * @method KllAttachment setMedium()   Sets the current record's "medium" value
 * @method KllAttachment setSmall()    Sets the current record's "small" value
 * @method KllAttachment setIsUse()    Sets the current record's "is_use" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllAttachment extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_attachment');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('aid', 'tinyint', 11, array(
             'type' => 'tinyint',
             'length' => 11,
             ));
        $this->hasColumn('type', 'tinyint', 5, array(
             'type' => 'tinyint',
             'length' => 5,
             ));
        $this->hasColumn('original', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('medium', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('small', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('is_use', 'tinyint', 2, array(
             'type' => 'tinyint',
             'length' => 2,
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