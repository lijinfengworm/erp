<?php

/**
 * TrdHaitaoBlacklist form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdHaitaoBlacklistForm extends BaseTrdHaitaoBlacklistForm
{
  public function configure()
  {
      # 姓名
      $this->setWidget('name', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 20)));
      $this->setValidator('name', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 20), array('required' => '名称必填',  'max_length' => '姓名不大于20个字')));
      # 身份证
      $this->setWidget('number', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 20)));
      $this->setValidator('number', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 20), array('required' => '身份证必填',  'max_length' => '身份证不大于20个字')));
      # 身份证
      $this->setWidget('note', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 100)));
      $this->setValidator('note', new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 100), array('required' => '备注必填',  'max_length' => '备注不大于100个字')));


  }
}
