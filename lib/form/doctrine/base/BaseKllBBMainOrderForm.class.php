<?php

/**
 * KllBBMainOrder form base class.
 *
 * @method KllBBMainOrder getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllBBMainOrderForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'order_number'        => new sfWidgetFormInputText(),
      'origin_order_number' => new sfWidgetFormInputText(),
      'total_price'         => new sfWidgetFormInputText(),
      'push_price'          => new sfWidgetFormInputText(),
      'real_price'          => new sfWidgetFormInputText(),
      'express_fee'         => new sfWidgetFormInputText(),
      'duty_fee'            => new sfWidgetFormInputText(),
      'coupon_fee'          => new sfWidgetFormInputText(),
      'pay_status'          => new sfWidgetFormInputText(),
      'pay_type'            => new sfWidgetFormInputText(),
      'pay_time'            => new sfWidgetFormInputText(),
      'uid'                 => new sfWidgetFormInputText(),
      'status'              => new sfWidgetFormInputText(),
      'logistic_type'       => new sfWidgetFormInputText(),
      'logistic_number'     => new sfWidgetFormInputText(),
      'flow_number'         => new sfWidgetFormInputText(),
      'payer'               => new sfWidgetFormInputText(),
      'source'              => new sfWidgetFormInputText(),
      'batch'               => new sfWidgetFormInputText(),
      'syn_api'             => new sfWidgetFormInputText(),
      'count'               => new sfWidgetFormInputText(),
      'audit_time'          => new sfWidgetFormInputText(),
      'creat_time'          => new sfWidgetFormInputText(),
      'update_time'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number'        => new sfValidatorPass(array('required' => false)),
      'origin_order_number' => new sfValidatorPass(array('required' => false)),
      'total_price'         => new sfValidatorNumber(array('required' => false)),
      'push_price'          => new sfValidatorNumber(array('required' => false)),
      'real_price'          => new sfValidatorNumber(array('required' => false)),
      'express_fee'         => new sfValidatorNumber(array('required' => false)),
      'duty_fee'            => new sfValidatorNumber(array('required' => false)),
      'coupon_fee'          => new sfValidatorNumber(array('required' => false)),
      'pay_status'          => new sfValidatorPass(array('required' => false)),
      'pay_type'            => new sfValidatorPass(array('required' => false)),
      'pay_time'            => new sfValidatorInteger(array('required' => false)),
      'uid'                 => new sfValidatorInteger(array('required' => false)),
      'status'              => new sfValidatorPass(array('required' => false)),
      'logistic_type'       => new sfValidatorPass(array('required' => false)),
      'logistic_number'     => new sfValidatorPass(array('required' => false)),
      'flow_number'         => new sfValidatorPass(array('required' => false)),
      'payer'               => new sfValidatorPass(array('required' => false)),
      'source'              => new sfValidatorPass(array('required' => false)),
      'batch'               => new sfValidatorPass(array('required' => false)),
      'syn_api'             => new sfValidatorPass(array('required' => false)),
      'count'               => new sfValidatorInteger(array('required' => false)),
      'audit_time'          => new sfValidatorInteger(array('required' => false)),
      'creat_time'          => new sfValidatorInteger(array('required' => false)),
      'update_time'         => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_bb_main_order[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllBBMainOrder';
  }

}
