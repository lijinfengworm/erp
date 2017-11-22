<?php

/**
 * KaluliRefundApply form base class.
 *
 * @method KaluliRefundApply getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKaluliRefundApplyForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'order_number'   => new sfWidgetFormInputText(),
      'order_id'       => new sfWidgetFormInputText(),
      'refund'         => new sfWidgetFormInputText(),
      'refund_remark'  => new sfWidgetFormTextarea(),
      'check_remark'   => new sfWidgetFormTextarea(),
      'express_type'   => new sfWidgetFormInputText(),
      'express_number' => new sfWidgetFormInputText(),
      'pic_attr'       => new sfWidgetFormTextarea(),
      'type'           => new sfWidgetFormInputText(),
      'status'         => new sfWidgetFormInputText(),
      'grant_uid'      => new sfWidgetFormInputText(),
      'grant_username' => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number'   => new sfValidatorInteger(array('required' => false)),
      'order_id'       => new sfValidatorInteger(array('required' => false)),
      'refund'         => new sfValidatorNumber(array('required' => false)),
      'refund_remark'  => new sfValidatorString(array('max_length' => 300, 'required' => false)),
      'check_remark'   => new sfValidatorString(array('max_length' => 300, 'required' => false)),
      'express_type'   => new sfValidatorInteger(array('required' => false)),
      'express_number' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'pic_attr'       => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'type'           => new sfValidatorInteger(array('required' => false)),
      'status'         => new sfValidatorInteger(array('required' => false)),
      'grant_uid'      => new sfValidatorInteger(array('required' => false)),
      'grant_username' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kaluli_refund_apply[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliRefundApply';
  }

}
