<?php

/**
 * KaluliMainOrder form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KaluliMainOrderForm extends BaseKaluliMainOrderForm
{
    /*  labels  */
    private $_labels = array(
        'order_number'=>'订单号',
        'ibilling_number'=>'ibilling号',
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
        'number'=>'数量',
        'refund'=>'退款金额',
    );




  public function configure()
  {

      unset($this['updated_at']);
      unset($this['created_at']);
      $this->widgetSchema->setLabels($this->_labels);



  }
}
