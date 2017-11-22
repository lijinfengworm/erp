<?php

/**
 * TrdProductCollocation form base class.
 *
 * @method TrdProductCollocation getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdProductCollocationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'product_id'       => new sfWidgetFormInputText(),
      'collocation_attr' => new sfWidgetFormInputText(),
      'shaiwu_attr'      => new sfWidgetFormInputText(),
      'status'           => new sfWidgetFormInputText(),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'product_id'       => new sfValidatorInteger(array('required' => false)),
      'collocation_attr' => new sfValidatorString(array('max_length' => 50)),
      'shaiwu_attr'      => new sfValidatorInteger(array('required' => false)),
      'status'           => new sfValidatorInteger(array('required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_product_collocation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdProductCollocation';
  }

}
