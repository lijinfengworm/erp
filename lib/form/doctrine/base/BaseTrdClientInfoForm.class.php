<?php

/**
 * TrdClientInfo form base class.
 *
 * @method TrdClientInfo getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdClientInfoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'user_id'      => new sfWidgetFormInputText(),
      'client_str'   => new sfWidgetFormInputText(),
      'client_token' => new sfWidgetFormInputText(),
      'wpclient_str' => new sfWidgetFormInputText(),
      'wp_url'       => new sfWidgetFormTextarea(),
      'first_virst'  => new sfWidgetFormInputText(),
      'last_virst'   => new sfWidgetFormInputText(),
      'type'         => new sfWidgetFormInputText(),
      'ios_type'     => new sfWidgetFormInputText(),
      'push_switch'  => new sfWidgetFormInputText(),
      'status'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id'      => new sfValidatorInteger(array('required' => false)),
      'client_str'   => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'client_token' => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'wpclient_str' => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'wp_url'       => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'first_virst'  => new sfValidatorInteger(array('required' => false)),
      'last_virst'   => new sfValidatorInteger(array('required' => false)),
      'type'         => new sfValidatorInteger(array('required' => false)),
      'ios_type'     => new sfValidatorInteger(array('required' => false)),
      'push_switch'  => new sfValidatorInteger(array('required' => false)),
      'status'       => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_client_info[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdClientInfo';
  }

}
