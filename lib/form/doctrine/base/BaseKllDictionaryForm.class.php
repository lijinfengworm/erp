<?php

/**
 * KllDictionary form base class.
 *
 * @method KllDictionary getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllDictionaryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'kll_type'   => new sfWidgetFormInputText(),
      'kll_code'   => new sfWidgetFormInputText(),
      'str_value'  => new sfWidgetFormInputText(),
      'int_value'  => new sfWidgetFormInputText(),
      'is_special' => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'kll_type'   => new sfValidatorInteger(),
      'kll_code'   => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'str_value'  => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'int_value'  => new sfValidatorInteger(array('required' => false)),
      'is_special' => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kll_dictionary[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllDictionary';
  }

}
