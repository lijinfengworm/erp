<?php

/**
 * KllOrderSynApi form base class.
 *
 * @method KllOrderSynApi getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllOrderSynApiForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'zt'                   => new sfWidgetFormInputText(),
      'order_number'         => new sfWidgetFormInputText(),
      'send_gj'              => new sfWidgetFormInputText(),
      'send_gj_date'         => new sfWidgetFormDateTime(),
      'send_hg'              => new sfWidgetFormInputText(),
      'send_hg_date'         => new sfWidgetFormDateTime(),
      'send_zz'              => new sfWidgetFormInputText(),
      'send_zz_date'         => new sfWidgetFormDateTime(),
      'syn_date'             => new sfWidgetFormDateTime(),
      'logisticJSON'         => new sfWidgetFormInputText(),
      'logisticStepInfoJSON' => new sfWidgetFormInputText(),
      'send_alipay'          => new sfWidgetFormInputText(),
      'send_alipay_date'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'zt'                   => new sfValidatorInteger(array('required' => false)),
      'order_number'         => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'send_gj'              => new sfValidatorInteger(array('required' => false)),
      'send_gj_date'         => new sfValidatorDateTime(array('required' => false)),
      'send_hg'              => new sfValidatorInteger(array('required' => false)),
      'send_hg_date'         => new sfValidatorDateTime(array('required' => false)),
      'send_zz'              => new sfValidatorInteger(array('required' => false)),
      'send_zz_date'         => new sfValidatorDateTime(array('required' => false)),
      'syn_date'             => new sfValidatorDateTime(array('required' => false)),
      'logisticJSON'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'logisticStepInfoJSON' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'send_alipay'          => new sfValidatorInteger(array('required' => false)),
      'send_alipay_date'     => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_order_syn_api[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllOrderSynApi';
  }

}
