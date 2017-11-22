<?php

/**
 * TrdOrder filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number'          => new sfWidgetFormFilterInput(),
      'ibilling_number'       => new sfWidgetFormFilterInput(),
      'title'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'product_id'            => new sfWidgetFormFilterInput(),
      'gid'                   => new sfWidgetFormFilterInput(),
      'goods_id'              => new sfWidgetFormFilterInput(),
      'mart_order_number'     => new sfWidgetFormFilterInput(),
      'mart_order_time'       => new sfWidgetFormFilterInput(),
      'mart_express_number'   => new sfWidgetFormFilterInput(),
      'mart_express_time'     => new sfWidgetFormFilterInput(),
      'domestic_express_type' => new sfWidgetFormFilterInput(),
      'domestic_order_number' => new sfWidgetFormFilterInput(),
      'domestic_express_time' => new sfWidgetFormFilterInput(),
      'attr'                  => new sfWidgetFormFilterInput(),
      'business'              => new sfWidgetFormFilterInput(),
      'business_account'      => new sfWidgetFormFilterInput(),
      'storage_status'        => new sfWidgetFormFilterInput(),
      'express_fee'           => new sfWidgetFormFilterInput(),
      'total_price'           => new sfWidgetFormFilterInput(),
      'price'                 => new sfWidgetFormFilterInput(),
      'marketing_fee'         => new sfWidgetFormFilterInput(),
      'refund_price'          => new sfWidgetFormFilterInput(),
      'refund_express_fee'    => new sfWidgetFormFilterInput(),
      'refund'                => new sfWidgetFormFilterInput(),
      'refund_remark'         => new sfWidgetFormFilterInput(),
      'order_time'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'storage_time'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'pay_time'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'pay_type'              => new sfWidgetFormFilterInput(),
      'refund_time'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'status'                => new sfWidgetFormFilterInput(),
      'operations_status'     => new sfWidgetFormFilterInput(),
      'is_plugin_added'       => new sfWidgetFormFilterInput(),
      'pay_status'            => new sfWidgetFormFilterInput(),
      'source'                => new sfWidgetFormFilterInput(),
      'channel'               => new sfWidgetFormFilterInput(),
      'hupu_uid'              => new sfWidgetFormFilterInput(),
      'hupu_username'         => new sfWidgetFormFilterInput(),
      'grant_uid'             => new sfWidgetFormFilterInput(),
      'grant_username'        => new sfWidgetFormFilterInput(),
      'grab_order_time'       => new sfWidgetFormFilterInput(),
      'finish_order_time'     => new sfWidgetFormFilterInput(),
      'forecast'              => new sfWidgetFormFilterInput(),
      'is_comment'            => new sfWidgetFormFilterInput(),
      'delivery_type'         => new sfWidgetFormFilterInput(),
      'mobile'                => new sfWidgetFormFilterInput(),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'order_number'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ibilling_number'       => new sfValidatorPass(array('required' => false)),
      'title'                 => new sfValidatorPass(array('required' => false)),
      'product_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'gid'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'goods_id'              => new sfValidatorPass(array('required' => false)),
      'mart_order_number'     => new sfValidatorPass(array('required' => false)),
      'mart_order_time'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'mart_express_number'   => new sfValidatorPass(array('required' => false)),
      'mart_express_time'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'domestic_express_type' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'domestic_order_number' => new sfValidatorPass(array('required' => false)),
      'domestic_express_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'attr'                  => new sfValidatorPass(array('required' => false)),
      'business'              => new sfValidatorPass(array('required' => false)),
      'business_account'      => new sfValidatorPass(array('required' => false)),
      'storage_status'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'express_fee'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'total_price'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'price'                 => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'marketing_fee'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'refund_price'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'refund_express_fee'    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'refund'                => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'refund_remark'         => new sfValidatorPass(array('required' => false)),
      'order_time'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'storage_time'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'pay_time'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'pay_type'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'refund_time'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'status'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'operations_status'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_plugin_added'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pay_status'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'source'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'channel'               => new sfValidatorPass(array('required' => false)),
      'hupu_uid'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username'         => new sfValidatorPass(array('required' => false)),
      'grant_uid'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'grant_username'        => new sfValidatorPass(array('required' => false)),
      'grab_order_time'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'finish_order_time'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'forecast'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_comment'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'delivery_type'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'mobile'                => new sfValidatorPass(array('required' => false)),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdOrder';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'order_number'          => 'Number',
      'ibilling_number'       => 'Text',
      'title'                 => 'Text',
      'product_id'            => 'Number',
      'gid'                   => 'Number',
      'goods_id'              => 'Text',
      'mart_order_number'     => 'Text',
      'mart_order_time'       => 'Number',
      'mart_express_number'   => 'Text',
      'mart_express_time'     => 'Number',
      'domestic_express_type' => 'Number',
      'domestic_order_number' => 'Text',
      'domestic_express_time' => 'Number',
      'attr'                  => 'Text',
      'business'              => 'Text',
      'business_account'      => 'Text',
      'storage_status'        => 'Number',
      'express_fee'           => 'Number',
      'total_price'           => 'Number',
      'price'                 => 'Number',
      'marketing_fee'         => 'Number',
      'refund_price'          => 'Number',
      'refund_express_fee'    => 'Number',
      'refund'                => 'Number',
      'refund_remark'         => 'Text',
      'order_time'            => 'Date',
      'storage_time'          => 'Date',
      'pay_time'              => 'Date',
      'pay_type'              => 'Number',
      'refund_time'           => 'Date',
      'status'                => 'Number',
      'operations_status'     => 'Number',
      'is_plugin_added'       => 'Number',
      'pay_status'            => 'Number',
      'source'                => 'Number',
      'channel'               => 'Text',
      'hupu_uid'              => 'Number',
      'hupu_username'         => 'Text',
      'grant_uid'             => 'Number',
      'grant_username'        => 'Text',
      'grab_order_time'       => 'Number',
      'finish_order_time'     => 'Number',
      'forecast'              => 'Number',
      'is_comment'            => 'Number',
      'delivery_type'         => 'Number',
      'mobile'                => 'Text',
      'created_at'            => 'Date',
      'updated_at'            => 'Date',
    );
  }
}
