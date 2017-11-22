<?php

/**
 * KllSendMsg form base class.
 *
 * @method KllSendMsg getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllSendMsgForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'mobile'  => new sfWidgetFormInputText(),
      'opt_uid' => new sfWidgetFormInputText(),
      'nums'    => new sfWidgetFormInputText(),
      'stime'   => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'mobile'  => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'opt_uid' => new sfValidatorInteger(array('required' => false)),
      'nums'    => new sfValidatorInteger(array('required' => false)),
      'stime'   => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_send_msg[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllSendMsg';
  }

}
