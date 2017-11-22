<?php

/**
 * TrdOrder form base class.
 *
 * @method TrdOrder getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdOrderForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'order_number'          => new sfWidgetFormInputText(),
      'ibilling_number'       => new sfWidgetFormInputText(),
      'title'                 => new sfWidgetFormInputText(),
      'product_id'            => new sfWidgetFormInputText(),
      'gid'                   => new sfWidgetFormInputText(),
      'goods_id'              => new sfWidgetFormInputText(),
      'mart_order_number'     => new sfWidgetFormInputText(),
      'mart_order_time'       => new sfWidgetFormInputText(),
      'mart_express_number'   => new sfWidgetFormInputText(),
      'mart_express_time'     => new sfWidgetFormInputText(),
      'domestic_express_type' => new sfWidgetFormInputText(),
      'domestic_order_number' => new sfWidgetFormInputText(),
      'domestic_express_time' => new sfWidgetFormInputText(),
      'attr'                  => new sfWidgetFormTextarea(),
      'business'              => new sfWidgetFormInputText(),
      'business_account'      => new sfWidgetFormInputText(),
      'storage_status'        => new sfWidgetFormInputText(),
      'express_fee'           => new sfWidgetFormInputText(),
      'total_price'           => new sfWidgetFormInputText(),
      'price'                 => new sfWidgetFormInputText(),
      'marketing_fee'         => new sfWidgetFormInputText(),
      'refund_price'          => new sfWidgetFormInputText(),
      'refund_express_fee'    => new sfWidgetFormInputText(),
      'refund'                => new sfWidgetFormInputText(),
      'refund_remark'         => new sfWidgetFormInputText(),
      'order_time'            => new sfWidgetFormInputText(),
      'storage_time'          => new sfWidgetFormInputText(),
      'pay_time'              => new sfWidgetFormInputText(),
      'pay_type'              => new sfWidgetFormInputText(),
      'refund_time'           => new sfWidgetFormInputText(),
      'status'                => new sfWidgetFormInputText(),
      'operations_status'     => new sfWidgetFormInputText(),
      'is_plugin_added'       => new sfWidgetFormInputText(),
      'pay_status'            => new sfWidgetFormInputText(),
      'source'                => new sfWidgetFormInputText(),
      'channel'               => new sfWidgetFormInputText(),
      'hupu_uid'              => new sfWidgetFormInputText(),
      'hupu_username'         => new sfWidgetFormInputText(),
      'grant_uid'             => new sfWidgetFormInputText(),
      'grant_username'        => new sfWidgetFormInputText(),
      'grab_order_time'       => new sfWidgetFormInputText(),
      'finish_order_time'     => new sfWidgetFormInputText(),
      'forecast'              => new sfWidgetFormInputText(),
      'is_comment'            => new sfWidgetFormInputText(),
      'delivery_type'         => new sfWidgetFormInputText(),
      'mobile'                => new sfWidgetFormInputText(),
      'created_at'            => new sfWidgetFormDateTime(),
      'updated_at'            => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number'          => new sfValidatorInteger(array('required' => false)),
      'ibilling_number'       => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'title'                 => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'product_id'            => new sfValidatorInteger(array('required' => false)),
      'gid'                   => new sfValidatorInteger(array('required' => false)),
      'goods_id'              => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'mart_order_number'     => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'mart_order_time'       => new sfValidatorInteger(array('required' => false)),
      'mart_express_number'   => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'mart_express_time'     => new sfValidatorInteger(array('required' => false)),
      'domestic_express_type' => new sfValidatorInteger(array('required' => false)),
      'domestic_order_number' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'domestic_express_time' => new sfValidatorInteger(array('required' => false)),
      'attr'                  => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'business'              => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'business_account'      => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'storage_status'        => new sfValidatorInteger(array('required' => false)),
      'express_fee'           => new sfValidatorNumber(array('required' => false)),
      'total_price'           => new sfValidatorNumber(array('required' => false)),
      'price'                 => new sfValidatorNumber(array('required' => false)),
      'marketing_fee'         => new sfValidatorNumber(array('required' => false)),
      'refund_price'          => new sfValidatorNumber(array('required' => false)),
      'refund_express_fee'    => new sfValidatorNumber(array('required' => false)),
      'refund'                => new sfValidatorNumber(array('required' => false)),
      'refund_remark'         => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'order_time'            => new sfValidatorPass(array('required' => false)),
      'storage_time'          => new sfValidatorPass(array('required' => false)),
      'pay_time'              => new sfValidatorPass(array('required' => false)),
      'pay_type'              => new sfValidatorInteger(array('required' => false)),
      'refund_time'           => new sfValidatorPass(array('required' => false)),
      'status'                => new sfValidatorInteger(array('required' => false)),
      'operations_status'     => new sfValidatorInteger(array('required' => false)),
      'is_plugin_added'       => new sfValidatorInteger(array('required' => false)),
      'pay_status'            => new sfValidatorInteger(array('required' => false)),
      'source'                => new sfValidatorInteger(array('required' => false)),
      'channel'               => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'hupu_uid'              => new sfValidatorInteger(array('required' => false)),
      'hupu_username'         => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'grant_uid'             => new sfValidatorInteger(array('required' => false)),
      'grant_username'        => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'grab_order_time'       => new sfValidatorInteger(array('required' => false)),
      'finish_order_time'     => new sfValidatorInteger(array('required' => false)),
      'forecast'              => new sfValidatorInteger(array('required' => false)),
      'is_comment'            => new sfValidatorInteger(array('required' => false)),
      'delivery_type'         => new sfValidatorInteger(array('required' => false)),
      'mobile'                => new sfValidatorString(array('max_length' => 16, 'required' => false)),
      'created_at'            => new sfValidatorDateTime(),
      'updated_at'            => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_order[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdOrder';
  }

}
