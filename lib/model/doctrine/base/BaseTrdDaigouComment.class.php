<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdDaigouComment', 'trade');

/**
 * BaseTrdDaigouComment
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $product_id
 * @property integer $user_id
 * @property string $user_name
 * @property text $content
 * @property text $imgs
 * @property string $tags_attr
 * @property string $attr
 * @property integer $status
 * @property datetime $created_at
 * @property datetime $updated_at
 * @property Doctrine_Collection $TrdDaigouCommentTags
 * 
 * @method integer             getId()                   Returns the current record's "id" value
 * @method integer             getProductId()            Returns the current record's "product_id" value
 * @method integer             getUserId()               Returns the current record's "user_id" value
 * @method string              getUserName()             Returns the current record's "user_name" value
 * @method text                getContent()              Returns the current record's "content" value
 * @method text                getImgs()                 Returns the current record's "imgs" value
 * @method string              getTagsAttr()             Returns the current record's "tags_attr" value
 * @method string              getAttr()                 Returns the current record's "attr" value
 * @method integer             getStatus()               Returns the current record's "status" value
 * @method datetime            getCreatedAt()            Returns the current record's "created_at" value
 * @method datetime            getUpdatedAt()            Returns the current record's "updated_at" value
 * @method Doctrine_Collection getTrdDaigouCommentTags() Returns the current record's "TrdDaigouCommentTags" collection
 * @method TrdDaigouComment    setId()                   Sets the current record's "id" value
 * @method TrdDaigouComment    setProductId()            Sets the current record's "product_id" value
 * @method TrdDaigouComment    setUserId()               Sets the current record's "user_id" value
 * @method TrdDaigouComment    setUserName()             Sets the current record's "user_name" value
 * @method TrdDaigouComment    setContent()              Sets the current record's "content" value
 * @method TrdDaigouComment    setImgs()                 Sets the current record's "imgs" value
 * @method TrdDaigouComment    setTagsAttr()             Sets the current record's "tags_attr" value
 * @method TrdDaigouComment    setAttr()                 Sets the current record's "attr" value
 * @method TrdDaigouComment    setStatus()               Sets the current record's "status" value
 * @method TrdDaigouComment    setCreatedAt()            Sets the current record's "created_at" value
 * @method TrdDaigouComment    setUpdatedAt()            Sets the current record's "updated_at" value
 * @method TrdDaigouComment    setTrdDaigouCommentTags() Sets the current record's "TrdDaigouCommentTags" collection
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdDaigouComment extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_daigou_comment');
        $this->hasColumn('id', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 10,
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
        $this->hasColumn('tags_attr', 'string', 256, array(
             'type' => 'string',
             'length' => 256,
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
        $this->hasColumn('created_at', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('updated_at', 'datetime', null, array(
             'type' => 'datetime',
             ));


        $this->index('children_id', array(
             'fields' => 
             array(
              0 => 'children_id',
             ),
             ));
        $this->index('root_id', array(
             'fields' => 
             array(
              0 => 'root_id',
             ),
             ));
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('TrdDaigouCommentTags', array(
             'local' => 'id',
             'foreign' => 'daigou_comment_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}