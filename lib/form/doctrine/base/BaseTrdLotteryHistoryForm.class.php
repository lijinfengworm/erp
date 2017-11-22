<?php

/**
 * TrdLotteryHistory form base class.
 *
 * @method TrdLotteryHistory getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdLotteryHistoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'lottery_id' => new sfWidgetFormInputText(),
      'user_id'    => new sfWidgetFormInputText(),
      'phone'      => new sfWidgetFormInputText(),
      'prize_id'   => new sfWidgetFormInputText(),
      'prize_name' => new sfWidgetFormInputText(),
      'is_virtual' => new sfWidgetFormInputText(),
      'card'       => new sfWidgetFormInputText(),
      'ip'         => new sfWidgetFormInputText(),
      'source'     => new sfWidgetFormInputText(),
      'status'     => new sfWidgetFormInputText(),
      'is_send'    => new sfWidgetFormInputText(),
      'address'    => new sfWidgetFormTextarea(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'lottery_id' => new sfValidatorInteger(array('required' => false)),
      'user_id'    => new sfValidatorInteger(array('required' => false)),
      'phone'      => new sfValidatorString(array('max_length' => 15, 'required' => false)),
      'prize_id'   => new sfValidatorInteger(array('required' => false)),
      'prize_name' => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'is_virtual' => new sfValidatorInteger(array('required' => false)),
      'card'       => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'ip'         => new sfValidatorString(array('max_length' => 15, 'required' => false)),
      'source'     => new sfValidatorString(array('max_length' => 12, 'required' => false)),
      'status'     => new sfValidatorInteger(array('required' => false)),
      'is_send'    => new sfValidatorInteger(array('required' => false)),
      'address'    => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_lottery_history[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdLotteryHistory';
  }

}
