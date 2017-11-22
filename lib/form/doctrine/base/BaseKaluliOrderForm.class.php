<?php

/**
 * KaluliOrder form base class.
 *
 * @method KaluliOrder getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKaluliOrderForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'order_number'          => new sfWidgetFormInputText(),
      'ibilling_number'       => new sfWidgetFormInputText(),
      'title'                 => new sfWidgetFormInputText(),
      'product_id'            => new sfWidgetFormInputText(),
      'goods_id'              => new sfWidgetFormInputText(),
      'domestic_express_type' => new sfWidgetFormInputText(),
      'domestic_order_number' => new sfWidgetFormInputText(),
      'domestic_express_time' => new sfWidgetFormInputText(),
      'is_gift'               => new sfWidgetFormInputText(),
      'depot_type'            => new sfWidgetFormInputText(),
      'express_fee'           => new sfWidgetFormInputText(),
      'total_price'           => new sfWidgetFormInputText(),
      'price'                 => new sfWidgetFormInputText(),
      'number'                => new sfWidgetFormInputText(),
      'marketing_fee'         => new sfWidgetFormInputText(),
      'duty_fee'              => new sfWidgetFormInputText(),
      'order_time'            => new sfWidgetFormInputText(),
      'pay_time'              => new sfWidgetFormInputText(),
      'receive_time'          => new sfWidgetFormInputText(),
      'status'                => new sfWidgetFormInputText(),
      'pay_status'            => new sfWidgetFormInputText(),
      'source'                => new sfWidgetFormInputText(),
      'hupu_uid'              => new sfWidgetFormInputText(),
      'hupu_username'         => new sfWidgetFormInputText(),
      'is_comment'            => new sfWidgetFormInputText(),
      'ware_status'           => new sfWidgetFormInputText(),
      'is_activity'           => new sfWidgetFormInputText(),
      'created_at'            => new sfWidgetFormDateTime(),
      'updated_at'            => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number'          => new sfValidatorInteger(array('required' => false)),
      'ibilling_number'       => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'title'                 => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'product_id'            => new sfValidatorInteger(array('required' => false)),
      'goods_id'              => new sfValidatorInteger(array('required' => false)),
      'domestic_express_type' => new sfValidatorInteger(array('required' => false)),
      'domestic_order_number' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'domestic_express_time' => new sfValidatorInteger(array('required' => false)),
      'is_gift'               => new sfValidatorInteger(array('required' => false)),
      'depot_type'            => new sfValidatorInteger(array('required' => false)),
      'express_fee'           => new sfValidatorNumber(array('required' => false)),
      'total_price'           => new sfValidatorNumber(array('required' => false)),
      'price'                 => new sfValidatorNumber(array('required' => false)),
      'number'                => new sfValidatorInteger(array('required' => false)),
      'marketing_fee'         => new sfValidatorNumber(array('required' => false)),
      'duty_fee'              => new sfValidatorNumber(array('required' => false)),
      'order_time'            => new sfValidatorPass(array('required' => false)),
      'pay_time'              => new sfValidatorPass(array('required' => false)),
      'receive_time'          => new sfValidatorPass(array('required' => false)),
      'status'                => new sfValidatorInteger(array('required' => false)),
      'pay_status'            => new sfValidatorInteger(array('required' => false)),
      'source'                => new sfValidatorInteger(array('required' => false)),
      'hupu_uid'              => new sfValidatorInteger(array('required' => false)),
      'hupu_username'         => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'is_comment'            => new sfValidatorInteger(array('required' => false)),
      'ware_status'           => new sfValidatorInteger(),
      'is_activity'           => new sfValidatorInteger(array('required' => false)),
      'created_at'            => new sfValidatorDateTime(),
      'updated_at'            => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kaluli_order[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliOrder';
  }

}
