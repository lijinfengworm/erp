<?php

/**
 * KllSendCouponOrder form base class.
 *
 * @method KllSendCouponOrder getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllSendCouponOrderForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'opt_uid'    => new sfWidgetFormInputText(),
      'title'      => new sfWidgetFormInputText(),
      'detail'     => new sfWidgetFormInputText(),
      'position'   => new sfWidgetFormInputText(),
      'record_id'  => new sfWidgetFormInputText(),
      'state'      => new sfWidgetFormInputText(),
      's_time'     => new sfWidgetFormInputText(),
      'e_time'     => new sfWidgetFormInputText(),
      'channel_id' => new sfWidgetFormInputText(),
      'type'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'opt_uid'    => new sfValidatorInteger(array('required' => false)),
      'title'      => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'detail'     => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'position'   => new sfValidatorInteger(array('required' => false)),
      'record_id'  => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'state'      => new sfValidatorInteger(array('required' => false)),
      's_time'     => new sfValidatorPass(array('required' => false)),
      'e_time'     => new sfValidatorPass(array('required' => false)),
      'channel_id' => new sfValidatorInteger(array('required' => false)),
      'type'       => new sfValidatorString(array('max_length' => 50, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_send_coupon_order[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllSendCouponOrder';
  }

}
