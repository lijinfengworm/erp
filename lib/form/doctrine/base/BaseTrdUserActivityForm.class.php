<?php

/**
 * TrdUserActivity form base class.
 *
 * @method TrdUserActivity getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdUserActivityForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'name'       => new sfWidgetFormInputText(),
      'attr'       => new sfWidgetFormInputText(),
      'status'     => new sfWidgetFormInputText(),
      'type'       => new sfWidgetFormInputText(),
      'start_time' => new sfWidgetFormInputText(),
      'end_time'   => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'attr'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'status'     => new sfValidatorInteger(array('required' => false)),
      'type'       => new sfValidatorInteger(array('required' => false)),
      'start_time' => new sfValidatorPass(array('required' => false)),
      'end_time'   => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_user_activity[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdUserActivity';
  }

}
