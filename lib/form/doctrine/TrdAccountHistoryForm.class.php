<?php

/**
 * TrdAccountHistory form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdAccountHistoryForm extends BaseTrdAccountHistoryForm
{
  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);
      $this->setWidget('explanation', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 100)));
      $this->setValidator('explanation', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 20, 'min_length' => 3), array('required' => '备注必填',  'max_length' => '备注不大于40个字', 'min_length' => '备注不少于3个字')));
      $this->setWidget('type', new sfWidgetFormChoice(array('choices'=>array('0'=>'增加积分','1'=>'扣减积分','2'=>'增加金币','3'=>'扣减金币'))));
      $this->setValidator('type', new sfValidatorChoice(array('choices'=>array('0'=>'0','1'=>'1','2'=>'2','3'=>'3'))));
      $this->setWidget('category', new sfWidgetFormChoice(array('choices'=>array('0'=>'爆料','1'=>'晒单','2'=>'其他'))));
      $this->setValidator('category', new sfValidatorChoice(array('choices'=>array('0'=>'0','1'=>'1','2'=>'2'))));
      
      $this->setWidget('hupu_uid', new sfWidgetFormInputHidden());
      $this->setWidget('hupu_username', new sfWidgetFormInputHidden());
      $this->setWidget('grant_uid', new sfWidgetFormInputHidden());
      $this->setWidget('grant_username', new sfWidgetFormInputHidden());
      $this->widgetSchema->setHelps(array(
            'integral' => '增加或者要扣减的积分或金币',
        ));
  }
  public function processValues($values) {
        if ($values['type'] == 0 || $values['type'] == 1){
            $values['integral'] = $values['integral'];
        } else if ($values['type'] == 2 || $values['type'] == 3){
            $values['gold'] = $values['integral'];
            $values['integral'] = 0;
        } 
        $uid = (sfContext::getInstance()->getConfiguration()->getApplication() == 'backend' ? '' : '-' ) . sfContext::getInstance()->getUser()->getAttribute('uid');
        $username = sfContext::getInstance()->getUser()->getAttribute('username');
        if ($this->isNew()) {
            $values['grant_uid'] = $uid;
            $values['grant_username'] = $username;
        }
        return $values;
    }
}
