<?php

/**
 * trdGrouponTreasure filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasetrdGrouponTreasureFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hupu_uid'       => new sfWidgetFormFilterInput(),
      'hupu_username'  => new sfWidgetFormFilterInput(),
      'title'          => new sfWidgetFormFilterInput(),
      'intro'          => new sfWidgetFormFilterInput(),
      'memo'           => new sfWidgetFormFilterInput(),
      'price'          => new sfWidgetFormFilterInput(),
      'original_price' => new sfWidgetFormFilterInput(),
      'discount'       => new sfWidgetFormFilterInput(),
      'category_id'    => new sfWidgetFormFilterInput(),
      'brand_id'       => new sfWidgetFormFilterInput(),
      'url'            => new sfWidgetFormFilterInput(),
      'pic_attr'       => new sfWidgetFormFilterInput(),
      'goods_num'      => new sfWidgetFormFilterInput(),
      'apply_for_time' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'start_time'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'end_time'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'status'         => new sfWidgetFormFilterInput(),
      'is_sold'        => new sfWidgetFormFilterInput(),
      'superiority'    => new sfWidgetFormFilterInput(),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'hupu_uid'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username'  => new sfValidatorPass(array('required' => false)),
      'title'          => new sfValidatorPass(array('required' => false)),
      'intro'          => new sfValidatorPass(array('required' => false)),
      'memo'           => new sfValidatorPass(array('required' => false)),
      'price'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'original_price' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'discount'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'category_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'brand_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'url'            => new sfValidatorPass(array('required' => false)),
      'pic_attr'       => new sfValidatorPass(array('required' => false)),
      'goods_num'      => new sfValidatorPass(array('required' => false)),
      'apply_for_time' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'start_time'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'end_time'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'status'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_sold'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'superiority'    => new sfValidatorPass(array('required' => false)),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_groupon_treasure_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'trdGrouponTreasure';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'hupu_uid'       => 'Number',
      'hupu_username'  => 'Text',
      'title'          => 'Text',
      'intro'          => 'Text',
      'memo'           => 'Text',
      'price'          => 'Number',
      'original_price' => 'Number',
      'discount'       => 'Number',
      'category_id'    => 'Number',
      'brand_id'       => 'Number',
      'url'            => 'Text',
      'pic_attr'       => 'Text',
      'goods_num'      => 'Text',
      'apply_for_time' => 'Date',
      'start_time'     => 'Date',
      'end_time'       => 'Date',
      'status'         => 'Number',
      'is_sold'        => 'Number',
      'superiority'    => 'Text',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
