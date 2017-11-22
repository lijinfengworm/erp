<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdSeoNewsCluster', 'trade');

/**
 * BaseTrdSeoNewsCluster
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $intro
 * @property string $text
 * 
 * @method integer           getId()    Returns the current record's "id" value
 * @method string            getIntro() Returns the current record's "intro" value
 * @method string            getText()  Returns the current record's "text" value
 * @method TrdSeoNewsCluster setId()    Sets the current record's "id" value
 * @method TrdSeoNewsCluster setIntro() Sets the current record's "intro" value
 * @method TrdSeoNewsCluster setText()  Sets the current record's "text" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdSeoNewsCluster extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_seo_news_cluster');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'length' => 8,
             ));
        $this->hasColumn('intro', 'string', 1000, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 1000,
             ));
        $this->hasColumn('text', 'string', 1000, array(
             'type' => 'string',
             'length' => 1000,
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