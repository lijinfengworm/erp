<?php

/**
 * KllWarehousesExpress filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllWarehousesExpressFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'warehouse_id' => new sfWidgetFormFilterInput(),
      'express_id'   => new sfWidgetFormFilterInput(),
      'status'       => new sfWidgetFormFilterInput(),
      'radio'        => new sfWidgetFormFilterInput(),
      'is_default'   => new sfWidgetFormFilterInput(),
      'ct_time'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'warehouse_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'express_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'radio'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'is_default'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ct_time'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_warehouses_express_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllWarehousesExpress';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'warehouse_id' => 'Number',
      'express_id'   => 'Number',
      'status'       => 'Number',
      'radio'        => 'Number',
      'is_default'   => 'Number',
      'ct_time'      => 'Date',
    );
  }
}
