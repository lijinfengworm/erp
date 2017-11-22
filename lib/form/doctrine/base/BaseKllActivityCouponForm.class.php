<?php

/**
 * KllActivityCoupon form base class.
 *
 * @method KllActivityCoupon getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllActivityCouponForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'user_id'         => new sfWidgetFormInputText(),
      'open_id'         => new sfWidgetFormInputText(),
      'mobile'          => new sfWidgetFormInputText(),
      'activity_id'     => new sfWidgetFormInputText(),
      'user_status'     => new sfWidgetFormInputText(),
      'user_activation' => new sfWidgetFormInputText(),
      'is_new'          => new sfWidgetFormInputText(),
      'create_time'     => new sfWidgetFormInputText(),
      'inviter'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id'         => new sfValidatorInteger(array('required' => false)),
      'open_id'         => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'mobile'          => new sfValidatorPass(array('required' => false)),
      'activity_id'     => new sfValidatorPass(array('required' => false)),
      'user_status'     => new sfValidatorPass(array('required' => false)),
      'user_activation' => new sfValidatorPass(array('required' => false)),
      'is_new'          => new sfValidatorPass(array('required' => false)),
      'create_time'     => new sfValidatorInteger(array('required' => false)),
      'inviter'         => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_activity_coupon[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllActivityCoupon';
  }

}
