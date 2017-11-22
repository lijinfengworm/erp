<?php

/**
 * KaluliOrderLogistics filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKaluliOrderLogisticsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'to_city'         => new sfWidgetFormFilterInput(),
      'order_number'    => new sfWidgetFormFilterInput(),
      'express_number'  => new sfWidgetFormFilterInput(),
      'domestic_status' => new sfWidgetFormFilterInput(),
      'excompany'       => new sfWidgetFormFilterInput(),
      'abroad'          => new sfWidgetFormFilterInput(),
      'content'         => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'to_city'         => new sfValidatorPass(array('required' => false)),
      'order_number'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'express_number'  => new sfValidatorPass(array('required' => false)),
      'domestic_status' => new sfValidatorPass(array('required' => false)),
      'excompany'       => new sfValidatorPass(array('required' => false)),
      'abroad'          => new sfValidatorPass(array('required' => false)),
      'content'         => new sfValidatorPass(array('required' => false)),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kaluli_order_logistics_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliOrderLogistics';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'to_city'         => 'Text',
      'order_number'    => 'Number',
      'express_number'  => 'Text',
      'domestic_status' => 'Text',
      'excompany'       => 'Text',
      'abroad'          => 'Text',
      'content'         => 'Text',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
