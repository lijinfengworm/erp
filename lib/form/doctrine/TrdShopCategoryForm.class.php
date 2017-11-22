<?php

/**
 * TrdShopCategory form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdShopCategoryForm extends BaseTrdShopCategoryForm
{
  public function configure()
  {
      unset($this["created_at"]);
      unset($this["updated_at"]);

      #  名称
      $this->setWidget('name', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 100)));
      $this->setValidator('name', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 40, ), array('required' => '标题必填',  'max_length' => '标题不大于40个字', 'min_length' => '标题不少于8个字')));

      # 状态显示
      $this->setWidget("status", new sfWidgetFormChoice(array('expanded' => true,"choices" => array(0=>'开启',1=>'未开启'),'default'=>1),array('class'=>'type_status radio')));
      $this->setValidator('status', new sfValidatorChoice(array('choices'=>array_keys(array(0=>'开启',1=>'未开启')), 'required' => true), array('invalid' => '请设置是状态', 'required'=>'请设置是状态')));
  }
}
