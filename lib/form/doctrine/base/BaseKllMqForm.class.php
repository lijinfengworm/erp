<?php

/**
 * KllMq form base class.
 *
 * @method KllMq getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllMqForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'msg_type'       => new sfWidgetFormInputText(),
      'msg_id'         => new sfWidgetFormInputText(),
      'msg_channel'    => new sfWidgetFormInputText(),
      'msg_body'       => new sfWidgetFormInputText(),
      'msg_status'     => new sfWidgetFormInputText(),
      'msg_audit'      => new sfWidgetFormInputText(),
      'order_number'   => new sfWidgetFormInputText(),
      'billing_number' => new sfWidgetFormInputText(),
      'zt'             => new sfWidgetFormInputText(),
      'msg_time'       => new sfWidgetFormDateTime(),
      'msg_response'   => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'msg_type'       => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'msg_id'         => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'msg_channel'    => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'msg_body'       => new sfValidatorPass(array('required' => false)),
      'msg_status'     => new sfValidatorInteger(array('required' => false)),
      'msg_audit'      => new sfValidatorInteger(array('required' => false)),
      'order_number'   => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'billing_number' => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'zt'             => new sfValidatorInteger(array('required' => false)),
      'msg_time'       => new sfValidatorDateTime(array('required' => false)),
      'msg_response'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_mq[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllMq';
  }

}
