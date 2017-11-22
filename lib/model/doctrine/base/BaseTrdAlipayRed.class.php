<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdAlipayRed', 'trade');

/**
 * BaseTrdAlipayRed
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $account
 * @property string $pass
 * @property integer $money
 * @property integer $status
 * 
 * @method integer      getId()      Returns the current record's "id" value
 * @method string       getAccount() Returns the current record's "account" value
 * @method string       getPass()    Returns the current record's "pass" value
 * @method integer      getMoney()   Returns the current record's "money" value
 * @method integer      getStatus()  Returns the current record's "status" value
 * @method TrdAlipayRed setId()      Sets the current record's "id" value
 * @method TrdAlipayRed setAccount() Sets the current record's "account" value
 * @method TrdAlipayRed setPass()    Sets the current record's "pass" value
 * @method TrdAlipayRed setMoney()   Sets the current record's "money" value
 * @method TrdAlipayRed setStatus()  Sets the current record's "status" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdAlipayRed extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_alipay_red');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('account', 'string', 30, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 30,
             ));
        $this->hasColumn('pass', 'string', 20, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 20,
             ));
        $this->hasColumn('money', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 10,
             ));
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));


        $this->index('account', array(
             'fields' => 
             array(
              0 => 'account',
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