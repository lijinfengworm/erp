<?php

/**
 * TrdOrderLogistics form base class.
 *
 * @method TrdOrderLogistics getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdOrderLogisticsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'express_number'  => new sfWidgetFormInputText(),
      'type'            => new sfWidgetFormInputText(),
      'foreign_status'  => new sfWidgetFormInputText(),
      'domestic_status' => new sfWidgetFormInputText(),
      'excompany'       => new sfWidgetFormInputText(),
      'content'         => new sfWidgetFormTextarea(),
      'transit_time'    => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'express_number'  => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'type'            => new sfValidatorInteger(array('required' => false)),
      'foreign_status'  => new sfValidatorInteger(array('required' => false)),
      'domestic_status' => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'excompany'       => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'content'         => new sfValidatorString(array('max_length' => 5000, 'required' => false)),
      'transit_time'    => new sfValidatorInteger(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_order_logistics[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdOrderLogistics';
  }

}
