<?php

/**
 * TrdOrder form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdOrderForm extends BaseTrdOrderForm
{
  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['news_id']);
      unset($this['is_plugin_added']);
      
      
        //修改订单状态
        $status_array = array(
            0=>'订单生成',
            1=>'识货下单',
            2=>'订单完成',
            3=>'待退货',
            4=>'已退货',
            5=>'订单关闭',
            6=>'用户取消',
            7=>'识货取消'
        );
        $paystatus_array = array(
            0=>'待付款',
            1=>'已支付',
            2=>'待退款',
            3=>'退款中',
            4=>'退款完成',
            5=>'退款失败',
        );
      $this->setWidget('status', new sfWidgetFormChoice(array('choices'=>$status_array)));//所属商城
      $this->setValidator('status', new sfValidatorChoice(array('choices'=>array_keys($status_array),'required' => false)));//验证
      $this->setWidget('pay_status', new sfWidgetFormChoice(array('choices'=>$paystatus_array)));//所属商城
      $this->setValidator('pay_status', new sfValidatorChoice(array('choices'=>array_keys($paystatus_array),'required' => false)));//验证
  }
}
