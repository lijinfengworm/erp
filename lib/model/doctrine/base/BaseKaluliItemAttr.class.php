<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KaluliItemAttr', 'kaluli');

/**
 * BaseKaluliItemAttr
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $item_id
 * @property text $content
 * @property text $pic_detail
 * @property integer $comment_imgs_count
 * @property integer $comment_count
 * @property string $comment_tags_count
 * @property string $attrs
 * @property string $review
 * @property integer $sales_count
 * 
 * @method integer        getId()                 Returns the current record's "id" value
 * @method integer        getItemId()             Returns the current record's "item_id" value
 * @method text           getContent()            Returns the current record's "content" value
 * @method text           getPicDetail()          Returns the current record's "pic_detail" value
 * @method integer        getCommentImgsCount()   Returns the current record's "comment_imgs_count" value
 * @method integer        getCommentCount()       Returns the current record's "comment_count" value
 * @method string         getCommentTagsCount()   Returns the current record's "comment_tags_count" value
 * @method string         getAttrs()              Returns the current record's "attrs" value
 * @method string         getReview()             Returns the current record's "review" value
 * @method integer        getSalesCount()         Returns the current record's "sales_count" value
 * @method KaluliItemAttr setId()                 Sets the current record's "id" value
 * @method KaluliItemAttr setItemId()             Sets the current record's "item_id" value
 * @method KaluliItemAttr setContent()            Sets the current record's "content" value
 * @method KaluliItemAttr setPicDetail()          Sets the current record's "pic_detail" value
 * @method KaluliItemAttr setCommentImgsCount()   Sets the current record's "comment_imgs_count" value
 * @method KaluliItemAttr setCommentCount()       Sets the current record's "comment_count" value
 * @method KaluliItemAttr setCommentTagsCount()   Sets the current record's "comment_tags_count" value
 * @method KaluliItemAttr setAttrs()              Sets the current record's "attrs" value
 * @method KaluliItemAttr setReview()             Sets the current record's "review" value
 * @method KaluliItemAttr setSalesCount()         Sets the current record's "sales_count" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKaluliItemAttr extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_item_attr');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('item_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('content', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('pic_detail', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('comment_imgs_count', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('comment_count', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('comment_tags_count', 'string', 2000, array(
             'type' => 'string',
             'length' => 2000,
             ));
        $this->hasColumn('attrs', 'string', 2000, array(
             'type' => 'string',
             'length' => 2000,
             ));
        $this->hasColumn('review', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('sales_count', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
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