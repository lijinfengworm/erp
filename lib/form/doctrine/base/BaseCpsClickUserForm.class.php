<?php

/**
 * CpsClickUser form base class.
 *
 * @method CpsClickUser getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCpsClickUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'cookie'     => new sfWidgetFormInputText(),
      'union_id'   => new sfWidgetFormInputText(),
      'mid'        => new sfWidgetFormInputText(),
      'euid'       => new sfWidgetFormInputText(),
      'referer'    => new sfWidgetFormTextarea(),
      'to'         => new sfWidgetFormTextarea(),
      'ip'         => new sfWidgetFormInputText(),
      'click_time' => new sfWidgetFormInputText(),
      'hupu_uid'   => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'cookie'     => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'union_id'   => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'mid'        => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'euid'       => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'referer'    => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'to'         => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'ip'         => new sfValidatorString(array('max_length' => 15, 'required' => false)),
      'click_time' => new sfValidatorInteger(array('required' => false)),
      'hupu_uid'   => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'CpsClickUser', 'column' => array('cookie')))
    );

    $this->widgetSchema->setNameFormat('cps_click_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CpsClickUser';
  }

}
