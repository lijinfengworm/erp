<?php

/**
 * TrdMainOrder form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 5009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdMainOrderForm extends BaseTrdMainOrderForm
{
  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['address_attr']);

      //收货地址
      $this->setWidget('name', new sfWidgetFormInputText(array('label'=>'收货人'),array('size' => 50)));
      $this->setWidget('postcode', new sfWidgetFormInputText(array('label'=>'邮编'),array('size' => 50)));
      $this->setWidget('province', new sfWidgetFormInputText(array('label'=>'省份'),array('size' => 50)));
      $this->setWidget('city', new sfWidgetFormInputText(array('label'=>'城市'),array('size' => 50)));
      $this->setWidget('area', new sfWidgetFormInputText(array('label'=>'地区'),array('size' => 50)));
      $this->setWidget('mobile', new sfWidgetFormInputText(array('label'=>'手机号'),array('size' => 50)));
      $this->setWidget('street', new sfWidgetFormInputText(array('label'=>'街道'),array('size' => 50)));
      $this->setWidget('identity_number', new sfWidgetFormInputText(array('label'=>'身份证号'),array('size' => 50)));
      
      $this->setValidator('name', new sfValidatorString(array('max_length' => 255, 'required' => false)));
      $this->setValidator('postcode', new sfValidatorString(array('max_length' => 255, 'required' => false)));
      $this->setValidator('province', new sfValidatorString(array('max_length' => 255, 'required' => false)));
      $this->setValidator('city', new sfValidatorString(array('max_length' => 255, 'required' => false)));
      $this->setValidator('area', new sfValidatorString(array('max_length' => 255, 'required' => false)));
      $this->setValidator('mobile', new sfValidatorString(array('max_length' => 255, 'required' => false)));
      $this->setValidator('street', new sfValidatorString(array('max_length' => 255, 'required' => false)));
      $this->setValidator('identity_number', new sfValidatorString(array('max_length' => 255, 'required' => false)));
      

  }
}
