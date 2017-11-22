<?php

/**
 * TrdMartOrder form base class.
 *
 * @method TrdMartOrder getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdMartOrderForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'mart_order_number' => new sfWidgetFormInputText(),
      'ibilling_number'   => new sfWidgetFormInputText(),
      'pay_price'         => new sfWidgetFormInputText(),
      'mart_price'        => new sfWidgetFormInputText(),
      'attr'              => new sfWidgetFormTextarea(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'mart_order_number' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'ibilling_number'   => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'pay_price'         => new sfValidatorNumber(array('required' => false)),
      'mart_price'        => new sfValidatorNumber(array('required' => false)),
      'attr'              => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_mart_order[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdMartOrder';
  }

}
