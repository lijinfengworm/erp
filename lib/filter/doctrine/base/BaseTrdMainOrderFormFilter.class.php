<?php

/**
 * TrdMainOrder filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdMainOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number'        => new sfWidgetFormFilterInput(),
      'ibilling_number'     => new sfWidgetFormFilterInput(),
      'hupu_uid'            => new sfWidgetFormFilterInput(),
      'hupu_username'       => new sfWidgetFormFilterInput(),
      'address'             => new sfWidgetFormFilterInput(),
      'address_attr'        => new sfWidgetFormFilterInput(),
      'express_fee'         => new sfWidgetFormFilterInput(),
      'total_price'         => new sfWidgetFormFilterInput(),
      'original_price'      => new sfWidgetFormFilterInput(),
      'coupon_fee'          => new sfWidgetFormFilterInput(),
      'marketing_fee'       => new sfWidgetFormFilterInput(),
      'number'              => new sfWidgetFormFilterInput(),
      'remark'              => new sfWidgetFormFilterInput(),
      'order_time'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'pay_time'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'pay_type'            => new sfWidgetFormFilterInput(),
      'refund'              => new sfWidgetFormFilterInput(),
      'handle_status'       => new sfWidgetFormFilterInput(),
      'source'              => new sfWidgetFormFilterInput(),
      'status'              => new sfWidgetFormFilterInput(),
      'tax_status'          => new sfWidgetFormFilterInput(),
      'tax'                 => new sfWidgetFormFilterInput(),
      'tax_ibilling_number' => new sfWidgetFormFilterInput(),
      'tax_time'            => new sfWidgetFormFilterInput(),
      'created_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'order_number'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ibilling_number'     => new sfValidatorPass(array('required' => false)),
      'hupu_uid'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username'       => new sfValidatorPass(array('required' => false)),
      'address'             => new sfValidatorPass(array('required' => false)),
      'address_attr'        => new sfValidatorPass(array('required' => false)),
      'express_fee'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'total_price'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'original_price'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'coupon_fee'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'marketing_fee'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'number'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'remark'              => new sfValidatorPass(array('required' => false)),
      'order_time'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'pay_time'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'pay_type'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'refund'              => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'handle_status'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'source'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tax_status'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tax'                 => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'tax_ibilling_number' => new sfValidatorPass(array('required' => false)),
      'tax_time'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_main_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdMainOrder';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'order_number'        => 'Number',
      'ibilling_number'     => 'Text',
      'hupu_uid'            => 'Number',
      'hupu_username'       => 'Text',
      'address'             => 'Text',
      'address_attr'        => 'Text',
      'express_fee'         => 'Number',
      'total_price'         => 'Number',
      'original_price'      => 'Number',
      'coupon_fee'          => 'Number',
      'marketing_fee'       => 'Number',
      'number'              => 'Number',
      'remark'              => 'Text',
      'order_time'          => 'Date',
      'pay_time'            => 'Date',
      'pay_type'            => 'Number',
      'refund'              => 'Number',
      'handle_status'       => 'Number',
      'source'              => 'Number',
      'status'              => 'Number',
      'tax_status'          => 'Number',
      'tax'                 => 'Number',
      'tax_ibilling_number' => 'Text',
      'tax_time'            => 'Number',
      'created_at'          => 'Date',
      'updated_at'          => 'Date',
    );
  }
}
