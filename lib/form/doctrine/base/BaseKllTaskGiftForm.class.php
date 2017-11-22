<?php

/**
 * KllTaskGift form base class.
 *
 * @method KllTaskGift getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllTaskGiftForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'user_id' => new sfWidgetFormInputText(),
      'type'    => new sfWidgetFormInputText(),
      'task'    => new sfWidgetFormInputText(),
      'ct_time' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id' => new sfValidatorInteger(array('required' => false)),
      'type'    => new sfValidatorPass(array('required' => false)),
      'task'    => new sfValidatorPass(array('required' => false)),
      'ct_time' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_task_gift[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllTaskGift';
  }

}
