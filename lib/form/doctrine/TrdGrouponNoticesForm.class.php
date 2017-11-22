<?php

/**
 * TrdGrouponNotices form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdGrouponNoticesForm extends BaseTrdGrouponNoticesForm
{
  public static $status = array(
      '0'=>'否',
      '1'=>'是',
  );
  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);


      # 标题
      $this->setWidget('title', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 40)));
      $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 40), array('required' => '标题必填',  'max_length' => '标题不大于40个字', 'min_length' => '标题不少于8个字')));

      # 内容
      $this->setWidget('content',new sfWidgetFormTextarea());
      $this->setValidator('content', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '内容必填')));


      # 状态
      $this->setWidget('status', new sfWidgetFormChoice(array('choices'=>self::$status)));
      $this->setValidator('status', new sfValidatorChoice(array('choices'=>array_keys(self::$status),'required' => true)));//验证

  }
}
