<?php

/**
 * TrdMartOrderInfo filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdMartOrderInfoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'mart_order_id'         => new sfWidgetFormFilterInput(),
      'account'               => new sfWidgetFormFilterInput(),
      'business'              => new sfWidgetFormFilterInput(),
      'total_price'           => new sfWidgetFormFilterInput(),
      'shipping_price'        => new sfWidgetFormFilterInput(),
      'receive_price'         => new sfWidgetFormFilterInput(),
      'refund_price'          => new sfWidgetFormFilterInput(),
      'full_name'             => new sfWidgetFormFilterInput(),
      'order_time'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'sh_order_time'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'sh_order_id'           => new sfWidgetFormFilterInput(),
      'sh_shipping_price'     => new sfWidgetFormFilterInput(),
      'sh_coupon_fee'         => new sfWidgetFormFilterInput(),
      'sh_price'              => new sfWidgetFormFilterInput(),
      'sh_marketing_fee'      => new sfWidgetFormFilterInput(),
      'sh_refund_price'       => new sfWidgetFormFilterInput(),
      'sh_refund_express_fee' => new sfWidgetFormFilterInput(),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'mart_order_id'         => new sfValidatorPass(array('required' => false)),
      'account'               => new sfValidatorPass(array('required' => false)),
      'business'              => new sfValidatorPass(array('required' => false)),
      'total_price'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'shipping_price'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'receive_price'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'refund_price'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'full_name'             => new sfValidatorPass(array('required' => false)),
      'order_time'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'sh_order_time'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'sh_order_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sh_shipping_price'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'sh_coupon_fee'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'sh_price'              => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'sh_marketing_fee'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'sh_refund_price'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'sh_refund_express_fee' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_mart_order_info_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdMartOrderInfo';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'mart_order_id'         => 'Text',
      'account'               => 'Text',
      'business'              => 'Text',
      'total_price'           => 'Number',
      'shipping_price'        => 'Number',
      'receive_price'         => 'Number',
      'refund_price'          => 'Number',
      'full_name'             => 'Text',
      'order_time'            => 'Date',
      'sh_order_time'         => 'Date',
      'sh_order_id'           => 'Number',
      'sh_shipping_price'     => 'Number',
      'sh_coupon_fee'         => 'Number',
      'sh_price'              => 'Number',
      'sh_marketing_fee'      => 'Number',
      'sh_refund_price'       => 'Number',
      'sh_refund_express_fee' => 'Number',
      'created_at'            => 'Date',
      'updated_at'            => 'Date',
    );
  }
}
