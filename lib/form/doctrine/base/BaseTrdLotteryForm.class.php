<?php

/**
 * TrdLottery form base class.
 *
 * @method TrdLottery getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdLotteryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'lottery_name'     => new sfWidgetFormInputText(),
      'lottery_desc'     => new sfWidgetFormTextarea(),
      'max_rand'         => new sfWidgetFormInputText(),
      'is_must'          => new sfWidgetFormInputText(),
      'fail_msg'         => new sfWidgetFormInputText(),
      'lottery_num_type' => new sfWidgetFormInputText(),
      'user_lottery_num' => new sfWidgetFormInputText(),
      'attr_lottery_num' => new sfWidgetFormInputText(),
      'start_time'       => new sfWidgetFormInputText(),
      'end_time'         => new sfWidgetFormInputText(),
      'bg_img'           => new sfWidgetFormInputText(),
      'pointer_img'      => new sfWidgetFormInputText(),
      'round_img'        => new sfWidgetFormInputText(),
      'status'           => new sfWidgetFormInputText(),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'lottery_name'     => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'lottery_desc'     => new sfValidatorString(array('max_length' => 5000, 'required' => false)),
      'max_rand'         => new sfValidatorInteger(array('required' => false)),
      'is_must'          => new sfValidatorInteger(array('required' => false)),
      'fail_msg'         => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'lottery_num_type' => new sfValidatorInteger(array('required' => false)),
      'user_lottery_num' => new sfValidatorInteger(array('required' => false)),
      'attr_lottery_num' => new sfValidatorInteger(array('required' => false)),
      'start_time'       => new sfValidatorInteger(array('required' => false)),
      'end_time'         => new sfValidatorInteger(array('required' => false)),
      'bg_img'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'pointer_img'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'round_img'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'status'           => new sfValidatorInteger(array('required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_lottery[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdLottery';
  }

}
