<?php

/**
 * TrdOpenimInfo form base class.
 *
 * @method TrdOpenimInfo getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdOpenimInfoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'user_id'       => new sfWidgetFormInputText(),
      'client_str'    => new sfWidgetFormInputText(),
      'cookie_str'    => new sfWidgetFormInputText(),
      'open_username' => new sfWidgetFormInputText(),
      'open_password' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id'       => new sfValidatorInteger(array('required' => false)),
      'client_str'    => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'cookie_str'    => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'open_username' => new sfValidatorString(array('max_length' => 20)),
      'open_password' => new sfValidatorString(array('max_length' => 64)),
    ));

    $this->widgetSchema->setNameFormat('trd_openim_info[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdOpenimInfo';
  }

}
