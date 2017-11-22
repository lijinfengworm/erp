<?php

/**
 * TrdHaitaoRefundLog form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdHaitaoRefundLogForm extends BaseTrdHaitaoRefundLogForm
{
  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);
      if ($this->isNew()) unset($this['callback_attr']);
      unset($this['grant_uid']);
      unset($this['grant_username']);
      unset($this['status']);
      $this->setWidget('title', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 100)));
      $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '退款标题不可为空')));
      $this->setWidget('order_number_attr', new sfWidgetFormTextarea(array(), array('cols' => 60, 'rows' => 20)));
      $this->setValidator('order_number_attr', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '退款信息必填')));
  
      $this->widgetSchema->setHelp('order_number_attr','格式：<span style="color: red">ibilling订单号,退款金额,订单号,手机号（订单号和手机号可不填，如填写退款成功后会发送手机短信）,例如：<br><span style="color: red">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SH20141023232131232,20,1501187069722026,15800867003<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SH20141023232131231,20,1501187069722026,15800867003</span>');
  }
  
  public function processValues($values) {
        $uid = sfContext::getInstance()->getUser()->getAttribute('uid');
        $username = sfContext::getInstance()->getUser()->getAttribute('username');
        if ($this->isNew()) {
            $values['grant_uid'] = $uid;
            $values['grant_username'] = $username;
        }
        return $values;
    }
}
