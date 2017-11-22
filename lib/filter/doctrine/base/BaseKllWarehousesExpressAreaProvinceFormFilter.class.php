<?php

/**
 * KllWarehousesExpressAreaProvince filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllWarehousesExpressAreaProvinceFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ware_express'         => new sfWidgetFormFilterInput(),
      'ware_express_area_id' => new sfWidgetFormFilterInput(),
      'province'             => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'ware_express'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ware_express_area_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'province'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_warehouses_express_area_province_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllWarehousesExpressAreaProvince';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'ware_express'         => 'Number',
      'ware_express_area_id' => 'Number',
      'province'             => 'Number',
    );
  }
}
