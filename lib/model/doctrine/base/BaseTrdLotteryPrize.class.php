<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdLotteryPrize', 'trade');

/**
 * BaseTrdLotteryPrize
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $lottery_id
 * @property string $prize_name
 * @property integer $prize_rand
 * @property integer $is_virtual
 * @property integer $virtual_type
 * @property integer $prize_num
 * @property string $prize_info
 * @property integer $listorder
 * 
 * @method integer         getId()           Returns the current record's "id" value
 * @method integer         getLotteryId()    Returns the current record's "lottery_id" value
 * @method string          getPrizeName()    Returns the current record's "prize_name" value
 * @method integer         getPrizeRand()    Returns the current record's "prize_rand" value
 * @method integer         getIsVirtual()    Returns the current record's "is_virtual" value
 * @method integer         getVirtualType()  Returns the current record's "virtual_type" value
 * @method integer         getPrizeNum()     Returns the current record's "prize_num" value
 * @method string          getPrizeInfo()    Returns the current record's "prize_info" value
 * @method integer         getListorder()    Returns the current record's "listorder" value
 * @method TrdLotteryPrize setId()           Sets the current record's "id" value
 * @method TrdLotteryPrize setLotteryId()    Sets the current record's "lottery_id" value
 * @method TrdLotteryPrize setPrizeName()    Sets the current record's "prize_name" value
 * @method TrdLotteryPrize setPrizeRand()    Sets the current record's "prize_rand" value
 * @method TrdLotteryPrize setIsVirtual()    Sets the current record's "is_virtual" value
 * @method TrdLotteryPrize setVirtualType()  Sets the current record's "virtual_type" value
 * @method TrdLotteryPrize setPrizeNum()     Sets the current record's "prize_num" value
 * @method TrdLotteryPrize setPrizeInfo()    Sets the current record's "prize_info" value
 * @method TrdLotteryPrize setListorder()    Sets the current record's "listorder" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdLotteryPrize extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_lottery_prize');
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
        $this->hasColumn('prize_name', 'string', 64, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             'length' => 64,
             ));
        $this->hasColumn('prize_rand', 'integer', 8, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'default' => 0,
             'length' => 8,
             ));
        $this->hasColumn('is_virtual', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('virtual_type', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'default' => 1,
             'length' => 1,
             ));
        $this->hasColumn('prize_num', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'default' => 0,
             'length' => 11,
             ));
        $this->hasColumn('prize_info', 'string', 128, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             'length' => 128,
             ));
        $this->hasColumn('listorder', 'integer', 2, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'default' => 0,
             'length' => 2,
             ));


        $this->index('lottery_id', array(
             'fields' => 
             array(
              0 => 'lottery_id',
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