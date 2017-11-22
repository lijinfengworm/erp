<?php

/**
 * KaluliOrderAttr form base class.
 *
 * @method KaluliOrderAttr getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKaluliOrderAttrForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'order_number'       => new sfWidgetFormInputText(),
      'order_id'           => new sfWidgetFormInputText(),
      'code'               => new sfWidgetFormInputText(),
      'attr'               => new sfWidgetFormTextarea(),
      'refund_price'       => new sfWidgetFormInputText(),
      'refund_express_fee' => new sfWidgetFormInputText(),
      'refund'             => new sfWidgetFormInputText(),
      'ware_type'          => new sfWidgetFormInputText(),
      'ware_id'            => new sfWidgetFormInputText(),
      'ware_code'          => new sfWidgetFormInputText(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number'       => new sfValidatorInteger(array('required' => false)),
      'order_id'           => new sfValidatorInteger(array('required' => false)),
      'code'               => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'attr'               => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'refund_price'       => new sfValidatorNumber(array('required' => false)),
      'refund_express_fee' => new sfValidatorNumber(array('required' => false)),
      'refund'             => new sfValidatorNumber(array('required' => false)),
      'ware_type'          => new sfValidatorInteger(),
      'ware_id'            => new sfValidatorInteger(),
      'ware_code'          => new sfValidatorString(array('max_length' => 20)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kaluli_order_attr[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliOrderAttr';
  }

}
