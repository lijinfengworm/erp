<?php

/**
 * TrdPayOrder form base class.
 *
 * @method TrdPayOrder getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdPayOrderForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'sign'               => new sfWidgetFormInputText(),
      'pay_uid'            => new sfWidgetFormInputText(),
      'title'              => new sfWidgetFormInputText(),
      'desc'               => new sfWidgetFormInputText(),
      'amount'             => new sfWidgetFormInputText(),
      'callback_url'       => new sfWidgetFormInputText(),
      'notify_url'         => new sfWidgetFormInputText(),
      'yzm'                => new sfWidgetFormInputText(),
      'order_no'           => new sfWidgetFormInputText(),
      'pay_type'           => new sfWidgetFormInputText(),
      'is_pay'             => new sfWidgetFormInputText(),
      'callback_error_num' => new sfWidgetFormInputText(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'sign'               => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'pay_uid'            => new sfValidatorInteger(array('required' => false)),
      'title'              => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'desc'               => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'amount'             => new sfValidatorNumber(array('required' => false)),
      'callback_url'       => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'notify_url'         => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'yzm'                => new sfValidatorString(array('max_length' => 5, 'required' => false)),
      'order_no'           => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'pay_type'           => new sfValidatorInteger(array('required' => false)),
      'is_pay'             => new sfValidatorInteger(array('required' => false)),
      'callback_error_num' => new sfValidatorInteger(array('required' => false)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_pay_order[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdPayOrder';
  }

}
