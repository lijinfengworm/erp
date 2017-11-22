<?php

/**
 * KllXbuyItemLog form base class.
 *
 * @method KllXbuyItemLog getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllXbuyItemLogForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'uid'         => new sfWidgetFormInputText(),
      'activity_id' => new sfWidgetFormInputText(),
      'item_id'     => new sfWidgetFormInputText(),
      'number'      => new sfWidgetFormInputText(),
      'ct_time'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'uid'         => new sfValidatorInteger(array('required' => false)),
      'activity_id' => new sfValidatorInteger(array('required' => false)),
      'item_id'     => new sfValidatorInteger(array('required' => false)),
      'number'      => new sfValidatorInteger(array('required' => false)),
      'ct_time'     => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_xbuy_item_log[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllXbuyItemLog';
  }

}
