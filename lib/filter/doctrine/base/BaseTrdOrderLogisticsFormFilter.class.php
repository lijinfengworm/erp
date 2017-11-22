<?php

/**
 * TrdOrderLogistics filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdOrderLogisticsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'express_number'  => new sfWidgetFormFilterInput(),
      'type'            => new sfWidgetFormFilterInput(),
      'foreign_status'  => new sfWidgetFormFilterInput(),
      'domestic_status' => new sfWidgetFormFilterInput(),
      'excompany'       => new sfWidgetFormFilterInput(),
      'content'         => new sfWidgetFormFilterInput(),
      'transit_time'    => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'express_number'  => new sfValidatorPass(array('required' => false)),
      'type'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'foreign_status'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'domestic_status' => new sfValidatorPass(array('required' => false)),
      'excompany'       => new sfValidatorPass(array('required' => false)),
      'content'         => new sfValidatorPass(array('required' => false)),
      'transit_time'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_order_logistics_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdOrderLogistics';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'express_number'  => 'Text',
      'type'            => 'Number',
      'foreign_status'  => 'Number',
      'domestic_status' => 'Text',
      'excompany'       => 'Text',
      'content'         => 'Text',
      'transit_time'    => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
