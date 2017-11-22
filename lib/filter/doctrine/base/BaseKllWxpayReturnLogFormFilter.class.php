<?php

/**
 * KllWxpayReturnLog filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllWxpayReturnLogFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number'   => new sfWidgetFormFilterInput(),
      'bank_type'      => new sfWidgetFormFilterInput(),
      'fee_type'       => new sfWidgetFormFilterInput(),
      'trade_type'     => new sfWidgetFormFilterInput(),
      'ct_time'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'transaction_id' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'order_number'   => new sfValidatorPass(array('required' => false)),
      'bank_type'      => new sfValidatorPass(array('required' => false)),
      'fee_type'       => new sfValidatorPass(array('required' => false)),
      'trade_type'     => new sfValidatorPass(array('required' => false)),
      'ct_time'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'transaction_id' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_wxpay_return_log_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllWxpayReturnLog';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'order_number'   => 'Text',
      'bank_type'      => 'Text',
      'fee_type'       => 'Text',
      'trade_type'     => 'Text',
      'ct_time'        => 'Date',
      'transaction_id' => 'Text',
    );
  }
}
