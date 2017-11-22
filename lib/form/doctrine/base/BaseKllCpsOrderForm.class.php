<?php

/**
 * KllCpsOrder form base class.
 *
 * @method KllCpsOrder getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllCpsOrderForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'order_number'     => new sfWidgetFormInputText(),
      'sub_order_number' => new sfWidgetFormInputText(),
      'order_time'       => new sfWidgetFormInputText(),
      'click_time'       => new sfWidgetFormInputText(),
      'orders_price'     => new sfWidgetFormInputText(),
      'discount_amount'  => new sfWidgetFormInputText(),
      'promotion_code'   => new sfWidgetFormInputText(),
      'is_new_custom'    => new sfWidgetFormInputText(),
      'channel'          => new sfWidgetFormInputText(),
      'status'           => new sfWidgetFormInputText(),
      'goods_id'         => new sfWidgetFormInputText(),
      'title'            => new sfWidgetFormInputText(),
      'goods_price'      => new sfWidgetFormInputText(),
      'goods_ta'         => new sfWidgetFormInputText(),
      'goods_cate'       => new sfWidgetFormInputText(),
      'goods_cate_name'  => new sfWidgetFormInputText(),
      'total_price'      => new sfWidgetFormInputText(),
      'rate'             => new sfWidgetFormInputText(),
      'commission'       => new sfWidgetFormInputText(),
      'commission_type'  => new sfWidgetFormInputText(),
      'test'             => new sfWidgetFormInputText(),
      'union_id'         => new sfWidgetFormInputText(),
      'mid'              => new sfWidgetFormInputText(),
      'euid'             => new sfWidgetFormInputText(),
      'referer'          => new sfWidgetFormTextarea(),
      'hupu_uid'         => new sfWidgetFormInputText(),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number'     => new sfValidatorInteger(array('required' => false)),
      'sub_order_number' => new sfValidatorInteger(array('required' => false)),
      'order_time'       => new sfValidatorInteger(array('required' => false)),
      'click_time'       => new sfValidatorInteger(array('required' => false)),
      'orders_price'     => new sfValidatorNumber(array('required' => false)),
      'discount_amount'  => new sfValidatorNumber(array('required' => false)),
      'promotion_code'   => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'is_new_custom'    => new sfValidatorInteger(array('required' => false)),
      'channel'          => new sfValidatorInteger(array('required' => false)),
      'status'           => new sfValidatorInteger(array('required' => false)),
      'goods_id'         => new sfValidatorInteger(array('required' => false)),
      'title'            => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'goods_price'      => new sfValidatorNumber(array('required' => false)),
      'goods_ta'         => new sfValidatorInteger(array('required' => false)),
      'goods_cate'       => new sfValidatorInteger(array('required' => false)),
      'goods_cate_name'  => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'total_price'      => new sfValidatorNumber(array('required' => false)),
      'rate'             => new sfValidatorNumber(array('required' => false)),
      'commission'       => new sfValidatorNumber(array('required' => false)),
      'commission_type'  => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'test'             => new sfValidatorInteger(array('required' => false)),
      'union_id'         => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'mid'              => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'euid'             => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'referer'          => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'hupu_uid'         => new sfValidatorInteger(array('required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kll_cps_order[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllCpsOrder';
  }

}
