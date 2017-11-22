<?php

/**
 * KllUserProperty form base class.
 *
 * @method KllUserProperty getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllUserPropertyForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'         => new sfWidgetFormInputHidden(),
      'user_name'       => new sfWidgetFormInputText(),
      'mail'            => new sfWidgetFormInputText(),
      'status'          => new sfWidgetFormInputText(),
      'sex'             => new sfWidgetFormInputText(),
      'province'        => new sfWidgetFormInputText(),
      'city'            => new sfWidgetFormInputText(),
      'profession'      => new sfWidgetFormInputText(),
      'info'            => new sfWidgetFormTextarea(),
      'register_time'   => new sfWidgetFormInputText(),
      'last_login_time' => new sfWidgetFormInputText(),
      'pwd_level'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'user_id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('user_id')), 'empty_value' => $this->getObject()->get('user_id'), 'required' => false)),
      'user_name'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'mail'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'status'          => new sfValidatorPass(array('required' => false)),
      'sex'             => new sfValidatorPass(array('required' => false)),
      'province'        => new sfValidatorInteger(array('required' => false)),
      'city'            => new sfValidatorInteger(array('required' => false)),
      'profession'      => new sfValidatorInteger(array('required' => false)),
      'info'            => new sfValidatorString(array('max_length' => 10000, 'required' => false)),
      'register_time'   => new sfValidatorInteger(array('required' => false)),
      'last_login_time' => new sfValidatorInteger(array('required' => false)),
      'pwd_level'       => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_user_property[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllUserProperty';
  }

}
