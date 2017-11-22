<?php

/**
 * KllWarehousesExpress form base class.
 *
 * @method KllWarehousesExpress getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllWarehousesExpressForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'warehouse_id' => new sfWidgetFormInputText(),
      'express_id'   => new sfWidgetFormInputText(),
      'status'       => new sfWidgetFormInputText(),
      'radio'        => new sfWidgetFormInputText(),
      'is_default'   => new sfWidgetFormInputText(),
      'ct_time'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'warehouse_id' => new sfValidatorInteger(array('required' => false)),
      'express_id'   => new sfValidatorInteger(array('required' => false)),
      'status'       => new sfValidatorInteger(array('required' => false)),
      'radio'        => new sfValidatorNumber(array('required' => false)),
      'is_default'   => new sfValidatorInteger(array('required' => false)),
      'ct_time'      => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_warehouses_express[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllWarehousesExpress';
  }

}
