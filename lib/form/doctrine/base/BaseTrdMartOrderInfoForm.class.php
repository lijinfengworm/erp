<?php

/**
 * TrdMartOrderInfo form base class.
 *
 * @method TrdMartOrderInfo getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdMartOrderInfoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'mart_order_id'         => new sfWidgetFormInputText(),
      'account'               => new sfWidgetFormInputText(),
      'business'              => new sfWidgetFormInputText(),
      'total_price'           => new sfWidgetFormInputText(),
      'shipping_price'        => new sfWidgetFormInputText(),
      'receive_price'         => new sfWidgetFormInputText(),
      'refund_price'          => new sfWidgetFormInputText(),
      'full_name'             => new sfWidgetFormInputText(),
      'order_time'            => new sfWidgetFormInputText(),
      'sh_order_time'         => new sfWidgetFormInputText(),
      'sh_order_id'           => new sfWidgetFormInputText(),
      'sh_shipping_price'     => new sfWidgetFormInputText(),
      'sh_coupon_fee'         => new sfWidgetFormInputText(),
      'sh_price'              => new sfWidgetFormInputText(),
      'sh_marketing_fee'      => new sfWidgetFormInputText(),
      'sh_refund_price'       => new sfWidgetFormInputText(),
      'sh_refund_express_fee' => new sfWidgetFormInputText(),
      'created_at'            => new sfWidgetFormDateTime(),
      'updated_at'            => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'mart_order_id'         => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'account'               => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'business'              => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'total_price'           => new sfValidatorNumber(array('required' => false)),
      'shipping_price'        => new sfValidatorNumber(array('required' => false)),
      'receive_price'         => new sfValidatorNumber(array('required' => false)),
      'refund_price'          => new sfValidatorNumber(array('required' => false)),
      'full_name'             => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'order_time'            => new sfValidatorPass(array('required' => false)),
      'sh_order_time'         => new sfValidatorPass(array('required' => false)),
      'sh_order_id'           => new sfValidatorInteger(array('required' => false)),
      'sh_shipping_price'     => new sfValidatorNumber(array('required' => false)),
      'sh_coupon_fee'         => new sfValidatorNumber(array('required' => false)),
      'sh_price'              => new sfValidatorNumber(array('required' => false)),
      'sh_marketing_fee'      => new sfValidatorNumber(array('required' => false)),
      'sh_refund_price'       => new sfValidatorNumber(array('required' => false)),
      'sh_refund_express_fee' => new sfValidatorNumber(array('required' => false)),
      'created_at'            => new sfValidatorDateTime(),
      'updated_at'            => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_mart_order_info[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdMartOrderInfo';
  }

}
