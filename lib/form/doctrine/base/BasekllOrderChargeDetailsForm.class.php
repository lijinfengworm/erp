<?php

/**
 * kllOrderChargeDetails form base class.
 *
 * @method kllOrderChargeDetails getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasekllOrderChargeDetailsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'logisticsId'       => new sfWidgetFormInputText(),
      'order_number'      => new sfWidgetFormInputText(),
      'logistics_cost'    => new sfWidgetFormInputText(),
      'package_cost'      => new sfWidgetFormInputText(),
      'operating_cost'    => new sfWidgetFormInputText(),
      'insurance_cost'    => new sfWidgetFormInputText(),
      'verification_cost' => new sfWidgetFormInputText(),
      'other_cost'        => new sfWidgetFormInputText(),
      'cost_unit'         => new sfWidgetFormInputText(),
      'remark'            => new sfWidgetFormTextarea(),
      'create_time'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'logisticsId'       => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'order_number'      => new sfValidatorInteger(array('required' => false)),
      'logistics_cost'    => new sfValidatorNumber(array('required' => false)),
      'package_cost'      => new sfValidatorNumber(array('required' => false)),
      'operating_cost'    => new sfValidatorNumber(array('required' => false)),
      'insurance_cost'    => new sfValidatorNumber(array('required' => false)),
      'verification_cost' => new sfValidatorNumber(array('required' => false)),
      'other_cost'        => new sfValidatorNumber(array('required' => false)),
      'cost_unit'         => new sfValidatorPass(array('required' => false)),
      'remark'            => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'create_time'       => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_order_charge_details[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'kllOrderChargeDetails';
  }

}
