<?php

/**
 * TrdHaitaoOrder filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdHaitaoOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number'           => new sfWidgetFormFilterInput(),
      'ibilling_number'        => new sfWidgetFormFilterInput(),
      'title'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'news_id'                => new sfWidgetFormFilterInput(),
      'product_id'             => new sfWidgetFormFilterInput(),
      'gid'                    => new sfWidgetFormFilterInput(),
      'hupu_uid'               => new sfWidgetFormFilterInput(),
      'hupu_username'          => new sfWidgetFormFilterInput(),
      'goods_id'               => new sfWidgetFormFilterInput(),
      'mart_order_number'      => new sfWidgetFormFilterInput(),
      'mart_express_type'      => new sfWidgetFormFilterInput(),
      'mart_express_number'    => new sfWidgetFormFilterInput(),
      'transport_type'         => new sfWidgetFormFilterInput(),
      'transport_order_number' => new sfWidgetFormFilterInput(),
      'customs_express_type'   => new sfWidgetFormFilterInput(),
      'customs_order_number'   => new sfWidgetFormFilterInput(),
      'domestic_express_type'  => new sfWidgetFormFilterInput(),
      'domestic_order_number'  => new sfWidgetFormFilterInput(),
      'domestic_express_time'  => new sfWidgetFormFilterInput(),
      'address'                => new sfWidgetFormFilterInput(),
      'address_id'             => new sfWidgetFormFilterInput(),
      'attr'                   => new sfWidgetFormFilterInput(),
      'number'                 => new sfWidgetFormFilterInput(),
      'storage_number'         => new sfWidgetFormFilterInput(),
      'price'                  => new sfWidgetFormFilterInput(),
      'express_type'           => new sfWidgetFormFilterInput(),
      'express_fee'            => new sfWidgetFormFilterInput(),
      'intl_freight'           => new sfWidgetFormFilterInput(),
      'total_price'            => new sfWidgetFormFilterInput(),
      'refund'                 => new sfWidgetFormFilterInput(),
      'refund_remark'          => new sfWidgetFormFilterInput(),
      'remark'                 => new sfWidgetFormFilterInput(),
      'order_time'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'storage_time'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'pay_time'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'refund_time'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'grant_uid'              => new sfWidgetFormFilterInput(),
      'grant_username'         => new sfWidgetFormFilterInput(),
      'refund_type'            => new sfWidgetFormFilterInput(),
      'status'                 => new sfWidgetFormFilterInput(),
      'is_plugin_added'        => new sfWidgetFormFilterInput(),
      'pay_status'             => new sfWidgetFormFilterInput(),
      'source'                 => new sfWidgetFormFilterInput(),
      'created_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'order_number'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ibilling_number'        => new sfValidatorPass(array('required' => false)),
      'title'                  => new sfValidatorPass(array('required' => false)),
      'news_id'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'product_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'gid'                    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_uid'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username'          => new sfValidatorPass(array('required' => false)),
      'goods_id'               => new sfValidatorPass(array('required' => false)),
      'mart_order_number'      => new sfValidatorPass(array('required' => false)),
      'mart_express_type'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'mart_express_number'    => new sfValidatorPass(array('required' => false)),
      'transport_type'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'transport_order_number' => new sfValidatorPass(array('required' => false)),
      'customs_express_type'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'customs_order_number'   => new sfValidatorPass(array('required' => false)),
      'domestic_express_type'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'domestic_order_number'  => new sfValidatorPass(array('required' => false)),
      'domestic_express_time'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'address'                => new sfValidatorPass(array('required' => false)),
      'address_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'attr'                   => new sfValidatorPass(array('required' => false)),
      'number'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'storage_number'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'price'                  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'express_type'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'express_fee'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'intl_freight'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'total_price'            => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'refund'                 => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'refund_remark'          => new sfValidatorPass(array('required' => false)),
      'remark'                 => new sfValidatorPass(array('required' => false)),
      'order_time'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'storage_time'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'pay_time'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'refund_time'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'grant_uid'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'grant_username'         => new sfValidatorPass(array('required' => false)),
      'refund_type'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_plugin_added'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pay_status'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'source'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_haitao_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdHaitaoOrder';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'order_number'           => 'Number',
      'ibilling_number'        => 'Text',
      'title'                  => 'Text',
      'news_id'                => 'Number',
      'product_id'             => 'Number',
      'gid'                    => 'Number',
      'hupu_uid'               => 'Number',
      'hupu_username'          => 'Text',
      'goods_id'               => 'Text',
      'mart_order_number'      => 'Text',
      'mart_express_type'      => 'Number',
      'mart_express_number'    => 'Text',
      'transport_type'         => 'Number',
      'transport_order_number' => 'Text',
      'customs_express_type'   => 'Number',
      'customs_order_number'   => 'Text',
      'domestic_express_type'  => 'Number',
      'domestic_order_number'  => 'Text',
      'domestic_express_time'  => 'Number',
      'address'                => 'Text',
      'address_id'             => 'Number',
      'attr'                   => 'Text',
      'number'                 => 'Number',
      'storage_number'         => 'Number',
      'price'                  => 'Number',
      'express_type'           => 'Number',
      'express_fee'            => 'Number',
      'intl_freight'           => 'Number',
      'total_price'            => 'Number',
      'refund'                 => 'Number',
      'refund_remark'          => 'Text',
      'remark'                 => 'Text',
      'order_time'             => 'Date',
      'storage_time'           => 'Date',
      'pay_time'               => 'Date',
      'refund_time'            => 'Date',
      'grant_uid'              => 'Number',
      'grant_username'         => 'Text',
      'refund_type'            => 'Number',
      'status'                 => 'Number',
      'is_plugin_added'        => 'Number',
      'pay_status'             => 'Number',
      'source'                 => 'Number',
      'created_at'             => 'Date',
      'updated_at'             => 'Date',
    );
  }
}
