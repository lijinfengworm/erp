<?php

/**
 * KaluliOrder form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KaluliOrderForm extends BaseKaluliOrderForm
{

    /*  labels  */
    private $_labels = array(
        'order_number'=>'订单号',
        'ibilling_number'=>'ibilling号',
        'title'=>'标题',
        'product_id'=>'主订单ID',
        'goods_id'=>'商品ID',
        'domestic_express_type'=>'快递类型',
        'domestic_order_number'=>'快递单号',
        'domestic_express_time'=>'发货时间',
        'depot_type'=>'商品仓库',
        'express_fee'=>'运费',
        'total_price'=>'总价',
        'price'=>'商品价格',
        'order_time'=>'订单时间',
        'pay_time'=>'支付时间',
        'status'=>'订单状态',
        'pay_status'=>'付款状态',
        'source'=>'来源',
        'hupu_uid'=>'虎扑id',
        'hupu_username'=>'虎扑用户名',
        'is_comment'=>'是否评论',
    );

  public function configure(){
      unset($this['updated_at']);
      unset($this['created_at']);


      $this->widgetSchema->setLabels($this->_labels);



      $this->setWidget('status', new sfWidgetFormChoice(array('choices'=>KaluliOrderTable::$order_status)));//订单状态
      $this->setValidator('status', new sfValidatorChoice(array('choices'=>array_keys(KaluliOrderTable::$order_status),'required' => false)));//验证
      $this->setWidget('pay_status', new sfWidgetFormChoice(array('choices'=>KaluliOrderTable::$order_pay_status)));//付款状态
      $this->setValidator('pay_status', new sfValidatorChoice(array('choices'=>array_keys(KaluliOrderTable::$order_pay_status),'required' => false)));//验证


  }
}
