<?php

/**
 * KllOrder form base class.
 *
 * @method KllOrder getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllOrderForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'order_number'       => new sfWidgetFormInputText(),
      'child_order_number' => new sfWidgetFormInputText(),
      'name'               => new sfWidgetFormInputText(),
      'description'        => new sfWidgetFormInputText(),
      'receiver'           => new sfWidgetFormInputText(),
      'product_code'       => new sfWidgetFormInputText(),
      'product_id'         => new sfWidgetFormInputText(),
      'goods_id'           => new sfWidgetFormInputText(),
      'total_price'        => new sfWidgetFormInputText(),
      'pay_time'           => new sfWidgetFormInputText(),
      'pay_status'         => new sfWidgetFormInputText(),
      'price'              => new sfWidgetFormInputText(),
      'number'             => new sfWidgetFormInputText(),
      'update_time'        => new sfWidgetFormInputText(),
      'creat_time'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number'       => new sfValidatorPass(array('required' => false)),
      'child_order_number' => new sfValidatorPass(array('required' => false)),
      'name'               => new sfValidatorPass(array('required' => false)),
      'description'        => new sfValidatorPass(array('required' => false)),
      'receiver'           => new sfValidatorPass(array('required' => false)),
      'product_code'       => new sfValidatorPass(array('required' => false)),
      'product_id'         => new sfValidatorPass(array('required' => false)),
      'goods_id'           => new sfValidatorPass(array('required' => false)),
      'total_price'        => new sfValidatorNumber(array('required' => false)),
      'pay_time'           => new sfValidatorPass(array('required' => false)),
      'pay_status'         => new sfValidatorPass(array('required' => false)),
      'price'              => new sfValidatorNumber(array('required' => false)),
      'number'             => new sfValidatorPass(array('required' => false)),
      'update_time'        => new sfValidatorPass(array('required' => false)),
      'creat_time'         => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_order[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllOrder';
  }

}
