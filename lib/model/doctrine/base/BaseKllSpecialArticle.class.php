<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllSpecialArticle', 'kaluliCMS');

/**
 * BaseKllSpecialArticle
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $special_id
 * @property string $article_id
 * 
 * @method integer           getId()         Returns the current record's "id" value
 * @method integer           getSpecialId()  Returns the current record's "special_id" value
 * @method string            getArticleId()  Returns the current record's "article_id" value
 * @method KllSpecialArticle setId()         Sets the current record's "id" value
 * @method KllSpecialArticle setSpecialId()  Sets the current record's "special_id" value
 * @method KllSpecialArticle setArticleId()  Sets the current record's "article_id" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllSpecialArticle extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_special_article');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('special_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 11,
             ));
        $this->hasColumn('article_id', 'string', 255, array(
             'type' => 'string',
             'unsigned' => true,
             'length' => 255,
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