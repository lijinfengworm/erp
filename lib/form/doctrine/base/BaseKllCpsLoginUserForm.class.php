<?php

/**
 * KllCpsLoginUser form base class.
 *
 * @method KllCpsLoginUser getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllCpsLoginUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'union_id'   => new sfWidgetFormInputText(),
      'uid'        => new sfWidgetFormInputText(),
      'mid'        => new sfWidgetFormInputText(),
      'referer'    => new sfWidgetFormTextarea(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'union_id'   => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'uid'        => new sfValidatorInteger(array('required' => false)),
      'mid'        => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'referer'    => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kll_cps_login_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllCpsLoginUser';
  }

}
