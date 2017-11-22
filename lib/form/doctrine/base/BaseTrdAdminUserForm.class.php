<?php

/**
 * TrdAdminUser form base class.
 *
 * @method TrdAdminUser getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdAdminUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'hupu_uid'        => new sfWidgetFormInputText(),
      'username'        => new sfWidgetFormInputText(),
      'password'        => new sfWidgetFormInputText(),
      'qq'              => new sfWidgetFormInputText(),
      'email'           => new sfWidgetFormInputText(),
      'mobile'          => new sfWidgetFormInputText(),
      'verify'          => new sfWidgetFormInputText(),
      'channel'         => new sfWidgetFormInputText(),
      'role'            => new sfWidgetFormInputText(),
      'last_login_ip'   => new sfWidgetFormInputText(),
      'last_login_time' => new sfWidgetFormDateTime(),
      'user_status'     => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'hupu_uid'        => new sfValidatorPass(array('required' => false)),
      'username'        => new sfValidatorPass(array('required' => false)),
      'password'        => new sfValidatorPass(array('required' => false)),
      'qq'              => new sfValidatorPass(array('required' => false)),
      'email'           => new sfValidatorPass(array('required' => false)),
      'mobile'          => new sfValidatorPass(array('required' => false)),
      'verify'          => new sfValidatorPass(array('required' => false)),
      'channel'         => new sfValidatorPass(array('required' => false)),
      'role'            => new sfValidatorPass(array('required' => false)),
      'last_login_ip'   => new sfValidatorPass(array('required' => false)),
      'last_login_time' => new sfValidatorDateTime(array('required' => false)),
      'user_status'     => new sfValidatorPass(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_admin_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAdminUser';
  }

}
