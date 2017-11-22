<?php

/**
 * KllNewUserTask form base class.
 *
 * @method KllNewUserTask getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllNewUserTaskForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'user_id'      => new sfWidgetFormInputText(),
      'invitor'      => new sfWidgetFormInputText(),
      'section'      => new sfWidgetFormInputText(),
      'ct_time'      => new sfWidgetFormDateTime(),
      'content'      => new sfWidgetFormInputText(),
      'order_number' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id'      => new sfValidatorInteger(array('required' => false)),
      'invitor'      => new sfValidatorInteger(array('required' => false)),
      'section'      => new sfValidatorPass(array('required' => false)),
      'ct_time'      => new sfValidatorDateTime(array('required' => false)),
      'content'      => new sfValidatorPass(array('required' => false)),
      'order_number' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_new_user_task[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllNewUserTask';
  }

}
