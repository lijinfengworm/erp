<?php

/**
 * KllWxpayReturnLog form base class.
 *
 * @method KllWxpayReturnLog getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllWxpayReturnLogForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'order_number'   => new sfWidgetFormInputText(),
      'bank_type'      => new sfWidgetFormInputText(),
      'fee_type'       => new sfWidgetFormInputText(),
      'trade_type'     => new sfWidgetFormInputText(),
      'ct_time'        => new sfWidgetFormDateTime(),
      'transaction_id' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number'   => new sfValidatorPass(array('required' => false)),
      'bank_type'      => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'fee_type'       => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'trade_type'     => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'ct_time'        => new sfValidatorDateTime(array('required' => false)),
      'transaction_id' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_wxpay_return_log[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllWxpayReturnLog';
  }

}
