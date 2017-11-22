<?php

/**
 * KaluliOrder filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKaluliOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number'          => new sfWidgetFormFilterInput(),
      'ibilling_number'       => new sfWidgetFormFilterInput(),
      'title'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'product_id'            => new sfWidgetFormFilterInput(),
      'goods_id'              => new sfWidgetFormFilterInput(),
      'domestic_express_type' => new sfWidgetFormFilterInput(),
      'domestic_order_number' => new sfWidgetFormFilterInput(),
      'domestic_express_time' => new sfWidgetFormFilterInput(),
      'is_gift'               => new sfWidgetFormFilterInput(),
      'depot_type'            => new sfWidgetFormFilterInput(),
      'express_fee'           => new sfWidgetFormFilterInput(),
      'total_price'           => new sfWidgetFormFilterInput(),
      'price'                 => new sfWidgetFormFilterInput(),
      'number'                => new sfWidgetFormFilterInput(),
      'marketing_fee'         => new sfWidgetFormFilterInput(),
      'duty_fee'              => new sfWidgetFormFilterInput(),
      'order_time'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'pay_time'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'receive_time'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'status'                => new sfWidgetFormFilterInput(),
      'pay_status'            => new sfWidgetFormFilterInput(),
      'source'                => new sfWidgetFormFilterInput(),
      'hupu_uid'              => new sfWidgetFormFilterInput(),
      'hupu_username'         => new sfWidgetFormFilterInput(),
      'is_comment'            => new sfWidgetFormFilterInput(),
      'ware_status'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_activity'           => new sfWidgetFormFilterInput(),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'order_number'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ibilling_number'       => new sfValidatorPass(array('required' => false)),
      'title'                 => new sfValidatorPass(array('required' => false)),
      'product_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'goods_id'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'domestic_express_type' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'domestic_order_number' => new sfValidatorPass(array('required' => false)),
      'domestic_express_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_gift'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'depot_type'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'express_fee'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'total_price'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'price'                 => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'number'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'marketing_fee'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'duty_fee'              => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'order_time'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'pay_time'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'receive_time'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'status'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pay_status'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'source'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_uid'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username'         => new sfValidatorPass(array('required' => false)),
      'is_comment'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ware_status'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_activity'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kaluli_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliOrder';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'order_number'          => 'Number',
      'ibilling_number'       => 'Text',
      'title'                 => 'Text',
      'product_id'            => 'Number',
      'goods_id'              => 'Number',
      'domestic_express_type' => 'Number',
      'domestic_order_number' => 'Text',
      'domestic_express_time' => 'Number',
      'is_gift'               => 'Number',
      'depot_type'            => 'Number',
      'express_fee'           => 'Number',
      'total_price'           => 'Number',
      'price'                 => 'Number',
      'number'                => 'Number',
      'marketing_fee'         => 'Number',
      'duty_fee'              => 'Number',
      'order_time'            => 'Date',
      'pay_time'              => 'Date',
      'receive_time'          => 'Date',
      'status'                => 'Number',
      'pay_status'            => 'Number',
      'source'                => 'Number',
      'hupu_uid'              => 'Number',
      'hupu_username'         => 'Text',
      'is_comment'            => 'Number',
      'ware_status'           => 'Number',
      'is_activity'           => 'Number',
      'created_at'            => 'Date',
      'updated_at'            => 'Date',
    );
  }
}
