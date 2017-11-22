<?php

/**
 * KaluliMainOrderAttr form base class.
 *
 * @method KaluliMainOrderAttr getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKaluliMainOrderAttrForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'order_number'       => new sfWidgetFormInputText(),
      'refund_price'       => new sfWidgetFormInputText(),
      'refund_express_fee' => new sfWidgetFormInputText(),
      'address_attr'       => new sfWidgetFormTextarea(),
      'is_remind'          => new sfWidgetFormInputText(),
      'remark'             => new sfWidgetFormInputText(),
      'coupon_id'          => new sfWidgetFormInputText(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number'       => new sfValidatorInteger(array('required' => false)),
      'refund_price'       => new sfValidatorNumber(array('required' => false)),
      'refund_express_fee' => new sfValidatorNumber(array('required' => false)),
      'address_attr'       => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'is_remind'          => new sfValidatorInteger(array('required' => false)),
      'remark'             => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'coupon_id'          => new sfValidatorInteger(array('required' => false)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'KaluliMainOrderAttr', 'column' => array('order_number')))
    );

    $this->widgetSchema->setNameFormat('kaluli_main_order_attr[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliMainOrderAttr';
  }

}
