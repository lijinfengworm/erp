<?php

/**
 * KllItemCountLog form base class.
 *
 * @method KllItemCountLog getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllItemCountLogForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'item_id' => new sfWidgetFormInputText(),
      'pv'      => new sfWidgetFormInputText(),
      'uv'      => new sfWidgetFormInputText(),
      'time'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'item_id' => new sfValidatorInteger(array('required' => false)),
      'pv'      => new sfValidatorInteger(array('required' => false)),
      'uv'      => new sfValidatorInteger(array('required' => false)),
      'time'    => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_item_count_log[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllItemCountLog';
  }

}
