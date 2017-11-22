<?php

/**
 * kllItemCustom form base class.
 *
 * @method kllItemCustom getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasekllItemCustomForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'warranty_days'   => new sfWidgetFormInputText(),
      'goods_number'    => new sfWidgetFormInputText(),
      'reg_custom'      => new sfWidgetFormInputText(),
      'reg_inspection'  => new sfWidgetFormInputText(),
      'reg_app_date'    => new sfWidgetFormInputText(),
      'custom_code'     => new sfWidgetFormInputText(),
      'inspection_code' => new sfWidgetFormInputText(),
      'hs_code'         => new sfWidgetFormInputText(),
      'tax_code'        => new sfWidgetFormInputText(),
      'stock_number'    => new sfWidgetFormInputText(),
      'unit_price'      => new sfWidgetFormInputText(),
      'unit'            => new sfWidgetFormInputText(),
      'original'        => new sfWidgetFormInputText(),
      'code'            => new sfWidgetFormInputText(),
      'goods_id'        => new sfWidgetFormInputText(),
      'product_id'      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'warranty_days'   => new sfValidatorInteger(array('required' => false)),
      'goods_number'    => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'reg_custom'      => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'reg_inspection'  => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'reg_app_date'    => new sfValidatorPass(array('required' => false)),
      'custom_code'     => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'inspection_code' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'hs_code'         => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'tax_code'        => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'stock_number'    => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'unit_price'      => new sfValidatorPass(array('required' => false)),
      'unit'            => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'original'        => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'code'            => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'goods_id'        => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'product_id'      => new sfValidatorString(array('max_length' => 10, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_item_custom[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'kllItemCustom';
  }

}
