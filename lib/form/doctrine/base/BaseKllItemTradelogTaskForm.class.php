<?php

/**
 * KllItemTradelogTask form base class.
 *
 * @method KllItemTradelogTask getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllItemTradelogTaskForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'product_id'   => new sfWidgetFormInputText(),
      'total_num'    => new sfWidgetFormInputText(),
      'current_num'  => new sfWidgetFormInputText(),
      'end_time'     => new sfWidgetFormInputText(),
      'updated_time' => new sfWidgetFormInputText(),
      'status'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'product_id'   => new sfValidatorInteger(array('required' => false)),
      'total_num'    => new sfValidatorInteger(array('required' => false)),
      'current_num'  => new sfValidatorInteger(array('required' => false)),
      'end_time'     => new sfValidatorInteger(array('required' => false)),
      'updated_time' => new sfValidatorInteger(array('required' => false)),
      'status'       => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_item_tradelog_task[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllItemTradelogTask';
  }

}
