<?php

/**
 * KllActivitySet form base class.
 *
 * @method KllActivitySet getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllActivitySetForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'title'      => new sfWidgetFormInputText(),
      'key'        => new sfWidgetFormInputText(),
      'role'       => new sfWidgetFormTextarea(),
      'note'       => new sfWidgetFormTextarea(),
      'remarks'    => new sfWidgetFormTextarea(),
      'version'    => new sfWidgetFormInputText(),
      'status'     => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'      => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'key'        => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'role'       => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'note'       => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'remarks'    => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'version'    => new sfValidatorInteger(array('required' => false)),
      'status'     => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kll_activity_set[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllActivitySet';
  }

}
