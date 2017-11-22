<?php

/**
 * KllXbuyItem form base class.
 *
 * @method KllXbuyItem getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllXbuyItemForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'activity_id'  => new sfWidgetFormInputText(),
      'item_id'      => new sfWidgetFormInputText(),
      'number'       => new sfWidgetFormInputText(),
      'price'        => new sfWidgetFormInputText(),
      'status'       => new sfWidgetFormInputText(),
      'title'        => new sfWidgetFormInputText(),
      'origin_price' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'activity_id'  => new sfValidatorInteger(array('required' => false)),
      'item_id'      => new sfValidatorInteger(array('required' => false)),
      'number'       => new sfValidatorInteger(array('required' => false)),
      'price'        => new sfValidatorNumber(array('required' => false)),
      'status'       => new sfValidatorInteger(array('required' => false)),
      'title'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'origin_price' => new sfValidatorNumber(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_xbuy_item[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllXbuyItem';
  }

}
