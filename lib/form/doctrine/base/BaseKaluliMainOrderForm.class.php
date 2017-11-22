<?php

/**
 * KaluliMainOrder form base class.
 *
 * @method KaluliMainOrder getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKaluliMainOrderForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'order_number'    => new sfWidgetFormInputText(),
      'ibilling_number' => new sfWidgetFormInputText(),
      'hupu_uid'        => new sfWidgetFormInputText(),
      'hupu_username'   => new sfWidgetFormInputText(),
      'express_fee'     => new sfWidgetFormInputText(),
      'total_price'     => new sfWidgetFormInputText(),
      'original_price'  => new sfWidgetFormInputText(),
      'marketing_fee'   => new sfWidgetFormInputText(),
      'coupon_fee'      => new sfWidgetFormInputText(),
      'duty_fee'        => new sfWidgetFormInputText(),
      'number'          => new sfWidgetFormInputText(),
      'order_time'      => new sfWidgetFormInputText(),
      'pay_time'        => new sfWidgetFormInputText(),
      'pay_type'        => new sfWidgetFormInputText(),
      'refund'          => new sfWidgetFormInputText(),
      'source'          => new sfWidgetFormInputText(),
      'status'          => new sfWidgetFormInputText(),
      'trade_no'        => new sfWidgetFormInputText(),
      'is_activity'     => new sfWidgetFormInputText(),
      'finance_audit'   => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number'    => new sfValidatorInteger(array('required' => false)),
      'ibilling_number' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'hupu_uid'        => new sfValidatorInteger(array('required' => false)),
      'hupu_username'   => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'express_fee'     => new sfValidatorNumber(array('required' => false)),
      'total_price'     => new sfValidatorNumber(array('required' => false)),
      'original_price'  => new sfValidatorNumber(array('required' => false)),
      'marketing_fee'   => new sfValidatorNumber(array('required' => false)),
      'coupon_fee'      => new sfValidatorNumber(array('required' => false)),
      'duty_fee'        => new sfValidatorNumber(array('required' => false)),
      'number'          => new sfValidatorInteger(array('required' => false)),
      'order_time'      => new sfValidatorPass(array('required' => false)),
      'pay_time'        => new sfValidatorPass(array('required' => false)),
      'pay_type'        => new sfValidatorInteger(array('required' => false)),
      'refund'          => new sfValidatorNumber(array('required' => false)),
      'source'          => new sfValidatorInteger(array('required' => false)),
      'status'          => new sfValidatorInteger(array('required' => false)),
      'trade_no'        => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'is_activity'     => new sfValidatorInteger(array('required' => false)),
      'finance_audit'   => new sfValidatorPass(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'KaluliMainOrder', 'column' => array('order_number'))),
        new sfValidatorDoctrineUnique(array('model' => 'KaluliMainOrder', 'column' => array('ibilling_number'))),
      ))
    );

    $this->widgetSchema->setNameFormat('kaluli_main_order[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliMainOrder';
  }

}
