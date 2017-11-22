<?php

/**
 * TrdAdminMenu form base class.
 *
 * @method TrdAdminMenu getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdAdminMenuForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'pid'         => new sfWidgetFormInputText(),
      'name'        => new sfWidgetFormInputText(),
      'controller'  => new sfWidgetFormInputText(),
      'action_name' => new sfWidgetFormInputText(),
      'is_public'   => new sfWidgetFormInputText(),
      'is_hide'     => new sfWidgetFormInputText(),
      'child_attr'  => new sfWidgetFormTextarea(),
      'menu_group'  => new sfWidgetFormInputText(),
      'menu_status' => new sfWidgetFormInputText(),
      'listorder'   => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'pid'         => new sfValidatorInteger(),
      'name'        => new sfValidatorString(array('max_length' => 32)),
      'controller'  => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'action_name' => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'is_public'   => new sfValidatorInteger(array('required' => false)),
      'is_hide'     => new sfValidatorInteger(array('required' => false)),
      'child_attr'  => new sfValidatorString(array('max_length' => 5000, 'required' => false)),
      'menu_group'  => new sfValidatorString(array('max_length' => 32)),
      'menu_status' => new sfValidatorInteger(),
      'listorder'   => new sfValidatorInteger(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_admin_menu[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAdminMenu';
  }

}
