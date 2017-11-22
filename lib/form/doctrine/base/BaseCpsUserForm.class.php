<?php

/**
 * CpsUser form base class.
 *
 * @method CpsUser getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCpsUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'union_id'      => new sfWidgetFormInputText(),
      'hupu_uid'      => new sfWidgetFormInputText(),
      'hupu_username' => new sfWidgetFormInputText(),
      'type'          => new sfWidgetFormInputText(),
      'status'        => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'union_id'      => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'hupu_uid'      => new sfValidatorInteger(array('required' => false)),
      'hupu_username' => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'type'          => new sfValidatorInteger(array('required' => false)),
      'status'        => new sfValidatorInteger(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'CpsUser', 'column' => array('union_id')))
    );

    $this->widgetSchema->setNameFormat('cps_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CpsUser';
  }

}
