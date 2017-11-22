<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllComment', 'kaluli');

/**
 * BaseKllComment
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $cid
 * @property integer $product_id
 * @property integer $user_id
 * @property string $user_name
 * @property text $content
 * @property text $imgs
 * @property string $tags_attr
 * @property string $attr
 * @property integer $status
 * @property integer $private
 * 
 * @method integer    getId()         Returns the current record's "id" value
 * @method integer    getCid()        Returns the current record's "cid" value
 * @method integer    getProductId()  Returns the current record's "product_id" value
 * @method integer    getUserId()     Returns the current record's "user_id" value
 * @method string     getUserName()   Returns the current record's "user_name" value
 * @method text       getContent()    Returns the current record's "content" value
 * @method text       getImgs()       Returns the current record's "imgs" value
 * @method string     getTagsAttr()   Returns the current record's "tags_attr" value
 * @method string     getAttr()       Returns the current record's "attr" value
 * @method integer    getStatus()     Returns the current record's "status" value
 * @method integer    getPrivate()    Returns the current record's "private" value
 * @method KllComment setId()         Sets the current record's "id" value
 * @method KllComment setCid()        Sets the current record's "cid" value
 * @method KllComment setProductId()  Sets the current record's "product_id" value
 * @method KllComment setUserId()     Sets the current record's "user_id" value
 * @method KllComment setUserName()   Sets the current record's "user_name" value
 * @method KllComment setContent()    Sets the current record's "content" value
 * @method KllComment setImgs()       Sets the current record's "imgs" value
 * @method KllComment setTagsAttr()   Sets the current record's "tags_attr" value
 * @method KllComment setAttr()       Sets the current record's "attr" value
 * @method KllComment setStatus()     Sets the current record's "status" value
 * @method KllComment setPrivate()    Sets the current record's "private" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllComment extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_comment');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('cid', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             ));
        $this->hasColumn('product_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('user_name', 'string', 128, array(
             'type' => 'string',
             'length' => 128,
             ));
        $this->hasColumn('content', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('imgs', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('tags_attr', 'string', 512, array(
             'type' => 'string',
             'length' => 512,
             ));
        $this->hasColumn('attr', 'string', 256, array(
             'type' => 'string',
             'length' => 256,
             ));
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 1,
             'length' => 1,
             ));
        $this->hasColumn('private', 'integer', 1, array(
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