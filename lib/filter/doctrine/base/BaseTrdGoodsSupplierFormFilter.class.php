<?php

/**
 * TrdGoodsSupplier filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsSupplierFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'goods_id'            => new sfWidgetFormFilterInput(),
      'name'                => new sfWidgetFormFilterInput(),
      'store'               => new sfWidgetFormFilterInput(),
      'description'         => new sfWidgetFormFilterInput(),
      'price'               => new sfWidgetFormFilterInput(),
      'url'                 => new sfWidgetFormFilterInput(),
      'from_id'             => new sfWidgetFormFilterInput(),
      'from_type'           => new sfWidgetFormFilterInput(),
      'status'              => new sfWidgetFormFilterInput(),
      'unique_id'           => new sfWidgetFormFilterInput(),
      'update_time'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'update_info'         => new sfWidgetFormFilterInput(),
      'update_error_num'    => new sfWidgetFormFilterInput(),
      'update_error_info'   => new sfWidgetFormFilterInput(),
      'comment_update_time' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'created_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'goods_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'                => new sfValidatorPass(array('required' => false)),
      'store'               => new sfValidatorPass(array('required' => false)),
      'description'         => new sfValidatorPass(array('required' => false)),
      'price'               => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'url'                 => new sfValidatorPass(array('required' => false)),
      'from_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'from_type'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'unique_id'           => new sfValidatorPass(array('required' => false)),
      'update_time'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'update_info'         => new sfValidatorPass(array('required' => false)),
      'update_error_num'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'update_error_info'   => new sfValidatorPass(array('required' => false)),
      'comment_update_time' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_supplier_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsSupplier';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'goods_id'            => 'Number',
      'name'                => 'Text',
      'store'               => 'Text',
      'description'         => 'Text',
      'price'               => 'Number',
      'url'                 => 'Text',
      'from_id'             => 'Number',
      'from_type'           => 'Number',
      'status'              => 'Number',
      'unique_id'           => 'Text',
      'update_time'         => 'Date',
      'update_info'         => 'Text',
      'update_error_num'    => 'Number',
      'update_error_info'   => 'Text',
      'comment_update_time' => 'Date',
      'created_at'          => 'Date',
      'updated_at'          => 'Date',
    );
  }
}
