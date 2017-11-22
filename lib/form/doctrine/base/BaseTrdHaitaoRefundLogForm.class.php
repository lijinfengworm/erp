<?php

/**
 * TrdHaitaoRefundLog form base class.
 *
 * @method TrdHaitaoRefundLog getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdHaitaoRefundLogForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'title'             => new sfWidgetFormInputText(),
      'order_number_attr' => new sfWidgetFormTextarea(),
      'callback_attr'     => new sfWidgetFormTextarea(),
      'grant_uid'         => new sfWidgetFormInputText(),
      'grant_username'    => new sfWidgetFormInputText(),
      'status'            => new sfWidgetFormInputText(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'             => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'order_number_attr' => new sfValidatorString(array('max_length' => 10000, 'required' => false)),
      'callback_attr'     => new sfValidatorString(array('max_length' => 10000, 'required' => false)),
      'grant_uid'         => new sfValidatorInteger(array('required' => false)),
      'grant_username'    => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'status'            => new sfValidatorInteger(array('required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_haitao_refund_log[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdHaitaoRefundLog';
  }

}
