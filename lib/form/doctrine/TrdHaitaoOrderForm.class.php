<?php

/**
 * TrdHaitaoOrder form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdHaitaoOrderForm extends BaseTrdHaitaoOrderForm
{
  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['news_id']);
      unset($this['is_plugin_added']);
      
      
        //修改订单状态
        $status_array = array(
            0=>'已创建',
            1=>'已支付',
            2=>'已下单',
            11=>'已入库',
            12=>'部分已入库',
            6=>'已发货',
            7=>'已完成',
            8=>'已取消',
            9=>'已退回'
        );
        $paystatus_array = array(
            0=>'待付款',
            1=>'已支付',
            2=>'待退款',
            3=>'退款中',
            4=>'退款完成',
        );
      $this->setWidget('status', new sfWidgetFormChoice(array('choices'=>$status_array)));//所属商城
      $this->setValidator('status', new sfValidatorChoice(array('choices'=>array_keys($status_array),'required' => false)));//验证
      $this->setWidget('pay_status', new sfWidgetFormChoice(array('choices'=>$paystatus_array)));//所属商城
      $this->setValidator('pay_status', new sfValidatorChoice(array('choices'=>array_keys($paystatus_array),'required' => false)));//验证
  }
}
