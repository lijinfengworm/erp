<?php

/**
 * KllSendCouponVip form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllSendCouponVipForm extends BaseKllSendCouponVipForm
{
    public static $_state=array(
        1=>'启动',
        2=>'关闭',
    );
  public function configure()
  {
        $this->setWidget('title', new sfWidgetFormInput(array(), array('class'=>'w460')));
        $this->setValidator('title',
            new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 20),
                array('required' => '标题必填！',  'max_length' => '不大于20个字')));
        
        $this->setWidget('s_time', new sfWidgetFormInput(array(), array('class'=>'w460')));
        $this->setValidator('s_time',
            new sfValidatorString(array('required' => true, 'trim' => true),
                array('required' => '必填！')));
        $this->setWidget('e_time', new sfWidgetFormInput(array(), array('class'=>'w460')));
        $this->setValidator('e_time',
            new sfValidatorString(array('required' => true, 'trim' => true),
                array('required' => '必填！')));
        
        $this->setWidget('record_id', new sfWidgetFormInput(array(), array('name'=>'kll_send_coupon_vip[record_id][]')));
        $this->setValidator('record_id',
            new sfValidatorString(array('required' => true, 'trim' => true),
                array('required' => '批次号错误！')));
        
        $this->setWidget('state', new sfWidgetFormChoice(array('expanded' => true, "choices" => self::$_state),array('class'=>'lipinka_type radio')));
        $this->setValidator('state', new sfValidatorChoice(
            array('choices'=>array_keys(self::$_state)),array('required' => '必填')));

  }
}
