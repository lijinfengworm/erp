<?php

/**
 * KaluliWarehouses filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKaluliWarehousesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'           => new sfWidgetFormFilterInput(),
      'code'           => new sfWidgetFormFilterInput(),
      'address'        => new sfWidgetFormFilterInput(),
      'note'           => new sfWidgetFormFilterInput(),
      'create_date'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'contact_name'   => new sfWidgetFormFilterInput(),
      'contact_phone'  => new sfWidgetFormFilterInput(),
      'contact_mobile' => new sfWidgetFormFilterInput(),
      'type_name'      => new sfWidgetFormFilterInput(),
      'area_name'      => new sfWidgetFormFilterInput(),
      'freight_type'   => new sfWidgetFormFilterInput(),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'name'           => new sfValidatorPass(array('required' => false)),
      'code'           => new sfValidatorPass(array('required' => false)),
      'address'        => new sfValidatorPass(array('required' => false)),
      'note'           => new sfValidatorPass(array('required' => false)),
      'create_date'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'contact_name'   => new sfValidatorPass(array('required' => false)),
      'contact_phone'  => new sfValidatorPass(array('required' => false)),
      'contact_mobile' => new sfValidatorPass(array('required' => false)),
      'type_name'      => new sfValidatorPass(array('required' => false)),
      'area_name'      => new sfValidatorPass(array('required' => false)),
      'freight_type'   => new sfValidatorPass(array('required' => false)),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kaluli_warehouses_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliWarehouses';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'name'           => 'Text',
      'code'           => 'Text',
      'address'        => 'Text',
      'note'           => 'Text',
      'create_date'    => 'Date',
      'contact_name'   => 'Text',
      'contact_phone'  => 'Text',
      'contact_mobile' => 'Text',
      'type_name'      => 'Text',
      'area_name'      => 'Text',
      'freight_type'   => 'Text',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
