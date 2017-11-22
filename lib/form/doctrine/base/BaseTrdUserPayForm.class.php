<?php

/**
 * TrdUserPay form base class.
 *
 * @method TrdUserPay getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdUserPayForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'hupu_uid'         => new sfWidgetFormInputText(),
      'hupu_username'    => new sfWidgetFormInputText(),
      'pay_passwd'       => new sfWidgetFormInputText(),
      'pay_passwd_index' => new sfWidgetFormInputText(),
      'status'           => new sfWidgetFormInputText(),
      'phone'            => new sfWidgetFormInputText(),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'hupu_uid'         => new sfValidatorInteger(array('required' => false)),
      'hupu_username'    => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'pay_passwd'       => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'pay_passwd_index' => new sfValidatorString(array('max_length' => 5, 'required' => false)),
      'status'           => new sfValidatorInteger(array('required' => false)),
      'phone'            => new sfValidatorString(array('max_length' => 11, 'required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_user_pay[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdUserPay';
  }

}
