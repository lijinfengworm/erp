<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('trdCommentProhibit', 'trade');

/**
 * BasetrdCommentProhibit
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $type
 * @property integer $type_num
 * @property datetime $allow_date
 * 
 * @method integer            getId()         Returns the current record's "id" value
 * @method integer            getType()       Returns the current record's "type" value
 * @method integer            getTypeNum()    Returns the current record's "type_num" value
 * @method datetime           getAllowDate()  Returns the current record's "allow_date" value
 * @method trdCommentProhibit setId()         Sets the current record's "id" value
 * @method trdCommentProhibit setType()       Sets the current record's "type" value
 * @method trdCommentProhibit setTypeNum()    Sets the current record's "type_num" value
 * @method trdCommentProhibit setAllowDate()  Sets the current record's "allow_date" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasetrdCommentProhibit extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_comment_prohibit');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('type', 'integer', 1, array(
             'type' => 'integer',
             'length' => 1,
             ));
        $this->hasColumn('type_num', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('allow_date', 'datetime', null, array(
             'type' => 'datetime',
             ));


        $this->index('unite_index', array(
             'fields' => 
             array(
              0 => 'type',
              1 => 'type_num',
             ),
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