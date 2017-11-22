<?php

/**
 * TrdCouponsList form base class.
 *
 * @method TrdCouponsList getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdCouponsListForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'title'       => new sfWidgetFormInputText(),
      'amount'      => new sfWidgetFormInputText(),
      'mall'        => new sfWidgetFormInputText(),
      'total'       => new sfWidgetFormInputText(),
      'recevied'    => new sfWidgetFormInputText(),
      'start_date'  => new sfWidgetFormInputText(),
      'expiry_date' => new sfWidgetFormInputText(),
      'img_path'    => new sfWidgetFormInputText(),
      'is_delete'   => new sfWidgetFormInputCheckbox(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'       => new sfValidatorString(array('max_length' => 50)),
      'amount'      => new sfValidatorString(array('max_length' => 20)),
      'mall'        => new sfValidatorInteger(array('required' => false)),
      'total'       => new sfValidatorInteger(array('required' => false)),
      'recevied'    => new sfValidatorInteger(array('required' => false)),
      'start_date'  => new sfValidatorPass(array('required' => false)),
      'expiry_date' => new sfValidatorPass(array('required' => false)),
      'img_path'    => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'is_delete'   => new sfValidatorBoolean(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_coupons_list[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdCouponsList';
  }

}
