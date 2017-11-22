<?php

/**
 * TrdMainOrder form base class.
 *
 * @method TrdMainOrder getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdMainOrderForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'order_number'        => new sfWidgetFormInputText(),
      'ibilling_number'     => new sfWidgetFormInputText(),
      'hupu_uid'            => new sfWidgetFormInputText(),
      'hupu_username'       => new sfWidgetFormInputText(),
      'address'             => new sfWidgetFormTextarea(),
      'address_attr'        => new sfWidgetFormTextarea(),
      'express_fee'         => new sfWidgetFormInputText(),
      'total_price'         => new sfWidgetFormInputText(),
      'original_price'      => new sfWidgetFormInputText(),
      'coupon_fee'          => new sfWidgetFormInputText(),
      'marketing_fee'       => new sfWidgetFormInputText(),
      'number'              => new sfWidgetFormInputText(),
      'remark'              => new sfWidgetFormInputText(),
      'order_time'          => new sfWidgetFormInputText(),
      'pay_time'            => new sfWidgetFormInputText(),
      'pay_type'            => new sfWidgetFormInputText(),
      'refund'              => new sfWidgetFormInputText(),
      'handle_status'       => new sfWidgetFormInputText(),
      'source'              => new sfWidgetFormInputText(),
      'status'              => new sfWidgetFormInputText(),
      'tax_status'          => new sfWidgetFormInputText(),
      'tax'                 => new sfWidgetFormInputText(),
      'tax_ibilling_number' => new sfWidgetFormInputText(),
      'tax_time'            => new sfWidgetFormInputText(),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number'        => new sfValidatorInteger(array('required' => false)),
      'ibilling_number'     => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'hupu_uid'            => new sfValidatorInteger(array('required' => false)),
      'hupu_username'       => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'address'             => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'address_attr'        => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'express_fee'         => new sfValidatorNumber(array('required' => false)),
      'total_price'         => new sfValidatorNumber(array('required' => false)),
      'original_price'      => new sfValidatorNumber(array('required' => false)),
      'coupon_fee'          => new sfValidatorNumber(array('required' => false)),
      'marketing_fee'       => new sfValidatorNumber(array('required' => false)),
      'number'              => new sfValidatorInteger(array('required' => false)),
      'remark'              => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'order_time'          => new sfValidatorPass(array('required' => false)),
      'pay_time'            => new sfValidatorPass(array('required' => false)),
      'pay_type'            => new sfValidatorInteger(array('required' => false)),
      'refund'              => new sfValidatorNumber(array('required' => false)),
      'handle_status'       => new sfValidatorInteger(array('required' => false)),
      'source'              => new sfValidatorInteger(array('required' => false)),
      'status'              => new sfValidatorInteger(array('required' => false)),
      'tax_status'          => new sfValidatorInteger(array('required' => false)),
      'tax'                 => new sfValidatorNumber(array('required' => false)),
      'tax_ibilling_number' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'tax_time'            => new sfValidatorInteger(array('required' => false)),
      'created_at'          => new sfValidatorDateTime(),
      'updated_at'          => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'TrdMainOrder', 'column' => array('order_number'))),
        new sfValidatorDoctrineUnique(array('model' => 'TrdMainOrder', 'column' => array('ibilling_number'))),
      ))
    );

    $this->widgetSchema->setNameFormat('trd_main_order[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdMainOrder';
  }

}
