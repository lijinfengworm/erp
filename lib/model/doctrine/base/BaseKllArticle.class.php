<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllArticle', 'kaluliCMS');

/**
 * BaseKllArticle
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $cid
 * @property integer $seo_id
 * @property string $author
 * @property integer $public_time
 * @property string $relate_gid
 * @property string $label
 * @property string $title
 * @property string $abstract
 * @property text $content
 * @property integer $add_time
 * @property integer $update_time
 * @property tinyint $is_use
 * @property integer $audit_uid
 * @property integer $audit_time
 * 
 * @method integer    getId()          Returns the current record's "id" value
 * @method integer    getCid()         Returns the current record's "cid" value
 * @method integer    getSeoId()       Returns the current record's "seo_id" value
 * @method string     getAuthor()      Returns the current record's "author" value
 * @method integer    getPublicTime()  Returns the current record's "public_time" value
 * @method string     getRelateGid()   Returns the current record's "relate_gid" value
 * @method string     getLabel()       Returns the current record's "label" value
 * @method string     getTitle()       Returns the current record's "title" value
 * @method string     getAbstract()    Returns the current record's "abstract" value
 * @method text       getContent()     Returns the current record's "content" value
 * @method integer    getAddTime()     Returns the current record's "add_time" value
 * @method integer    getUpdateTime()  Returns the current record's "update_time" value
 * @method tinyint    getIsUse()       Returns the current record's "is_use" value
 * @method integer    getAuditUid()    Returns the current record's "audit_uid" value
 * @method integer    getAuditTime()   Returns the current record's "audit_time" value
 * @method KllArticle setId()          Sets the current record's "id" value
 * @method KllArticle setCid()         Sets the current record's "cid" value
 * @method KllArticle setSeoId()       Sets the current record's "seo_id" value
 * @method KllArticle setAuthor()      Sets the current record's "author" value
 * @method KllArticle setPublicTime()  Sets the current record's "public_time" value
 * @method KllArticle setRelateGid()   Sets the current record's "relate_gid" value
 * @method KllArticle setLabel()       Sets the current record's "label" value
 * @method KllArticle setTitle()       Sets the current record's "title" value
 * @method KllArticle setAbstract()    Sets the current record's "abstract" value
 * @method KllArticle setContent()     Sets the current record's "content" value
 * @method KllArticle setAddTime()     Sets the current record's "add_time" value
 * @method KllArticle setUpdateTime()  Sets the current record's "update_time" value
 * @method KllArticle setIsUse()       Sets the current record's "is_use" value
 * @method KllArticle setAuditUid()    Sets the current record's "audit_uid" value
 * @method KllArticle setAuditTime()   Sets the current record's "audit_time" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllArticle extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_article');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('cid', 'integer', 10, array(
             'type' => 'integer',
             'length' => 10,
             ));
        $this->hasColumn('seo_id', 'integer', 10, array(
             'type' => 'integer',
             'length' => 10,
             ));
        $this->hasColumn('author', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('public_time', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('relate_gid', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('label', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('abstract', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('content', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('add_time', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 11,
             ));
        $this->hasColumn('update_time', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 11,
             ));
        $this->hasColumn('is_use', 'tinyint', 2, array(
             'type' => 'tinyint',
             'length' => 2,
             ));
        $this->hasColumn('audit_uid', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 11,
             ));
        $this->hasColumn('audit_time', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 11,
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