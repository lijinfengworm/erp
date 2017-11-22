<?php

/**
 * KllWarehousesFee filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllWarehousesFeeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'warehouse_id' => new sfWidgetFormFilterInput(),
      'total_price'  => new sfWidgetFormFilterInput(),
      'note'         => new sfWidgetFormFilterInput(),
      'ct_time'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'warehouse_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'total_price'  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'note'         => new sfValidatorPass(array('required' => false)),
      'ct_time'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_warehouses_fee_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllWarehousesFee';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'warehouse_id' => 'Number',
      'total_price'  => 'Number',
      'note'         => 'Text',
      'ct_time'      => 'Date',
    );
  }
}
