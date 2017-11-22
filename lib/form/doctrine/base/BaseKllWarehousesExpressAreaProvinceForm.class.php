<?php

/**
 * KllWarehousesExpressAreaProvince form base class.
 *
 * @method KllWarehousesExpressAreaProvince getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllWarehousesExpressAreaProvinceForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'ware_express'         => new sfWidgetFormInputText(),
      'ware_express_area_id' => new sfWidgetFormInputText(),
      'province'             => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ware_express'         => new sfValidatorInteger(array('required' => false)),
      'ware_express_area_id' => new sfValidatorInteger(array('required' => false)),
      'province'             => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_warehouses_express_area_province[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllWarehousesExpressAreaProvince';
  }

}
