<?php

/**
 * KaluliWarehouses form base class.
 *
 * @method KaluliWarehouses getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKaluliWarehousesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'name'           => new sfWidgetFormInputText(),
      'code'           => new sfWidgetFormInputText(),
      'address'        => new sfWidgetFormInputText(),
      'note'           => new sfWidgetFormInputText(),
      'create_date'    => new sfWidgetFormDateTime(),
      'contact_name'   => new sfWidgetFormInputText(),
      'contact_phone'  => new sfWidgetFormInputText(),
      'contact_mobile' => new sfWidgetFormInputText(),
      'type_name'      => new sfWidgetFormInputText(),
      'area_name'      => new sfWidgetFormInputText(),
      'freight_type'   => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'           => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'code'           => new sfValidatorString(array('max_length' => 12, 'required' => false)),
      'address'        => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'note'           => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'create_date'    => new sfValidatorDateTime(array('required' => false)),
      'contact_name'   => new sfValidatorString(array('max_length' => 24, 'required' => false)),
      'contact_phone'  => new sfValidatorString(array('max_length' => 16, 'required' => false)),
      'contact_mobile' => new sfValidatorString(array('max_length' => 16, 'required' => false)),
      'type_name'      => new sfValidatorString(array('max_length' => 24, 'required' => false)),
      'area_name'      => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'freight_type'   => new sfValidatorPass(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kaluli_warehouses[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliWarehouses';
  }

}
