<?php

/**
 * TrdUserTask form base class.
 *
 * @method TrdUserTask getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdUserTaskForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'hupu_uid'      => new sfWidgetFormInputText(),
      'task_attr'     => new sfWidgetFormTextarea(),
      'one_time_attr' => new sfWidgetFormTextarea(),
      'finished_at'   => new sfWidgetFormDateTime(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'hupu_uid'      => new sfValidatorInteger(array('required' => false)),
      'task_attr'     => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'one_time_attr' => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'finished_at'   => new sfValidatorDateTime(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_user_task[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdUserTask';
  }

}
