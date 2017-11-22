<?php

/**
 * KaluliOptLog form base class.
 *
 * @method KaluliOptLog getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKaluliOptLogForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'opt_id'        => new sfWidgetFormInputText(),
      'opt_uid'       => new sfWidgetFormInputText(),
      'opt_uri'       => new sfWidgetFormInputText(),
      'opt_json'      => new sfWidgetFormInputText(),
      'opt_time'      => new sfWidgetFormInputText(),
      'last_login_ip' => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'opt_id'        => new sfValidatorInteger(array('required' => false)),
      'opt_uid'       => new sfValidatorInteger(array('required' => false)),
      'opt_uri'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'opt_json'      => new sfValidatorPass(array('required' => false)),
      'opt_time'      => new sfValidatorInteger(array('required' => false)),
      'last_login_ip' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kaluli_opt_log[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliOptLog';
  }

}
