<?php

/**
 * KllOrderSynApi filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllOrderSynApiFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'zt'                   => new sfWidgetFormFilterInput(),
      'order_number'         => new sfWidgetFormFilterInput(),
      'send_gj'              => new sfWidgetFormFilterInput(),
      'send_gj_date'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'send_hg'              => new sfWidgetFormFilterInput(),
      'send_hg_date'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'send_zz'              => new sfWidgetFormFilterInput(),
      'send_zz_date'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'syn_date'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'logisticJSON'         => new sfWidgetFormFilterInput(),
      'logisticStepInfoJSON' => new sfWidgetFormFilterInput(),
      'send_alipay'          => new sfWidgetFormFilterInput(),
      'send_alipay_date'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'zt'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'order_number'         => new sfValidatorPass(array('required' => false)),
      'send_gj'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'send_gj_date'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'send_hg'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'send_hg_date'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'send_zz'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'send_zz_date'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'syn_date'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'logisticJSON'         => new sfValidatorPass(array('required' => false)),
      'logisticStepInfoJSON' => new sfValidatorPass(array('required' => false)),
      'send_alipay'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'send_alipay_date'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_order_syn_api_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllOrderSynApi';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'zt'                   => 'Number',
      'order_number'         => 'Text',
      'send_gj'              => 'Number',
      'send_gj_date'         => 'Date',
      'send_hg'              => 'Number',
      'send_hg_date'         => 'Date',
      'send_zz'              => 'Number',
      'send_zz_date'         => 'Date',
      'syn_date'             => 'Date',
      'logisticJSON'         => 'Text',
      'logisticStepInfoJSON' => 'Text',
      'send_alipay'          => 'Number',
      'send_alipay_date'     => 'Date',
    );
  }
}
