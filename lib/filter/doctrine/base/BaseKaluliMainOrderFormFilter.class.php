<?php

/**
 * KaluliMainOrder filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKaluliMainOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number'    => new sfWidgetFormFilterInput(),
      'ibilling_number' => new sfWidgetFormFilterInput(),
      'hupu_uid'        => new sfWidgetFormFilterInput(),
      'hupu_username'   => new sfWidgetFormFilterInput(),
      'express_fee'     => new sfWidgetFormFilterInput(),
      'total_price'     => new sfWidgetFormFilterInput(),
      'original_price'  => new sfWidgetFormFilterInput(),
      'marketing_fee'   => new sfWidgetFormFilterInput(),
      'coupon_fee'      => new sfWidgetFormFilterInput(),
      'duty_fee'        => new sfWidgetFormFilterInput(),
      'number'          => new sfWidgetFormFilterInput(),
      'order_time'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'pay_time'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'pay_type'        => new sfWidgetFormFilterInput(),
      'refund'          => new sfWidgetFormFilterInput(),
      'source'          => new sfWidgetFormFilterInput(),
      'status'          => new sfWidgetFormFilterInput(),
      'trade_no'        => new sfWidgetFormFilterInput(),
      'is_activity'     => new sfWidgetFormFilterInput(),
      'finance_audit'   => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'order_number'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ibilling_number' => new sfValidatorPass(array('required' => false)),
      'hupu_uid'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username'   => new sfValidatorPass(array('required' => false)),
      'express_fee'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'total_price'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'original_price'  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'marketing_fee'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'coupon_fee'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'duty_fee'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'number'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'order_time'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'pay_time'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'pay_type'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'refund'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'source'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'trade_no'        => new sfValidatorPass(array('required' => false)),
      'is_activity'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'finance_audit'   => new sfValidatorPass(array('required' => false)),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kaluli_main_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliMainOrder';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'order_number'    => 'Number',
      'ibilling_number' => 'Text',
      'hupu_uid'        => 'Number',
      'hupu_username'   => 'Text',
      'express_fee'     => 'Number',
      'total_price'     => 'Number',
      'original_price'  => 'Number',
      'marketing_fee'   => 'Number',
      'coupon_fee'      => 'Number',
      'duty_fee'        => 'Number',
      'number'          => 'Number',
      'order_time'      => 'Date',
      'pay_time'        => 'Date',
      'pay_type'        => 'Number',
      'refund'          => 'Number',
      'source'          => 'Number',
      'status'          => 'Number',
      'trade_no'        => 'Text',
      'is_activity'     => 'Number',
      'finance_audit'   => 'Text',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
