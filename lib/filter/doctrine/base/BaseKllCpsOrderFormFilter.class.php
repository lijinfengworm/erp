<?php

/**
 * KllCpsOrder filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllCpsOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number'     => new sfWidgetFormFilterInput(),
      'sub_order_number' => new sfWidgetFormFilterInput(),
      'order_time'       => new sfWidgetFormFilterInput(),
      'click_time'       => new sfWidgetFormFilterInput(),
      'orders_price'     => new sfWidgetFormFilterInput(),
      'discount_amount'  => new sfWidgetFormFilterInput(),
      'promotion_code'   => new sfWidgetFormFilterInput(),
      'is_new_custom'    => new sfWidgetFormFilterInput(),
      'channel'          => new sfWidgetFormFilterInput(),
      'status'           => new sfWidgetFormFilterInput(),
      'goods_id'         => new sfWidgetFormFilterInput(),
      'title'            => new sfWidgetFormFilterInput(),
      'goods_price'      => new sfWidgetFormFilterInput(),
      'goods_ta'         => new sfWidgetFormFilterInput(),
      'goods_cate'       => new sfWidgetFormFilterInput(),
      'goods_cate_name'  => new sfWidgetFormFilterInput(),
      'total_price'      => new sfWidgetFormFilterInput(),
      'rate'             => new sfWidgetFormFilterInput(),
      'commission'       => new sfWidgetFormFilterInput(),
      'commission_type'  => new sfWidgetFormFilterInput(),
      'test'             => new sfWidgetFormFilterInput(),
      'union_id'         => new sfWidgetFormFilterInput(),
      'mid'              => new sfWidgetFormFilterInput(),
      'euid'             => new sfWidgetFormFilterInput(),
      'referer'          => new sfWidgetFormFilterInput(),
      'hupu_uid'         => new sfWidgetFormFilterInput(),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'order_number'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sub_order_number' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'order_time'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'click_time'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'orders_price'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'discount_amount'  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'promotion_code'   => new sfValidatorPass(array('required' => false)),
      'is_new_custom'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'channel'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'goods_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'            => new sfValidatorPass(array('required' => false)),
      'goods_price'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'goods_ta'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'goods_cate'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'goods_cate_name'  => new sfValidatorPass(array('required' => false)),
      'total_price'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'rate'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'commission'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'commission_type'  => new sfValidatorPass(array('required' => false)),
      'test'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'union_id'         => new sfValidatorPass(array('required' => false)),
      'mid'              => new sfValidatorPass(array('required' => false)),
      'euid'             => new sfValidatorPass(array('required' => false)),
      'referer'          => new sfValidatorPass(array('required' => false)),
      'hupu_uid'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_cps_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllCpsOrder';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'order_number'     => 'Number',
      'sub_order_number' => 'Number',
      'order_time'       => 'Number',
      'click_time'       => 'Number',
      'orders_price'     => 'Number',
      'discount_amount'  => 'Number',
      'promotion_code'   => 'Text',
      'is_new_custom'    => 'Number',
      'channel'          => 'Number',
      'status'           => 'Number',
      'goods_id'         => 'Number',
      'title'            => 'Text',
      'goods_price'      => 'Number',
      'goods_ta'         => 'Number',
      'goods_cate'       => 'Number',
      'goods_cate_name'  => 'Text',
      'total_price'      => 'Number',
      'rate'             => 'Number',
      'commission'       => 'Number',
      'commission_type'  => 'Text',
      'test'             => 'Number',
      'union_id'         => 'Text',
      'mid'              => 'Text',
      'euid'             => 'Text',
      'referer'          => 'Text',
      'hupu_uid'         => 'Number',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
    );
  }
}
