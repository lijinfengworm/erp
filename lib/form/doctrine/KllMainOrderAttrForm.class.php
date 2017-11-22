<?php

/**
 * KllMainOrderAttr form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllMainOrderAttrForm extends BaseKllMainOrderAttrForm
{
	public function __construct($init_data){
        parent::__construct($init_data);
    }
  	public function configure()
  	{
  		$this->setWidget('receiver', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator('receiver', new sfValidatorString(array('required' => true, 'trim' => true),array('required' => '收件人不能为空')));
  		$this->setWidget('mobile', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator('mobile', new sfValidatorNumber(array('required' => true, 'trim' => true),array('required' => '必须是数字')));
      $this->setWidget('card_type', new sfWidgetFormInput(array(), array('class'=>'w180')));
  		$this->setWidget('card_code', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator('card_code', new sfValidatorString(array('required' => true, 'trim' => true),array('required' => '身份证不能为空')));
  		$this->setWidget('province', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator('province', new sfValidatorString(array('required' => true, 'trim' => true),array('required' => '省份不能为空')));
  		$this->setWidget('city', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator('receiver', new sfValidatorString(array('required' => true, 'trim' => true),array('required' => '城市不能为空')));
  		$this->setWidget('area', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator('area', new sfValidatorString(array('required' => true, 'trim' => true),array('required' => '地区不能为空')));
  		$this->setWidget('postal_code', new sfWidgetFormInput(array(), array('class'=>'w180')));
  		$this->setWidget('address', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator('address', new sfValidatorString(array('required' => true, 'trim' => true),array('required' => '地址不能为空')));
  		$this->setWidget('order_number', new sfWidgetFormInput(array(), array('class'=>'w180')));
  	}

}
