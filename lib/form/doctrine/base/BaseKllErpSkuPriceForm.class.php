<?php

/**
 * KllErpSkuPrice form base class.
 *
 * @method KllErpSkuPrice getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllErpSkuPriceForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'goods_id'       => new sfWidgetFormInputText(),
      'sku_id'         => new sfWidgetFormInputText(),
      'code_num'       => new sfWidgetFormInputText(),
      'product_code'   => new sfWidgetFormInputText(),
      'goods_title'    => new sfWidgetFormInputText(),
      'channel'        => new sfWidgetFormInputText(),
      'depot'          => new sfWidgetFormInputText(),
      'standard_price' => new sfWidgetFormInputText(),
      'cost_price'     => new sfWidgetFormInputText(),
      'push_price'     => new sfWidgetFormInputText(),
      'add_user'       => new sfWidgetFormInputText(),
      'audit_user'     => new sfWidgetFormInputText(),
      'audit_status'   => new sfWidgetFormInputText(),
      'audit_time'     => new sfWidgetFormInputText(),
      'create_time'    => new sfWidgetFormInputText(),
      'update_time'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'goods_id'       => new sfValidatorInteger(array('required' => false)),
      'sku_id'         => new sfValidatorInteger(array('required' => false)),
      'code_num'       => new sfValidatorPass(array('required' => false)),
      'product_code'   => new sfValidatorPass(array('required' => false)),
      'goods_title'    => new sfValidatorPass(array('required' => false)),
      'channel'        => new sfValidatorPass(array('required' => false)),
      'depot'          => new sfValidatorPass(array('required' => false)),
      'standard_price' => new sfValidatorNumber(array('required' => false)),
      'cost_price'     => new sfValidatorNumber(array('required' => false)),
      'push_price'     => new sfValidatorNumber(array('required' => false)),
      'add_user'       => new sfValidatorInteger(array('required' => false)),
      'audit_user'     => new sfValidatorInteger(array('required' => false)),
      'audit_status'   => new sfValidatorPass(array('required' => false)),
      'audit_time'     => new sfValidatorInteger(array('required' => false)),
      'create_time'    => new sfValidatorInteger(array('required' => false)),
      'update_time'    => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_erp_sku_price[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllErpSkuPrice';
  }

}
