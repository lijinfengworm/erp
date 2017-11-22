<?php

/**
 * TrdAppActivityReceived form base class.
 *
 * @method TrdAppActivityReceived getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdAppActivityReceivedForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'activity_id'   => new sfWidgetFormInputText(),
      'detail_id'     => new sfWidgetFormInputText(),
      'account'       => new sfWidgetFormInputText(),
      'mobile'        => new sfWidgetFormInputText(),
      'start_time'    => new sfWidgetFormInputText(),
      'end_time'      => new sfWidgetFormInputText(),
      'hupu_uid'      => new sfWidgetFormInputText(),
      'hupu_username' => new sfWidgetFormInputText(),
      'received_time' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'activity_id'   => new sfValidatorInteger(),
      'detail_id'     => new sfValidatorInteger(),
      'account'       => new sfValidatorString(array('max_length' => 30)),
      'mobile'        => new sfValidatorString(array('max_length' => 16, 'required' => false)),
      'start_time'    => new sfValidatorPass(array('required' => false)),
      'end_time'      => new sfValidatorPass(array('required' => false)),
      'hupu_uid'      => new sfValidatorInteger(),
      'hupu_username' => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'received_time' => new sfValidatorPass(),
    ));

    $this->widgetSchema->setNameFormat('trd_app_activity_received[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAppActivityReceived';
  }

}
