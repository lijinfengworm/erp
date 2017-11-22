<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdShaiwuActivity', 'trade');

/**
 * BaseTrdShaiwuActivity
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $title
 * @property string $pic
 * @property string $content
 * @property integer $num
 * @property integer $stime
 * @property integer $etime
 * 
 * @method integer           getId()      Returns the current record's "id" value
 * @method string            getTitle()   Returns the current record's "title" value
 * @method string            getPic()     Returns the current record's "pic" value
 * @method string            getContent() Returns the current record's "content" value
 * @method integer           getNum()     Returns the current record's "num" value
 * @method integer           getStime()   Returns the current record's "stime" value
 * @method integer           getEtime()   Returns the current record's "etime" value
 * @method TrdShaiwuActivity setId()      Sets the current record's "id" value
 * @method TrdShaiwuActivity setTitle()   Sets the current record's "title" value
 * @method TrdShaiwuActivity setPic()     Sets the current record's "pic" value
 * @method TrdShaiwuActivity setContent() Sets the current record's "content" value
 * @method TrdShaiwuActivity setNum()     Sets the current record's "num" value
 * @method TrdShaiwuActivity setStime()   Sets the current record's "stime" value
 * @method TrdShaiwuActivity setEtime()   Sets the current record's "etime" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdShaiwuActivity extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_shaiwu_activity');
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
        $this->hasColumn('pic', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('content', 'string', 3000, array(
             'type' => 'string',
             'length' => 3000,
             ));
        $this->hasColumn('num', 'integer', null, array(
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


        $this->index('idx_etime', array(
             'fields' => 
             array(
              0 => 'etime',
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