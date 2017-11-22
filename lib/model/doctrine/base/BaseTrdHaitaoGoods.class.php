<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdHaitaoGoods', 'trade');

/**
 * BaseTrdHaitaoGoods
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $product_id
 * @property string $title
 * @property string $goods_id
 * @property string $attr
 * @property string $code
 * @property integer $total_num
 * @property integer $lock_num
 * @property integer $status
 * 
 * @method integer        getId()         Returns the current record's "id" value
 * @method integer        getProductId()  Returns the current record's "product_id" value
 * @method string         getTitle()      Returns the current record's "title" value
 * @method string         getGoodsId()    Returns the current record's "goods_id" value
 * @method string         getAttr()       Returns the current record's "attr" value
 * @method string         getCode()       Returns the current record's "code" value
 * @method integer        getTotalNum()   Returns the current record's "total_num" value
 * @method integer        getLockNum()    Returns the current record's "lock_num" value
 * @method integer        getStatus()     Returns the current record's "status" value
 * @method TrdHaitaoGoods setId()         Sets the current record's "id" value
 * @method TrdHaitaoGoods setProductId()  Sets the current record's "product_id" value
 * @method TrdHaitaoGoods setTitle()      Sets the current record's "title" value
 * @method TrdHaitaoGoods setGoodsId()    Sets the current record's "goods_id" value
 * @method TrdHaitaoGoods setAttr()       Sets the current record's "attr" value
 * @method TrdHaitaoGoods setCode()       Sets the current record's "code" value
 * @method TrdHaitaoGoods setTotalNum()   Sets the current record's "total_num" value
 * @method TrdHaitaoGoods setLockNum()    Sets the current record's "lock_num" value
 * @method TrdHaitaoGoods setStatus()     Sets the current record's "status" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdHaitaoGoods extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_haitao_goods');
        $this->hasColumn('id', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 10,
             ));
        $this->hasColumn('product_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('title', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('goods_id', 'string', 30, array(
             'type' => 'string',
             'length' => 30,
             ));
        $this->hasColumn('attr', 'string', 20000, array(
             'type' => 'string',
             'length' => 20000,
             ));
        $this->hasColumn('code', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('total_num', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('lock_num', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 1,
             ));


        $this->index('goods_id', array(
             'fields' => 
             array(
              0 => 'goods_id',
             ),
             'type' => 'unique',
             ));
        $this->index('code', array(
             'fields' => 
             array(
              0 => 'code',
             ),
             'type' => 'unique',
             ));
        $this->index('product_id', array(
             'fields' => 
             array(
              0 => 'product_id',
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