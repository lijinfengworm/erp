<?php

/**
 * TrdGroupon filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdGrouponFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'brand_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Brand'), 'add_empty' => true)),
      'shop_id'         => new sfWidgetFormFilterInput(),
      'item_id'         => new sfWidgetFormFilterInput(),
      'hupu_uid'        => new sfWidgetFormFilterInput(),
      'hupu_username'   => new sfWidgetFormFilterInput(),
      'ibilling_number' => new sfWidgetFormFilterInput(),
      'pay_status'      => new sfWidgetFormFilterInput(),
      'intro'           => new sfWidgetFormFilterInput(),
      'memo'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'title'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'url'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'attend_count'    => new sfWidgetFormFilterInput(),
      'price'           => new sfWidgetFormFilterInput(),
      'original_price'  => new sfWidgetFormFilterInput(),
      'discount'        => new sfWidgetFormFilterInput(),
      'praise'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'category_id'     => new sfWidgetFormFilterInput(),
      'start_time'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'end_time'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'attr'            => new sfWidgetFormFilterInput(),
      'goods_num'       => new sfWidgetFormFilterInput(),
      'color_num'       => new sfWidgetFormFilterInput(),
      'pic_attr'        => new sfWidgetFormFilterInput(),
      'rank'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'alliance'        => new sfWidgetFormFilterInput(),
      'is_sold'         => new sfWidgetFormFilterInput(),
      'collect_count'   => new sfWidgetFormFilterInput(),
      'status'          => new sfWidgetFormFilterInput(),
      'usp_logo'        => new sfWidgetFormFilterInput(),
      'type'            => new sfWidgetFormFilterInput(),
      'is_ad'           => new sfWidgetFormFilterInput(),
      'pay_type'        => new sfWidgetFormFilterInput(),
      'pay_date'        => new sfWidgetFormFilterInput(),
      'commodity'       => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'deleted_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'brand_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Brand'), 'column' => 'id')),
      'shop_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'item_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_uid'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username'   => new sfValidatorPass(array('required' => false)),
      'ibilling_number' => new sfValidatorPass(array('required' => false)),
      'pay_status'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'intro'           => new sfValidatorPass(array('required' => false)),
      'memo'            => new sfValidatorPass(array('required' => false)),
      'title'           => new sfValidatorPass(array('required' => false)),
      'url'             => new sfValidatorPass(array('required' => false)),
      'attend_count'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'price'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'original_price'  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'discount'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'praise'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'category_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'start_time'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'end_time'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'attr'            => new sfValidatorPass(array('required' => false)),
      'goods_num'       => new sfValidatorPass(array('required' => false)),
      'color_num'       => new sfValidatorPass(array('required' => false)),
      'pic_attr'        => new sfValidatorPass(array('required' => false)),
      'rank'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'alliance'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_sold'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'collect_count'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'usp_logo'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_ad'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pay_type'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pay_date'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'commodity'       => new sfValidatorPass(array('required' => false)),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'deleted_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_groupon_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGroupon';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'brand_id'        => 'ForeignKey',
      'shop_id'         => 'Number',
      'item_id'         => 'Number',
      'hupu_uid'        => 'Number',
      'hupu_username'   => 'Text',
      'ibilling_number' => 'Text',
      'pay_status'      => 'Number',
      'intro'           => 'Text',
      'memo'            => 'Text',
      'title'           => 'Text',
      'url'             => 'Text',
      'attend_count'    => 'Number',
      'price'           => 'Number',
      'original_price'  => 'Number',
      'discount'        => 'Number',
      'praise'          => 'Number',
      'category_id'     => 'Number',
      'start_time'      => 'Date',
      'end_time'        => 'Date',
      'attr'            => 'Text',
      'goods_num'       => 'Text',
      'color_num'       => 'Text',
      'pic_attr'        => 'Text',
      'rank'            => 'Number',
      'alliance'        => 'Number',
      'is_sold'         => 'Number',
      'collect_count'   => 'Number',
      'status'          => 'Number',
      'usp_logo'        => 'Number',
      'type'            => 'Number',
      'is_ad'           => 'Number',
      'pay_type'        => 'Number',
      'pay_date'        => 'Number',
      'commodity'       => 'Text',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
      'deleted_at'      => 'Date',
    );
  }
}
