<?php

/**
 * TrdAppActivityRemind form base class.
 *
 * @method TrdAppActivityRemind getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdAppActivityRemindForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'activity_id'  => new sfWidgetFormInputText(),
      'mobile'       => new sfWidgetFormInputText(),
      'status'       => new sfWidgetFormInputCheckbox(),
      'title'        => new sfWidgetFormInputText(),
      'start_time'   => new sfWidgetFormInputText(),
      'created_time' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'activity_id'  => new sfValidatorInteger(),
      'mobile'       => new sfValidatorString(array('max_length' => 16, 'required' => false)),
      'status'       => new sfValidatorBoolean(array('required' => false)),
      'title'        => new sfValidatorString(array('max_length' => 200)),
      'start_time'   => new sfValidatorPass(),
      'created_time' => new sfValidatorPass(),
    ));

    $this->widgetSchema->setNameFormat('trd_app_activity_remind[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAppActivityRemind';
  }

}
