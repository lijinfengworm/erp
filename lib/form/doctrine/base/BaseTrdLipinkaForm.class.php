<?php

/**
 * TrdLipinka form base class.
 *
 * @method TrdLipinka getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdLipinkaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'title'          => new sfWidgetFormInputText(),
      'apply_user_id'  => new sfWidgetFormInputText(),
      'type'           => new sfWidgetFormInputText(),
      'activity_type'  => new sfWidgetFormInputText(),
      'for_what'       => new sfWidgetFormInputText(),
      'verify_user_id' => new sfWidgetFormInputText(),
      'stime'          => new sfWidgetFormInputText(),
      'etime'          => new sfWidgetFormInputText(),
      'amount'         => new sfWidgetFormInputText(),
      'status'         => new sfWidgetFormInputText(),
      'is_delete'      => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'apply_user_id'  => new sfValidatorInteger(array('required' => false)),
      'type'           => new sfValidatorInteger(array('required' => false)),
      'activity_type'  => new sfValidatorInteger(array('required' => false)),
      'for_what'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'verify_user_id' => new sfValidatorInteger(array('required' => false)),
      'stime'          => new sfValidatorInteger(array('required' => false)),
      'etime'          => new sfValidatorInteger(array('required' => false)),
      'amount'         => new sfValidatorInteger(array('required' => false)),
      'status'         => new sfValidatorInteger(array('required' => false)),
      'is_delete'      => new sfValidatorInteger(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_lipinka[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdLipinka';
  }

}
