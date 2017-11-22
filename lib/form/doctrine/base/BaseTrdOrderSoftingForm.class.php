<?php

/**
 * TrdOrderSofting form base class.
 *
 * @method TrdOrderSofting getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdOrderSoftingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'order_number'   => new sfWidgetFormInputText(),
      'delivery_type'  => new sfWidgetFormInputText(),
      'express_number' => new sfWidgetFormInputText(),
      'softing_id'     => new sfWidgetFormInputText(),
      'status'         => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number'   => new sfValidatorInteger(array('required' => false)),
      'delivery_type'  => new sfValidatorInteger(array('required' => false)),
      'express_number' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'softing_id'     => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'status'         => new sfValidatorInteger(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_order_softing[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdOrderSofting';
  }

}
