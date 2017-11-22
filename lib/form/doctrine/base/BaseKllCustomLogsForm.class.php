<?php

/**
 * KllCustomLogs form base class.
 *
 * @method KllCustomLogs getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllCustomLogsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'contents'     => new sfWidgetFormInputText(),
      'opt_uid'      => new sfWidgetFormInputText(),
      'order_number' => new sfWidgetFormInputText(),
      'stime'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'contents'     => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'opt_uid'      => new sfValidatorInteger(array('required' => false)),
      'order_number' => new sfValidatorInteger(array('required' => false)),
      'stime'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_custom_logs[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllCustomLogs';
  }

}
