<?php

/**
 * KllWarehousesExpressArea filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllWarehousesExpressAreaFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ware_express_id'  => new sfWidgetFormFilterInput(),
      'provinces'        => new sfWidgetFormFilterInput(),
      'is_default'       => new sfWidgetFormFilterInput(),
      'first_price'      => new sfWidgetFormFilterInput(),
      'additional_price' => new sfWidgetFormFilterInput(),
      'ct_time'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'ware_express_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'provinces'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_default'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'first_price'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'additional_price' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'ct_time'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_warehouses_express_area_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllWarehousesExpressArea';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'ware_express_id'  => 'Number',
      'provinces'        => 'Number',
      'is_default'       => 'Number',
      'first_price'      => 'Number',
      'additional_price' => 'Number',
      'ct_time'          => 'Date',
    );
  }
}
