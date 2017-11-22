<?php

/**
 * kllOrderChargeDetails filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasekllOrderChargeDetailsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'logisticsId'       => new sfWidgetFormFilterInput(),
      'order_number'      => new sfWidgetFormFilterInput(),
      'logistics_cost'    => new sfWidgetFormFilterInput(),
      'package_cost'      => new sfWidgetFormFilterInput(),
      'operating_cost'    => new sfWidgetFormFilterInput(),
      'insurance_cost'    => new sfWidgetFormFilterInput(),
      'verification_cost' => new sfWidgetFormFilterInput(),
      'other_cost'        => new sfWidgetFormFilterInput(),
      'cost_unit'         => new sfWidgetFormFilterInput(),
      'remark'            => new sfWidgetFormFilterInput(),
      'create_time'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'logisticsId'       => new sfValidatorPass(array('required' => false)),
      'order_number'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'logistics_cost'    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'package_cost'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'operating_cost'    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'insurance_cost'    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'verification_cost' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'other_cost'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'cost_unit'         => new sfValidatorPass(array('required' => false)),
      'remark'            => new sfValidatorPass(array('required' => false)),
      'create_time'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_order_charge_details_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'kllOrderChargeDetails';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'logisticsId'       => 'Text',
      'order_number'      => 'Number',
      'logistics_cost'    => 'Number',
      'package_cost'      => 'Number',
      'operating_cost'    => 'Number',
      'insurance_cost'    => 'Number',
      'verification_cost' => 'Number',
      'other_cost'        => 'Number',
      'cost_unit'         => 'Text',
      'remark'            => 'Text',
      'create_time'       => 'Date',
    );
  }
}
