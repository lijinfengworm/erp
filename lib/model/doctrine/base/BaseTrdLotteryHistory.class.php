<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdLotteryHistory', 'trade');

/**
 * BaseTrdLotteryHistory
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $lottery_id
 * @property integer $user_id
 * @property string $phone
 * @property integer $prize_id
 * @property string $prize_name
 * @property integer $is_virtual
 * @property string $card
 * @property string $ip
 * @property string $source
 * @property integer $status
 * @property integer $is_send
 * @property string $address
 * 
 * @method integer           getId()         Returns the current record's "id" value
 * @method integer           getLotteryId()  Returns the current record's "lottery_id" value
 * @method integer           getUserId()     Returns the current record's "user_id" value
 * @method string            getPhone()      Returns the current record's "phone" value
 * @method integer           getPrizeId()    Returns the current record's "prize_id" value
 * @method string            getPrizeName()  Returns the current record's "prize_name" value
 * @method integer           getIsVirtual()  Returns the current record's "is_virtual" value
 * @method string            getCard()       Returns the current record's "card" value
 * @method string            getIp()         Returns the current record's "ip" value
 * @method string            getSource()     Returns the current record's "source" value
 * @method integer           getStatus()     Returns the current record's "status" value
 * @method integer           getIsSend()     Returns the current record's "is_send" value
 * @method string            getAddress()    Returns the current record's "address" value
 * @method TrdLotteryHistory setId()         Sets the current record's "id" value
 * @method TrdLotteryHistory setLotteryId()  Sets the current record's "lottery_id" value
 * @method TrdLotteryHistory setUserId()     Sets the current record's "user_id" value
 * @method TrdLotteryHistory setPhone()      Sets the current record's "phone" value
 * @method TrdLotteryHistory setPrizeId()    Sets the current record's "prize_id" value
 * @method TrdLotteryHistory setPrizeName()  Sets the current record's "prize_name" value
 * @method TrdLotteryHistory setIsVirtual()  Sets the current record's "is_virtual" value
 * @method TrdLotteryHistory setCard()       Sets the current record's "card" value
 * @method TrdLotteryHistory setIp()         Sets the current record's "ip" value
 * @method TrdLotteryHistory setSource()     Sets the current record's "source" value
 * @method TrdLotteryHistory setStatus()     Sets the current record's "status" value
 * @method TrdLotteryHistory setIsSend()     Sets the current record's "is_send" value
 * @method TrdLotteryHistory setAddress()    Sets the current record's "address" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdLotteryHistory extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_lottery_history');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => 8,
             ));
        $this->hasColumn('lottery_id', 'integer', 8, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'default' => 0,
             'length' => 8,
             ));
        $this->hasColumn('user_id', 'integer', 8, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'default' => 0,
             'length' => 8,
             ));
        $this->hasColumn('phone', 'string', 15, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             'length' => 15,
             ));
        $this->hasColumn('prize_id', 'integer', 8, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'default' => 0,
             'length' => 8,
             ));
        $this->hasColumn('prize_name', 'string', 64, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             'length' => 64,
             ));
        $this->hasColumn('is_virtual', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('card', 'string', 64, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             'length' => 64,
             ));
        $this->hasColumn('ip', 'string', 15, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             'length' => 15,
             ));
        $this->hasColumn('source', 'string', 12, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             'length' => 12,
             ));
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('is_send', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('address', 'string', 512, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             'length' => 512,
             ));


        $this->index('lottery_id', array(
             'fields' => 
             array(
              0 => 'lottery_id',
             ),
             ));
        $this->index('user_id', array(
             'fields' => 
             array(
              0 => 'user_id',
             ),
             ));
        $this->index('prize_id', array(
             'fields' => 
             array(
              0 => 'prize_id',
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