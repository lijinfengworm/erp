<?php

/**
 * TrdAdminAccess form base class.
 *
 * @method TrdAdminAccess getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdAdminAccessForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'role_id'     => new sfWidgetFormInputText(),
      'menu_id'     => new sfWidgetFormInputText(),
      'controller'  => new sfWidgetFormInputText(),
      'action_name' => new sfWidgetFormInputText(),
      'child_attr'  => new sfWidgetFormTextarea(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'role_id'     => new sfValidatorInteger(),
      'menu_id'     => new sfValidatorInteger(),
      'controller'  => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'action_name' => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'child_attr'  => new sfValidatorString(array('max_length' => 5000, 'required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_admin_access[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAdminAccess';
  }

}
