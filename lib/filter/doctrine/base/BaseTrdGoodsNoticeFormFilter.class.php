<?php

/**
 * TrdGoodsNotice filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsNoticeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'goods_id'    => new sfWidgetFormFilterInput(),
      'supplier_id' => new sfWidgetFormFilterInput(),
      'pic'         => new sfWidgetFormFilterInput(),
      'tag_type'    => new sfWidgetFormFilterInput(),
      'type'        => new sfWidgetFormFilterInput(),
      'status'      => new sfWidgetFormFilterInput(),
      'checked_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'goods_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'supplier_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pic'         => new sfValidatorPass(array('required' => false)),
      'tag_type'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'        => new sfValidatorPass(array('required' => false)),
      'status'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'checked_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_notice_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsNotice';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'goods_id'    => 'Number',
      'supplier_id' => 'Number',
      'pic'         => 'Text',
      'tag_type'    => 'Number',
      'type'        => 'Text',
      'status'      => 'Number',
      'checked_at'  => 'Date',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
