<?php

/**
 * KllUser form base class.
 *
 * @method KllUser getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'         => new sfWidgetFormInputHidden(),
      'user_name'       => new sfWidgetFormInputText(),
      'password'        => new sfWidgetFormInputText(),
      'mobile'          => new sfWidgetFormInputText(),
      'source'          => new sfWidgetFormInputText(),
      'ct_time'         => new sfWidgetFormInputText(),
      'up_time'         => new sfWidgetFormInputText(),
      'last_login_time' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'user_id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('user_id')), 'empty_value' => $this->getObject()->get('user_id'), 'required' => false)),
      'user_name'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'password'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'mobile'          => new sfValidatorInteger(array('required' => false)),
      'source'          => new sfValidatorPass(array('required' => false)),
      'ct_time'         => new sfValidatorInteger(array('required' => false)),
      'up_time'         => new sfValidatorInteger(array('required' => false)),
      'last_login_time' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllUser';
  }

}
