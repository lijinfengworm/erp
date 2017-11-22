<?php

/**
 * TrdShaiwuActivity form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdShaiwuActivityForm extends BaseTrdShaiwuActivityForm
{
  public function configure()
  {
      unset($this['num']);
      unset($this['updated_at']);
      unset($this['created_at']);
      # 标题
      $this->setWidget('title', new sfWidgetFormInput(array(), array('size' => 20, 'maxlength' => 20)));
      $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 15, 'min_length' => 5), array('required' => '标题必填',  'max_length' => '标题不大于15个字', 'min_length' => '标题不少于5个字')));
      # 内容
      $this->setWidget('content',new tradeWidgetFormUeditor(array('button_widget'=>true)));
      $this->setValidator('content', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '内容必填')));
      # 上传图片
      $rule = array(
          'required'=>true,
          'max_size'=>'500000',
          //    'height'=>400,
          //    'width'=>400,
          'path'=>'uploads/trade/shaiwu/activity',
       //   'ratio'=>'1x1'
      );
      $this->setWidget('upload_path',new tradeWidgetFormKupload(array("callback"=>"displayImage(data.url);","rule"=>$rule)));
      $this->setWidget('pic', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 300)));
      $this->setValidator('pic', new sfValidatorUrl(array('required' => true, 'trim' => true), array('required' => '图片必填', 'invalid' => '图片必填')));
      # 开始时间
      $this->setWidget('stime', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
      $this->setValidator('stime', new sfValidatorString(array('required' => true)));
      $this->setDefault('stime', date('Y-m-d H:i:s'));
      # 结束时间
      $this->setWidget('etime', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
      $this->setValidator('etime', new sfValidatorString(array('required' => true)));
      $this->setDefault('etime', date('Y-m-d H:i:s'));
      # 回调
      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );
  }
    public function myCallback($validator, $values)
    {
        $values['stime'] = strtotime($values['stime']);
        $values['etime'] = strtotime($values['etime']);

        if($values['stime']>=$values['etime'])
        {
            throw new sfValidatorError($validator, '开始时间不能大于等于结束时间');
        }
        return $values;
    }
}
