<?php

/**
 * TrdLipinka form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdLipinkaForm extends BaseTrdLipinkaForm
{

    //生成类型
    public static $_type = array(
        1=> '生成卡号',
        2=>'发到账户'
    );

    //选择活动
    public static $_activity_type = array(
        1 => '推广活动',
        2 => '运营活动',
        3 => '客户抚慰',
    );

    //审核状态
    public static $_STATUS_OK_TYPE  = 2;
    public static $_STATUS_FAIL_TYPE =  3;
    public static $_STATUS_WAIT_TYPE =  1;







  public function configure() {
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['is_delete']);

      unset($this['apply_user_id']);
      unset($this['amount']);
      unset($this['verify_user_id']);
      unset($this['status']);
      unset($this['stime']);
      unset($this['etime']);
      $this->disableLocalCSRFProtection();




      $this->setWidget('title', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator('title',
          new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 64),
              array('required' => '名称必填！',  'max_length' => '不大于64个字')));

      $this->setWidget('type', new sfWidgetFormChoice(array('expanded' => true, "choices" => self::$_type),array('class'=>'lipinka_type radio')));
      if($this->isNew()) {
          $this->setValidator('type', new sfValidatorChoice(
              array('choices' => array_keys(self::$_type)), array('required' => '必填')));
      }
      if($this->isNew()&& $this->getOption('_type')) {
          $this->setDefault('type',$this->getOption('_type'));
      }



      $this->setWidget('activity_type', new sfWidgetFormChoice(array( "choices" => self::$_activity_type)));
      $this->setValidator('activity_type', new sfValidatorChoice(
          array('choices'=>array_keys(self::$_activity_type)),array('required' => '必填')));


      $this->setWidget('for_what', new sfWidgetFormInput(array(), array('class'=>'w460')));
      $this->setValidator('for_what',
          new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 255),
              array('required' => '申请理由必填！',  'max_length' => '不大于255个字')));




      $this->widgetSchema->setLabels(array(
          'title' => '申请标题',
          'type' => '申请类型',
          'activity_type' => '活动类型',
          'for_what' => '申请理由',
          'status' => '状态',
      ));

      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );
  }

    public function myCallback($validator, $values) {
        $_attr_name = $this->getOption('attr_name');
        if(!empty($_attr_name)) {
            $_attr = sfContext::getInstance()->getRequest()->getParameter($_attr_name);
            if(empty($_attr)) throw new sfValidatorError($validator, '请先添加一条记录！');
        }
        return $values;
    }





    public function processValues($values) {

        if($this->isNew()) {
            $values['apply_user_id'] = sfContext::getInstance()->getUser()->getTrdUserHuPuId();
            $values['status'] = 1;
        } else {
            $values['type'] = $this->getObject()->getType();
        }



        return  $values;
    }












}
