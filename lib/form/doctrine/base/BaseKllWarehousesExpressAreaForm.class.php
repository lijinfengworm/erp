<?php

/**
 * KllWarehousesExpressArea form base class.
 *
 * @method KllWarehousesExpressArea getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllWarehousesExpressAreaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'ware_express_id'  => new sfWidgetFormInputText(),
      'provinces'        => new sfWidgetFormInputText(),
      'is_default'       => new sfWidgetFormInputText(),
      'first_price'      => new sfWidgetFormInputText(),
      'additional_price' => new sfWidgetFormInputText(),
      'ct_time'          => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ware_express_id'  => new sfValidatorInteger(array('required' => false)),
      'provinces'        => new sfValidatorInteger(array('required' => false)),
      'is_default'       => new sfValidatorInteger(array('required' => false)),
      'first_price'      => new sfValidatorNumber(array('required' => false)),
      'additional_price' => new sfValidatorNumber(array('required' => false)),
      'ct_time'          => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_warehouses_express_area[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllWarehousesExpressArea';
  }

}
