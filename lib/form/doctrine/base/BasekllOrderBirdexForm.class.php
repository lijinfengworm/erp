<?php

/**
 * kllOrderBirdex form base class.
 *
 * @method kllOrderBirdex getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasekllOrderBirdexForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'zt'               => new sfWidgetFormInputText(),
      'send_birdex'      => new sfWidgetFormInputText(),
      'ibilling_number'  => new sfWidgetFormInputText(),
      'order_number'     => new sfWidgetFormInputText(),
      'update_time'      => new sfWidgetFormDateTime(),
      'create_time'      => new sfWidgetFormDateTime(),
      'send_birdex_date' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'zt'               => new sfValidatorInteger(array('required' => false)),
      'send_birdex'      => new sfValidatorInteger(array('required' => false)),
      'ibilling_number'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'order_number'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'update_time'      => new sfValidatorDateTime(array('required' => false)),
      'create_time'      => new sfValidatorDateTime(array('required' => false)),
      'send_birdex_date' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_order_birdex[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'kllOrderBirdex';
  }

}
