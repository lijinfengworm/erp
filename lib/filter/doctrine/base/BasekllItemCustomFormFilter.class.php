<?php

/**
 * kllItemCustom filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasekllItemCustomFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'warranty_days'   => new sfWidgetFormFilterInput(),
      'goods_number'    => new sfWidgetFormFilterInput(),
      'reg_custom'      => new sfWidgetFormFilterInput(),
      'reg_inspection'  => new sfWidgetFormFilterInput(),
      'reg_app_date'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'custom_code'     => new sfWidgetFormFilterInput(),
      'inspection_code' => new sfWidgetFormFilterInput(),
      'hs_code'         => new sfWidgetFormFilterInput(),
      'tax_code'        => new sfWidgetFormFilterInput(),
      'stock_number'    => new sfWidgetFormFilterInput(),
      'unit_price'      => new sfWidgetFormFilterInput(),
      'unit'            => new sfWidgetFormFilterInput(),
      'original'        => new sfWidgetFormFilterInput(),
      'code'            => new sfWidgetFormFilterInput(),
      'goods_id'        => new sfWidgetFormFilterInput(),
      'product_id'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'warranty_days'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'goods_number'    => new sfValidatorPass(array('required' => false)),
      'reg_custom'      => new sfValidatorPass(array('required' => false)),
      'reg_inspection'  => new sfValidatorPass(array('required' => false)),
      'reg_app_date'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'custom_code'     => new sfValidatorPass(array('required' => false)),
      'inspection_code' => new sfValidatorPass(array('required' => false)),
      'hs_code'         => new sfValidatorPass(array('required' => false)),
      'tax_code'        => new sfValidatorPass(array('required' => false)),
      'stock_number'    => new sfValidatorPass(array('required' => false)),
      'unit_price'      => new sfValidatorPass(array('required' => false)),
      'unit'            => new sfValidatorPass(array('required' => false)),
      'original'        => new sfValidatorPass(array('required' => false)),
      'code'            => new sfValidatorPass(array('required' => false)),
      'goods_id'        => new sfValidatorPass(array('required' => false)),
      'product_id'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_item_custom_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'kllItemCustom';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'warranty_days'   => 'Number',
      'goods_number'    => 'Text',
      'reg_custom'      => 'Text',
      'reg_inspection'  => 'Text',
      'reg_app_date'    => 'Date',
      'custom_code'     => 'Text',
      'inspection_code' => 'Text',
      'hs_code'         => 'Text',
      'tax_code'        => 'Text',
      'stock_number'    => 'Text',
      'unit_price'      => 'Text',
      'unit'            => 'Text',
      'original'        => 'Text',
      'code'            => 'Text',
      'goods_id'        => 'Text',
      'product_id'      => 'Text',
    );
  }
}
