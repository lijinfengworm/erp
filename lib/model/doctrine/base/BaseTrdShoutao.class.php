<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdShoutao', 'trade');

/**
 * BaseTrdShoutao
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property bigint $tid
 * @property string $title
 * @property bigint $item_id
 * @property string $item_url
 * @property string $pic
 * @property text $recommend
 * @property string $tags
 * @property integer $type
 * @property datetime $send_time
 * @property string $content_img
 * @property integer $admin_id
 * 
 * @method integer    getId()          Returns the current record's "id" value
 * @method bigint     getTid()         Returns the current record's "tid" value
 * @method string     getTitle()       Returns the current record's "title" value
 * @method bigint     getItemId()      Returns the current record's "item_id" value
 * @method string     getItemUrl()     Returns the current record's "item_url" value
 * @method string     getPic()         Returns the current record's "pic" value
 * @method text       getRecommend()   Returns the current record's "recommend" value
 * @method string     getTags()        Returns the current record's "tags" value
 * @method integer    getType()        Returns the current record's "type" value
 * @method datetime   getSendTime()    Returns the current record's "send_time" value
 * @method string     getContentImg()  Returns the current record's "content_img" value
 * @method integer    getAdminId()     Returns the current record's "admin_id" value
 * @method TrdShoutao setId()          Sets the current record's "id" value
 * @method TrdShoutao setTid()         Sets the current record's "tid" value
 * @method TrdShoutao setTitle()       Sets the current record's "title" value
 * @method TrdShoutao setItemId()      Sets the current record's "item_id" value
 * @method TrdShoutao setItemUrl()     Sets the current record's "item_url" value
 * @method TrdShoutao setPic()         Sets the current record's "pic" value
 * @method TrdShoutao setRecommend()   Sets the current record's "recommend" value
 * @method TrdShoutao setTags()        Sets the current record's "tags" value
 * @method TrdShoutao setType()        Sets the current record's "type" value
 * @method TrdShoutao setSendTime()    Sets the current record's "send_time" value
 * @method TrdShoutao setContentImg()  Sets the current record's "content_img" value
 * @method TrdShoutao setAdminId()     Sets the current record's "admin_id" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdShoutao extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_shoutao');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('tid', 'bigint', 20, array(
             'type' => 'bigint',
             'notnull' => true,
             'length' => 20,
             ));
        $this->hasColumn('title', 'string', 25, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 25,
             ));
        $this->hasColumn('item_id', 'bigint', 20, array(
             'type' => 'bigint',
             'notnull' => true,
             'length' => 20,
             ));
        $this->hasColumn('item_url', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('pic', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('recommend', 'text', null, array(
             'type' => 'text',
             'notnull' => true,
             ));
        $this->hasColumn('tags', 'string', 100, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 100,
             ));
        $this->hasColumn('type', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 1,
             ));
        $this->hasColumn('send_time', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('content_img', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('admin_id', 'integer', null, array(
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
        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}