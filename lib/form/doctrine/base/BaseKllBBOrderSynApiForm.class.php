<?php

/**
 * KllBBOrderSynApi form base class.
 *
 * @method KllBBOrderSynApi getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllBBOrderSynApiForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'zt'               => new sfWidgetFormInputText(),
      'order_number'     => new sfWidgetFormInputText(),
      'send_gj'          => new sfWidgetFormInputText(),
      'send_gj_date'     => new sfWidgetFormDateTime(),
      'send_hg'          => new sfWidgetFormInputText(),
      'send_hg_date'     => new sfWidgetFormDateTime(),
      'send_zz'          => new sfWidgetFormInputText(),
      'send_zz_date'     => new sfWidgetFormDateTime(),
      'syn_date'         => new sfWidgetFormDateTime(),
      'send_yb_gj'       => new sfWidgetFormInputText(),
      'send_yb_gj_date'  => new sfWidgetFormDateTime(),
      'logisticJSON'     => new sfWidgetFormInputText(),
      'send_yb_hg'       => new sfWidgetFormInputText(),
      'send_yb_hg_date'  => new sfWidgetFormDateTime(),
      'pay_type'         => new sfWidgetFormInputText(),
      'send_nr'          => new sfWidgetFormInputText(),
      'send_nr_date'     => new sfWidgetFormDateTime(),
      'source'           => new sfWidgetFormInputText(),
      'edi_orderno'      => new sfWidgetFormInputText(),
      'send_jd_pay'      => new sfWidgetFormInputText(),
      'send_jd_pay_date' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'zt'               => new sfValidatorInteger(array('required' => false)),
      'order_number'     => new sfValidatorPass(array('required' => false)),
      'send_gj'          => new sfValidatorInteger(array('required' => false)),
      'send_gj_date'     => new sfValidatorDateTime(array('required' => false)),
      'send_hg'          => new sfValidatorInteger(array('required' => false)),
      'send_hg_date'     => new sfValidatorDateTime(array('required' => false)),
      'send_zz'          => new sfValidatorInteger(array('required' => false)),
      'send_zz_date'     => new sfValidatorDateTime(array('required' => false)),
      'syn_date'         => new sfValidatorDateTime(array('required' => false)),
      'send_yb_gj'       => new sfValidatorInteger(array('required' => false)),
      'send_yb_gj_date'  => new sfValidatorDateTime(array('required' => false)),
      'logisticJSON'     => new sfValidatorPass(array('required' => false)),
      'send_yb_hg'       => new sfValidatorInteger(array('required' => false)),
      'send_yb_hg_date'  => new sfValidatorDateTime(array('required' => false)),
      'pay_type'         => new sfValidatorInteger(array('required' => false)),
      'send_nr'          => new sfValidatorInteger(array('required' => false)),
      'send_nr_date'     => new sfValidatorDateTime(array('required' => false)),
      'source'           => new sfValidatorPass(array('required' => false)),
      'edi_orderno'      => new sfValidatorPass(array('required' => false)),
      'send_jd_pay'      => new sfValidatorInteger(array('required' => false)),
      'send_jd_pay_date' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_bb_order_syn_api[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllBBOrderSynApi';
  }

}
