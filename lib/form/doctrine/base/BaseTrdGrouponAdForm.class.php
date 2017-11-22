<?php

/**
 * TrdGrouponAd form base class.
 *
 * @method TrdGrouponAd getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdGrouponAdForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'group_id'   => new sfWidgetFormInputText(),
      'title'      => new sfWidgetFormInputText(),
      'order_id'   => new sfWidgetFormInputText(),
      'stime'      => new sfWidgetFormInputText(),
      'etime'      => new sfWidgetFormInputText(),
      'pay_type'   => new sfWidgetFormInputText(),
      'pay_date'   => new sfWidgetFormInputText(),
      'is_cancel'  => new sfWidgetFormInputText(),
      'reason'     => new sfWidgetFormInputText(),
      'status'     => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'group_id'   => new sfValidatorInteger(array('required' => false)),
      'title'      => new sfValidatorString(array('max_length' => 50)),
      'order_id'   => new sfValidatorInteger(array('required' => false)),
      'stime'      => new sfValidatorInteger(array('required' => false)),
      'etime'      => new sfValidatorInteger(array('required' => false)),
      'pay_type'   => new sfValidatorInteger(array('required' => false)),
      'pay_date'   => new sfValidatorInteger(array('required' => false)),
      'is_cancel'  => new sfValidatorInteger(array('required' => false)),
      'reason'     => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'status'     => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_groupon_ad[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGrouponAd';
  }

}
