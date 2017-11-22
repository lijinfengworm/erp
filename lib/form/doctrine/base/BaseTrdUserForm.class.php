<?php

/**
 * TrdUser form base class.
 *
 * @method TrdUser getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'hupu_uid'          => new sfWidgetFormInputText(),
      'hupu_username'     => new sfWidgetFormInputText(),
      'external_uid'      => new sfWidgetFormInputText(),
      'external_username' => new sfWidgetFormInputText(),
      'status'            => new sfWidgetFormInputText(),
      'ban_start_time'    => new sfWidgetFormDateTime(),
      'ban_end_time'      => new sfWidgetFormDateTime(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'hupu_uid'          => new sfValidatorInteger(array('required' => false)),
      'hupu_username'     => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'external_uid'      => new sfValidatorInteger(array('required' => false)),
      'external_username' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'status'            => new sfValidatorInteger(array('required' => false)),
      'ban_start_time'    => new sfValidatorDateTime(array('required' => false)),
      'ban_end_time'      => new sfValidatorDateTime(array('required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdUser';
  }

}
