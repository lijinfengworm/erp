<?php

/**
 * KllCardWare form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllCardWareForm extends BaseKllCardWareForm
{

    private  $_status = array(
        1 => '正常',
        2 => '下线'
    );



    public function configure() {
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['is_delete']);
      unset($this['hupu_uid']);
      unset($this['hupu_username']);

        if($this->isNew()) {
            $this->setWidget('code', new sfWidgetFormInput(array(), array('class'=>'w180')));
        } else {
            $this->setWidget('code', new sfWidgetFormInput(array(), array('class'=>'w180 gwyy_btn btn-disabled disabled',"readonly"=>"readonly")));
        }

        $this->setValidator('code', new sfValidatorString(array('required' => true, 'trim' => true,'min_length'=>6, 'max_length' => 6), array('required' => '6位唯一码必填！', 'max_length'=>'必须是6个字符','min_length'=>'必须是6个字符' ,'invalid' => '唯一码最大不得超过6个字')));

        $this->setWidget('title', new sfWidgetFormInput(array(), array('class'=>'w180 ')));
        $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 20), array('required' => '标题必填！', 'invalid' => '标题最大不得超过20个字')));


      $this->setWidget('stime', new sfWidgetFormInput(array(), array('class'=>'w180 J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})")));
      $this->setValidator('stime', new sfValidatorDateTime(array('required' => true, 'trim' => true, 'datetime_output' => 'Y-m-d H:i:s')));
      $this->setDefault('stime','');

      $this->setWidget('etime', new sfWidgetFormInput(array(), array('class'=>'w180 J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})")));
      $this->setValidator('etime', new sfValidatorDateTime(array('required' => true, 'trim' => true, 'datetime_output' => 'Y-m-d H:i:s')));
      $this->setDefault('etime','');


        $this->setWidget("status", new sfWidgetFormChoice(array('expanded' => true,"choices" => $this->_status,'default'=>1),array('class'=>' radio')));
        $this->setDefault('status',1);
        $this->setValidator('status', new sfValidatorChoice(array('choices'=>array_keys($this->_status), 'required' => true), array('invalid' => '请选择状态', 'required'=>'请选择状态')));




        $this->widgetSchema->setLabels(array(
          'code' => '活动唯一码',
          'title' => '申请标题',
          'stime' => '开始时间',
          'etime' => '结束时间',
          'phone' => '联系人手机',
          'status' => '状态',
      ));

        $this->widgetSchema->setHelps(array(
            'code' => '<span class="c-999">请填写活动唯一码，只能是6位数字或者字母。</span>',
        ));


        $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
        );
  }



    public function myCallback($validator, $values) {
        if ($this->isNew()) {
            $user_flag = KllCardWareTable::getInstance()->hasDataField('code',$values['code'],0);
        } else {
            $user_flag = KllCardWareTable::getInstance()->hasDataField('code',$values['code'],$values['id']);
        }
        if($user_flag) throw new sfValidatorError($validator, '唯一码已存在，请换一个！');
        return $values;
    }


    public function processValues($values)
    {
        $values = parent::processValues($values);
        $values['hupu_uid'] =   sfContext::getInstance()->getUser()->getTrdUserHuPuId();
        $values['hupu_username'] =   sfContext::getInstance()->getUser()->getTrdUsername();
        $values['stime'] = strtotime($values['stime']);
        $values['etime'] = strtotime($values['etime']);
        return $values;
    }





}
