<?php

/**
 * TrdBusinessman form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdBusinessmanForm extends BaseTrdBusinessmanForm
{
  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);

      # 用户名
      $this->setWidget('username', new sfWidgetFormInput(array(), array('size' => 20, 'maxlength' => 30)));
      $this->setValidator('username', new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 30, ), array('required' => '必填',  'max_length' => '不大于30个字', 'min_length' => '不少于5个字')));
      # uid
      $this->setWidget('hupu_uid', new sfWidgetFormInput(array(), array('size' => 20, 'maxlength' => 20)));
      $this->setValidator('hupu_uid', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 20, ), array('required' => '必填',  'max_length' => '不大于20个字', 'min_length' => '不少于5个字')));
      # 虎扑名
      $this->setWidget('hupu_username', new sfWidgetFormInput(array(), array('size' => 20, 'maxlength' => 30)));
      $this->setValidator('hupu_username', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 30, ), array('required' => '必填',  'max_length' => '不大于30个字', 'min_length' => '不少于5个字')));
      # 手机号
      $this->setWidget('phone', new sfWidgetFormInput(array(), array('size' => 20, 'maxlength' => 20)));
      # 邮箱
      $this->setWidget('email', new sfWidgetFormInput(array(), array('size' => 20, 'maxlength' => 30)));
      # QQ
      $this->setWidget('qq', new sfWidgetFormInput(array(), array('size' => 20, 'maxlength' => 20)));
      # 店铺URL
      $this->setWidget('shop_url', new sfWidgetFormInput(array(), array('size' => 20, 'maxlength' => 100)));
      # 店铺名
      $this->setWidget('shop_name', new sfWidgetFormInput(array(), array('size' => 20, 'maxlength' => 20)));
      # 旺旺号
      $this->setWidget('wanwan', new sfWidgetFormInput(array(), array('size' => 20, 'maxlength' => 20)));
      # 是否加入联盟
      $this->setWidget('alliance', new sfWidgetFormChoice(array('choices'=>array(0=>'否',1=>'是'))));
      # trdNO
      $this->setWidget('alliance_trdno', new sfWidgetFormInput(array(), array('size' => 20, 'maxlength' => 50)));
  }
}
