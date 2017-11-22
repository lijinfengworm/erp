<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllWiki', 'kaluli');

/**
 * BaseKllWiki
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $title
 * @property string $banner
 * @property text $content
 * @property string $qa
 * @property string $relate_article
 * 
 * @method integer getId()             Returns the current record's "id" value
 * @method string  getTitle()          Returns the current record's "title" value
 * @method string  getBanner()         Returns the current record's "banner" value
 * @method text    getContent()        Returns the current record's "content" value
 * @method string  getQa()             Returns the current record's "qa" value
 * @method string  getRelateArticle()  Returns the current record's "relate_article" value
 * @method KllWiki setId()             Sets the current record's "id" value
 * @method KllWiki setTitle()          Sets the current record's "title" value
 * @method KllWiki setBanner()         Sets the current record's "banner" value
 * @method KllWiki setContent()        Sets the current record's "content" value
 * @method KllWiki setQa()             Sets the current record's "qa" value
 * @method KllWiki setRelateArticle()  Sets the current record's "relate_article" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllWiki extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_wiki');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('title', 'string', 500, array(
             'type' => 'string',
             'length' => 500,
             ));
        $this->hasColumn('banner', 'string', 500, array(
             'type' => 'string',
             'length' => 500,
             ));
        $this->hasColumn('content', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('qa', 'string', 1000, array(
             'type' => 'string',
             'length' => 1000,
             ));
        $this->hasColumn('relate_article', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
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