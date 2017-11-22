<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdOrder', 'trade');

/**
 * BaseTrdOrder
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $order_number
 * @property string $ibilling_number
 * @property string $title
 * @property integer $product_id
 * @property integer $gid
 * @property string $goods_id
 * @property string $mart_order_number
 * @property integer $mart_order_time
 * @property string $mart_express_number
 * @property integer $mart_express_time
 * @property integer $domestic_express_type
 * @property string $domestic_order_number
 * @property integer $domestic_express_time
 * @property string $attr
 * @property string $business
 * @property string $business_account
 * @property integer $storage_status
 * @property float $express_fee
 * @property float $total_price
 * @property float $price
 * @property float $marketing_fee
 * @property float $refund_price
 * @property float $refund_express_fee
 * @property float $refund
 * @property string $refund_remark
 * @property datetime $order_time
 * @property datetime $storage_time
 * @property datetime $pay_time
 * @property integer $pay_type
 * @property datetime $refund_time
 * @property integer $status
 * @property integer $operations_status
 * @property integer $is_plugin_added
 * @property integer $pay_status
 * @property integer $source
 * @property string $channel
 * @property integer $hupu_uid
 * @property string $hupu_username
 * @property integer $grant_uid
 * @property string $grant_username
 * @property integer $grab_order_time
 * @property integer $finish_order_time
 * @property integer $forecast
 * @property integer $is_comment
 * @property integer $delivery_type
 * @property string $mobile
 * 
 * @method integer  getId()                    Returns the current record's "id" value
 * @method integer  getOrderNumber()           Returns the current record's "order_number" value
 * @method string   getIbillingNumber()        Returns the current record's "ibilling_number" value
 * @method string   getTitle()                 Returns the current record's "title" value
 * @method integer  getProductId()             Returns the current record's "product_id" value
 * @method integer  getGid()                   Returns the current record's "gid" value
 * @method string   getGoodsId()               Returns the current record's "goods_id" value
 * @method string   getMartOrderNumber()       Returns the current record's "mart_order_number" value
 * @method integer  getMartOrderTime()         Returns the current record's "mart_order_time" value
 * @method string   getMartExpressNumber()     Returns the current record's "mart_express_number" value
 * @method integer  getMartExpressTime()       Returns the current record's "mart_express_time" value
 * @method integer  getDomesticExpressType()   Returns the current record's "domestic_express_type" value
 * @method string   getDomesticOrderNumber()   Returns the current record's "domestic_order_number" value
 * @method integer  getDomesticExpressTime()   Returns the current record's "domestic_express_time" value
 * @method string   getAttr()                  Returns the current record's "attr" value
 * @method string   getBusiness()              Returns the current record's "business" value
 * @method string   getBusinessAccount()       Returns the current record's "business_account" value
 * @method integer  getStorageStatus()         Returns the current record's "storage_status" value
 * @method float    getExpressFee()            Returns the current record's "express_fee" value
 * @method float    getTotalPrice()            Returns the current record's "total_price" value
 * @method float    getPrice()                 Returns the current record's "price" value
 * @method float    getMarketingFee()          Returns the current record's "marketing_fee" value
 * @method float    getRefundPrice()           Returns the current record's "refund_price" value
 * @method float    getRefundExpressFee()      Returns the current record's "refund_express_fee" value
 * @method float    getRefund()                Returns the current record's "refund" value
 * @method string   getRefundRemark()          Returns the current record's "refund_remark" value
 * @method datetime getOrderTime()             Returns the current record's "order_time" value
 * @method datetime getStorageTime()           Returns the current record's "storage_time" value
 * @method datetime getPayTime()               Returns the current record's "pay_time" value
 * @method integer  getPayType()               Returns the current record's "pay_type" value
 * @method datetime getRefundTime()            Returns the current record's "refund_time" value
 * @method integer  getStatus()                Returns the current record's "status" value
 * @method integer  getOperationsStatus()      Returns the current record's "operations_status" value
 * @method integer  getIsPluginAdded()         Returns the current record's "is_plugin_added" value
 * @method integer  getPayStatus()             Returns the current record's "pay_status" value
 * @method integer  getSource()                Returns the current record's "source" value
 * @method string   getChannel()               Returns the current record's "channel" value
 * @method integer  getHupuUid()               Returns the current record's "hupu_uid" value
 * @method string   getHupuUsername()          Returns the current record's "hupu_username" value
 * @method integer  getGrantUid()              Returns the current record's "grant_uid" value
 * @method string   getGrantUsername()         Returns the current record's "grant_username" value
 * @method integer  getGrabOrderTime()         Returns the current record's "grab_order_time" value
 * @method integer  getFinishOrderTime()       Returns the current record's "finish_order_time" value
 * @method integer  getForecast()              Returns the current record's "forecast" value
 * @method integer  getIsComment()             Returns the current record's "is_comment" value
 * @method integer  getDeliveryType()          Returns the current record's "delivery_type" value
 * @method string   getMobile()                Returns the current record's "mobile" value
 * @method TrdOrder setId()                    Sets the current record's "id" value
 * @method TrdOrder setOrderNumber()           Sets the current record's "order_number" value
 * @method TrdOrder setIbillingNumber()        Sets the current record's "ibilling_number" value
 * @method TrdOrder setTitle()                 Sets the current record's "title" value
 * @method TrdOrder setProductId()             Sets the current record's "product_id" value
 * @method TrdOrder setGid()                   Sets the current record's "gid" value
 * @method TrdOrder setGoodsId()               Sets the current record's "goods_id" value
 * @method TrdOrder setMartOrderNumber()       Sets the current record's "mart_order_number" value
 * @method TrdOrder setMartOrderTime()         Sets the current record's "mart_order_time" value
 * @method TrdOrder setMartExpressNumber()     Sets the current record's "mart_express_number" value
 * @method TrdOrder setMartExpressTime()       Sets the current record's "mart_express_time" value
 * @method TrdOrder setDomesticExpressType()   Sets the current record's "domestic_express_type" value
 * @method TrdOrder setDomesticOrderNumber()   Sets the current record's "domestic_order_number" value
 * @method TrdOrder setDomesticExpressTime()   Sets the current record's "domestic_express_time" value
 * @method TrdOrder setAttr()                  Sets the current record's "attr" value
 * @method TrdOrder setBusiness()              Sets the current record's "business" value
 * @method TrdOrder setBusinessAccount()       Sets the current record's "business_account" value
 * @method TrdOrder setStorageStatus()         Sets the current record's "storage_status" value
 * @method TrdOrder setExpressFee()            Sets the current record's "express_fee" value
 * @method TrdOrder setTotalPrice()            Sets the current record's "total_price" value
 * @method TrdOrder setPrice()                 Sets the current record's "price" value
 * @method TrdOrder setMarketingFee()          Sets the current record's "marketing_fee" value
 * @method TrdOrder setRefundPrice()           Sets the current record's "refund_price" value
 * @method TrdOrder setRefundExpressFee()      Sets the current record's "refund_express_fee" value
 * @method TrdOrder setRefund()                Sets the current record's "refund" value
 * @method TrdOrder setRefundRemark()          Sets the current record's "refund_remark" value
 * @method TrdOrder setOrderTime()             Sets the current record's "order_time" value
 * @method TrdOrder setStorageTime()           Sets the current record's "storage_time" value
 * @method TrdOrder setPayTime()               Sets the current record's "pay_time" value
 * @method TrdOrder setPayType()               Sets the current record's "pay_type" value
 * @method TrdOrder setRefundTime()            Sets the current record's "refund_time" value
 * @method TrdOrder setStatus()                Sets the current record's "status" value
 * @method TrdOrder setOperationsStatus()      Sets the current record's "operations_status" value
 * @method TrdOrder setIsPluginAdded()         Sets the current record's "is_plugin_added" value
 * @method TrdOrder setPayStatus()             Sets the current record's "pay_status" value
 * @method TrdOrder setSource()                Sets the current record's "source" value
 * @method TrdOrder setChannel()               Sets the current record's "channel" value
 * @method TrdOrder setHupuUid()               Sets the current record's "hupu_uid" value
 * @method TrdOrder setHupuUsername()          Sets the current record's "hupu_username" value
 * @method TrdOrder setGrantUid()              Sets the current record's "grant_uid" value
 * @method TrdOrder setGrantUsername()         Sets the current record's "grant_username" value
 * @method TrdOrder setGrabOrderTime()         Sets the current record's "grab_order_time" value
 * @method TrdOrder setFinishOrderTime()       Sets the current record's "finish_order_time" value
 * @method TrdOrder setForecast()              Sets the current record's "forecast" value
 * @method TrdOrder setIsComment()             Sets the current record's "is_comment" value
 * @method TrdOrder setDeliveryType()          Sets the current record's "delivery_type" value
 * @method TrdOrder setMobile()                Sets the current record's "mobile" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdOrder extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_order');
        $this->hasColumn('id', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 10,
             ));
        $this->hasColumn('order_number', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 10,
             ));
        $this->hasColumn('ibilling_number', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('title', 'string', 200, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             'length' => 200,
             ));
        $this->hasColumn('product_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('gid', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('goods_id', 'string', 30, array(
             'type' => 'string',
             'length' => 30,
             ));
        $this->hasColumn('mart_order_number', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('mart_order_time', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('mart_express_number', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('mart_express_time', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('domestic_express_type', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('domestic_order_number', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('domestic_express_time', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             ));
        $this->hasColumn('attr', 'string', 500, array(
             'type' => 'string',
             'length' => 500,
             ));
        $this->hasColumn('business', 'string', 30, array(
             'type' => 'string',
             'length' => 30,
             ));
        $this->hasColumn('business_account', 'string', 30, array(
             'type' => 'string',
             'length' => 30,
             ));
        $this->hasColumn('storage_status', 'integer', 2, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 2,
             ));
        $this->hasColumn('express_fee', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
             ));
        $this->hasColumn('total_price', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
             ));
        $this->hasColumn('price', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
             ));
        $this->hasColumn('marketing_fee', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
             ));
        $this->hasColumn('refund_price', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
             ));
        $this->hasColumn('refund_express_fee', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
             ));
        $this->hasColumn('refund', 'float', 8, array(
             'type' => 'float',
             'length' => 8,
             'scale' => 2,
             ));
        $this->hasColumn('refund_remark', 'string', 200, array(
             'type' => 'string',
             'length' => 200,
             ));
        $this->hasColumn('order_time', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('storage_time', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('pay_time', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('pay_type', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('refund_time', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('operations_status', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('is_plugin_added', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('pay_status', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('source', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('channel', 'string', 20, array(
             'type' => 'string',
             'length' => 20,
             ));
        $this->hasColumn('hupu_uid', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('hupu_username', 'string', 30, array(
             'type' => 'string',
             'length' => 30,
             ));
        $this->hasColumn('grant_uid', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('grant_username', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('grab_order_time', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             ));
        $this->hasColumn('finish_order_time', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             ));
        $this->hasColumn('forecast', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('is_comment', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('delivery_type', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('mobile', 'string', 16, array(
             'type' => 'string',
             'length' => 16,
             ));


        $this->index('order_number', array(
             'fields' => 
             array(
              0 => 'order_number',
             ),
             ));
        $this->index('hupu_uid', array(
             'fields' => 
             array(
              0 => 'hupu_uid',
             ),
             ));
        $this->index('hupu_username', array(
             'fields' => 
             array(
              0 => 'hupu_username',
             ),
             ));
        $this->index('goods_id', array(
             'fields' => 
             array(
              0 => 'goods_id',
             ),
             ));
        $this->index('mart_order_number', array(
             'fields' => 
             array(
              0 => 'mart_order_number',
             ),
             ));
        $this->index('mart_order_time', array(
             'fields' => 
             array(
              0 => 'mart_order_time',
             ),
             ));
        $this->index('mart_express_number', array(
             'fields' => 
             array(
              0 => 'mart_express_number',
             ),
             ));
        $this->index('mart_express_time', array(
             'fields' => 
             array(
              0 => 'mart_express_time',
             ),
             ));
        $this->index('business_account', array(
             'fields' => 
             array(
              0 => 'business_account',
             ),
             ));
        $this->index('order_time', array(
             'fields' => 
             array(
              0 => 'order_time',
             ),
             ));
        $this->index('status', array(
             'fields' => 
             array(
              0 => 'status',
             ),
             ));
        $this->index('mobile', array(
             'fields' => 
             array(
              0 => 'mobile',
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