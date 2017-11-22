<?php

/**
 * KllCardmultiple form base class.
 *
 * @method KllCardmultiple getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllCardmultipleForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'code'          => new sfWidgetFormInputText(),
      'title'         => new sfWidgetFormInputText(),
      'hupu_uid'      => new sfWidgetFormInputText(),
      'hupu_username' => new sfWidgetFormInputText(),
      'status'        => new sfWidgetFormInputText(),
      'cardware_code' => new sfWidgetFormInputText(),
      'is_success'    => new sfWidgetFormInputText(),
      'alert_num'     => new sfWidgetFormInputText(),
      'is_alert'      => new sfWidgetFormInputText(),
      'phone'         => new sfWidgetFormInputText(),
      'card_number'   => new sfWidgetFormInputText(),
      'read_number'   => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'code'          => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'title'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'hupu_uid'      => new sfValidatorInteger(array('required' => false)),
      'hupu_username' => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'status'        => new sfValidatorInteger(array('required' => false)),
      'cardware_code' => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'is_success'    => new sfValidatorInteger(array('required' => false)),
      'alert_num'     => new sfValidatorInteger(array('required' => false)),
      'is_alert'      => new sfValidatorInteger(array('required' => false)),
      'phone'         => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'card_number'   => new sfValidatorInteger(array('required' => false)),
      'read_number'   => new sfValidatorInteger(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kll_cardmultiple[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllCardmultiple';
  }

}
