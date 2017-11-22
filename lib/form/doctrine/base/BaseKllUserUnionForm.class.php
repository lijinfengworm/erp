<?php

/**
 * KllUserUnion form base class.
 *
 * @method KllUserUnion getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllUserUnionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'user_id'         => new sfWidgetFormInputText(),
      'type'            => new sfWidgetFormInputText(),
      'union_id'        => new sfWidgetFormInputText(),
      'info'            => new sfWidgetFormTextarea(),
      'union_user_name' => new sfWidgetFormInputText(),
      'ct_time'         => new sfWidgetFormInputText(),
      'up_time'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id'         => new sfValidatorInteger(array('required' => false)),
      'type'            => new sfValidatorPass(array('required' => false)),
      'union_id'        => new sfValidatorPass(array('required' => false)),
      'info'            => new sfValidatorString(array('max_length' => 10000, 'required' => false)),
      'union_user_name' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'ct_time'         => new sfValidatorInteger(array('required' => false)),
      'up_time'         => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_user_union[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllUserUnion';
  }

}
