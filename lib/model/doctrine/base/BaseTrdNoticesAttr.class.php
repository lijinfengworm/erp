<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdNoticesAttr', 'trade');

/**
 * BaseTrdNoticesAttr
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $notice_id
 * @property text $content
 * @property integer $comment_id
 * @property integer $reply_id
 * @property text $extra
 * 
 * @method integer        getId()         Returns the current record's "id" value
 * @method integer        getNoticeId()   Returns the current record's "notice_id" value
 * @method text           getContent()    Returns the current record's "content" value
 * @method integer        getCommentId()  Returns the current record's "comment_id" value
 * @method integer        getReplyId()    Returns the current record's "reply_id" value
 * @method text           getExtra()      Returns the current record's "extra" value
 * @method TrdNoticesAttr setId()         Sets the current record's "id" value
 * @method TrdNoticesAttr setNoticeId()   Sets the current record's "notice_id" value
 * @method TrdNoticesAttr setContent()    Sets the current record's "content" value
 * @method TrdNoticesAttr setCommentId()  Sets the current record's "comment_id" value
 * @method TrdNoticesAttr setReplyId()    Sets the current record's "reply_id" value
 * @method TrdNoticesAttr setExtra()      Sets the current record's "extra" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdNoticesAttr extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_notices_attr');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('notice_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('content', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('comment_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('reply_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('extra', 'text', null, array(
             'type' => 'text',
             ));


        $this->index('notice_id', array(
             'fields' => 
             array(
              0 => 'notice_id',
             ),
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