<?php

/**
 * KllItemTradelog form base class.
 *
 * @method KllItemTradelog getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllItemTradelogForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'product_id'   => new sfWidgetFormInputText(),
      'username'     => new sfWidgetFormInputText(),
      'attr'         => new sfWidgetFormInputText(),
      'order_id'     => new sfWidgetFormInputText(),
      'num'          => new sfWidgetFormInputText(),
      'created_time' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'product_id'   => new sfValidatorInteger(array('required' => false)),
      'username'     => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'attr'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'order_id'     => new sfValidatorInteger(array('required' => false)),
      'num'          => new sfValidatorInteger(array('required' => false)),
      'created_time' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_item_tradelog[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllItemTradelog';
  }

}
