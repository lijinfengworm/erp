<?php

/**
 * TrdCouponsRecevied form base class.
 *
 * @method TrdCouponsRecevied getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdCouponsReceviedForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'root_type'     => new sfWidgetFormInputText(),
      'activity_id'   => new sfWidgetFormInputText(),
      'type'          => new sfWidgetFormInputText(),
      'list_id'       => new sfWidgetFormInputText(),
      'detail_id'     => new sfWidgetFormInputText(),
      'account'       => new sfWidgetFormInputText(),
      'pass'          => new sfWidgetFormInputText(),
      'mobile'        => new sfWidgetFormInputText(),
      'stime'         => new sfWidgetFormInputText(),
      'etime'         => new sfWidgetFormInputText(),
      'hupu_uid'      => new sfWidgetFormInputText(),
      'hupu_username' => new sfWidgetFormInputText(),
      'recevied_date' => new sfWidgetFormInputText(),
      'status'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'root_type'     => new sfValidatorInteger(array('required' => false)),
      'activity_id'   => new sfValidatorInteger(array('required' => false)),
      'type'          => new sfValidatorInteger(array('required' => false)),
      'list_id'       => new sfValidatorInteger(array('required' => false)),
      'detail_id'     => new sfValidatorInteger(array('required' => false)),
      'account'       => new sfValidatorString(array('max_length' => 30)),
      'pass'          => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'mobile'        => new sfValidatorString(array('max_length' => 16, 'required' => false)),
      'stime'         => new sfValidatorInteger(array('required' => false)),
      'etime'         => new sfValidatorInteger(array('required' => false)),
      'hupu_uid'      => new sfValidatorInteger(array('required' => false)),
      'hupu_username' => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'recevied_date' => new sfValidatorInteger(array('required' => false)),
      'status'        => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_coupons_recevied[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdCouponsRecevied';
  }

}
