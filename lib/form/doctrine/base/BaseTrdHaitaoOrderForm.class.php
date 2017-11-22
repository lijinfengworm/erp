<?php

/**
 * TrdHaitaoOrder form base class.
 *
 * @method TrdHaitaoOrder getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdHaitaoOrderForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'order_number'           => new sfWidgetFormInputText(),
      'ibilling_number'        => new sfWidgetFormInputText(),
      'title'                  => new sfWidgetFormInputText(),
      'news_id'                => new sfWidgetFormInputText(),
      'product_id'             => new sfWidgetFormInputText(),
      'gid'                    => new sfWidgetFormInputText(),
      'hupu_uid'               => new sfWidgetFormInputText(),
      'hupu_username'          => new sfWidgetFormInputText(),
      'goods_id'               => new sfWidgetFormInputText(),
      'mart_order_number'      => new sfWidgetFormInputText(),
      'mart_express_type'      => new sfWidgetFormInputText(),
      'mart_express_number'    => new sfWidgetFormInputText(),
      'transport_type'         => new sfWidgetFormInputText(),
      'transport_order_number' => new sfWidgetFormInputText(),
      'customs_express_type'   => new sfWidgetFormInputText(),
      'customs_order_number'   => new sfWidgetFormInputText(),
      'domestic_express_type'  => new sfWidgetFormInputText(),
      'domestic_order_number'  => new sfWidgetFormInputText(),
      'domestic_express_time'  => new sfWidgetFormInputText(),
      'address'                => new sfWidgetFormTextarea(),
      'address_id'             => new sfWidgetFormInputText(),
      'attr'                   => new sfWidgetFormTextarea(),
      'number'                 => new sfWidgetFormInputText(),
      'storage_number'         => new sfWidgetFormInputText(),
      'price'                  => new sfWidgetFormInputText(),
      'express_type'           => new sfWidgetFormInputText(),
      'express_fee'            => new sfWidgetFormInputText(),
      'intl_freight'           => new sfWidgetFormInputText(),
      'total_price'            => new sfWidgetFormInputText(),
      'refund'                 => new sfWidgetFormInputText(),
      'refund_remark'          => new sfWidgetFormInputText(),
      'remark'                 => new sfWidgetFormInputText(),
      'order_time'             => new sfWidgetFormInputText(),
      'storage_time'           => new sfWidgetFormInputText(),
      'pay_time'               => new sfWidgetFormInputText(),
      'refund_time'            => new sfWidgetFormInputText(),
      'grant_uid'              => new sfWidgetFormInputText(),
      'grant_username'         => new sfWidgetFormInputText(),
      'refund_type'            => new sfWidgetFormInputText(),
      'status'                 => new sfWidgetFormInputText(),
      'is_plugin_added'        => new sfWidgetFormInputText(),
      'pay_status'             => new sfWidgetFormInputText(),
      'source'                 => new sfWidgetFormInputText(),
      'created_at'             => new sfWidgetFormDateTime(),
      'updated_at'             => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number'           => new sfValidatorInteger(array('required' => false)),
      'ibilling_number'        => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'title'                  => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'news_id'                => new sfValidatorInteger(array('required' => false)),
      'product_id'             => new sfValidatorInteger(array('required' => false)),
      'gid'                    => new sfValidatorInteger(array('required' => false)),
      'hupu_uid'               => new sfValidatorInteger(array('required' => false)),
      'hupu_username'          => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'goods_id'               => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'mart_order_number'      => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'mart_express_type'      => new sfValidatorInteger(array('required' => false)),
      'mart_express_number'    => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'transport_type'         => new sfValidatorInteger(array('required' => false)),
      'transport_order_number' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'customs_express_type'   => new sfValidatorInteger(array('required' => false)),
      'customs_order_number'   => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'domestic_express_type'  => new sfValidatorInteger(array('required' => false)),
      'domestic_order_number'  => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'domestic_express_time'  => new sfValidatorInteger(array('required' => false)),
      'address'                => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'address_id'             => new sfValidatorInteger(array('required' => false)),
      'attr'                   => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'number'                 => new sfValidatorInteger(array('required' => false)),
      'storage_number'         => new sfValidatorInteger(array('required' => false)),
      'price'                  => new sfValidatorNumber(array('required' => false)),
      'express_type'           => new sfValidatorInteger(array('required' => false)),
      'express_fee'            => new sfValidatorInteger(array('required' => false)),
      'intl_freight'           => new sfValidatorNumber(array('required' => false)),
      'total_price'            => new sfValidatorNumber(array('required' => false)),
      'refund'                 => new sfValidatorNumber(array('required' => false)),
      'refund_remark'          => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'remark'                 => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'order_time'             => new sfValidatorPass(array('required' => false)),
      'storage_time'           => new sfValidatorPass(array('required' => false)),
      'pay_time'               => new sfValidatorPass(array('required' => false)),
      'refund_time'            => new sfValidatorPass(array('required' => false)),
      'grant_uid'              => new sfValidatorInteger(array('required' => false)),
      'grant_username'         => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'refund_type'            => new sfValidatorInteger(array('required' => false)),
      'status'                 => new sfValidatorInteger(array('required' => false)),
      'is_plugin_added'        => new sfValidatorInteger(array('required' => false)),
      'pay_status'             => new sfValidatorInteger(array('required' => false)),
      'source'                 => new sfValidatorInteger(array('required' => false)),
      'created_at'             => new sfValidatorDateTime(),
      'updated_at'             => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'TrdHaitaoOrder', 'column' => array('order_number')))
    );

    $this->widgetSchema->setNameFormat('trd_haitao_order[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdHaitaoOrder';
  }

}
