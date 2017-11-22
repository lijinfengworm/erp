<?php

/**
 * KllKolMonthAccountLog form base class.
 *
 * @method KllKolMonthAccountLog getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllKolMonthAccountLogForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'kol_id'            => new sfWidgetFormInputText(),
      'user_name'         => new sfWidgetFormInputText(),
      'account'           => new sfWidgetFormInputText(),
      'order_total_price' => new sfWidgetFormInputText(),
      'commision_price'   => new sfWidgetFormInputText(),
      'type'              => new sfWidgetFormInputText(),
      'month'             => new sfWidgetFormInputText(),
      'year'              => new sfWidgetFormInputText(),
      'ct_time'           => new sfWidgetFormDateTime(),
      'channel_id'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'kol_id'            => new sfValidatorInteger(array('required' => false)),
      'user_name'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'account'           => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'order_total_price' => new sfValidatorNumber(array('required' => false)),
      'commision_price'   => new sfValidatorNumber(array('required' => false)),
      'type'              => new sfValidatorPass(array('required' => false)),
      'month'             => new sfValidatorPass(array('required' => false)),
      'year'              => new sfValidatorPass(array('required' => false)),
      'ct_time'           => new sfValidatorDateTime(array('required' => false)),
      'channel_id'        => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_kol_month_account_log[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllKolMonthAccountLog';
  }

}
