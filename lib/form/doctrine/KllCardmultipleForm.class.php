<?php

/**
 * KllCardmultiple form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllCardmultipleForm extends BaseKllCardmultipleForm
{

    private  $_status = array(
        1 => '正常',
        2 => '下线'
    );

  public function configure() {
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['hupu_uid']);
      unset($this['is_success']);
      unset($this['hupu_username']);
      unset($this['read_number']);
      unset($this['is_alert']);


      $this->setWidget("status", new sfWidgetFormChoice(array('expanded' => true,"choices" => $this->_status,'default'=>1),array('class'=>' radio')));
      $this->setDefault('status',1);
      $this->setValidator('status', new sfValidatorChoice(array('choices'=>array_keys($this->_status), 'required' => true), array('invalid' => '请选择状态', 'required'=>'请选择状态')));



      if($this->isNew()) {
          $this->setWidget('code', new sfWidgetFormInput(array(), array('class'=>'w180')));
      } else {
          $this->setWidget('code', new sfWidgetFormInput(array(), array('class'=>'w180 gwyy_btn btn-disabled disabled',"readonly"=>"readonly")));
      }

      $this->setValidator('code', new sfValidatorString(array('required' => true, 'trim' => true,'min_length'=>6, 'max_length' => 6), array('required' => '6位唯一码必填！', 'max_length'=>'必须是6个字符','min_length'=>'必须是6个字符' ,'invalid' => '唯一码最大不得超过6个字')));


      $this->setWidget('title', new sfWidgetFormInput(array(), array('class'=>'w180 ')));
      $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 20), array('required' => '标题必填！', 'invalid' => '标题最大不得超过20个字')));


      $this->setWidget('cardware_code', new sfWidgetFormInput(array(), array('class'=>'w180 ')));
      $this->setValidator('cardware_code', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 20), array('required' => '必填呀，骚年！', 'invalid' => '不得超过20个字')));



      $this->widgetSchema->setHelps(array(
          'code' => '<span class="c-999">请填写活动唯一码，只能是6位数字或者字母。</span>',
          'cardware_code' => '<button id="check_card" type="button" class="gwyy_btn">检查卡卷包</button>',
      ));


      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );





      $this->widgetSchema->setLabels(array(
          'code' => '活动唯一码',
          'title' => '申请标题',
          'cardware_code' => '卡卷包code',
          'etime' => '结束时间',
          'phone' => '联系人手机',
          'alert_num' => '警戒数量',
          'status' => '状态',
          'card_number' => '生成数量',
      ));

  }

    public function myCallback($validator, $values) {
        if ($this->isNew()) {
            $user_flag = KllCardmultipleTable::getInstance()->hasDataField('code',$values['code'],0);
        } else {
            $user_flag = KllCardmultipleTable::getInstance()->hasDataField('code',$values['code'],$values['id']);
        }
        if($user_flag) throw new sfValidatorError($validator, '唯一码已存在，请换一个！');

        // 判断生成数量
        if($values['card_number'] <= 0)  throw new sfValidatorError($validator, '生成数量不能小于1！');

        //判断卡卷包状态
        $cardWare = KllCardWareTable::getOne('code',$values['cardware_code'],'all');
        if(empty($cardWare))  throw new sfValidatorError($validator, '卡卷包不存在！');
        if($cardWare['status'] != KllCardWare::$_STATUS_OK) throw new sfValidatorError($validator, '卡卷包已下架！');
        $_redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $_redis->select(3);
        foreach($cardWare['_data'] as $k=>$v) {
            $surplus = (int)$_redis->scard($v['cache_key']);
            if($surplus <= 0)   throw new sfValidatorError($validator, '卡卷包礼品卡数量不足！');
        }
        return $values;
    }


    public function processValues($values)
    {
        $values = parent::processValues($values);
        $values['hupu_uid'] =   sfContext::getInstance()->getUser()->getTrdUserHuPuId();
        $values['hupu_username'] =   sfContext::getInstance()->getUser()->getTrdUsername();
        $values['is_success'] = 1;
        return $values;
    }

















}
