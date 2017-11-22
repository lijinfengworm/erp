<?php

/**
 * kllOrderCustoms filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasekllOrderCustomsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'logisticsId'   => new sfWidgetFormFilterInput(),
      'order_number'  => new sfWidgetFormFilterInput(),
      'order_price'   => new sfWidgetFormFilterInput(),
      'customs_dutys' => new sfWidgetFormFilterInput(),
      'customs_img'   => new sfWidgetFormFilterInput(),
      'customs_rate'  => new sfWidgetFormFilterInput(),
      'cost_unit'     => new sfWidgetFormFilterInput(),
      'status'        => new sfWidgetFormFilterInput(),
      'check_status'  => new sfWidgetFormFilterInput(),
      'create_time'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'check_time'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'logisticsId'   => new sfValidatorPass(array('required' => false)),
      'order_number'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'order_price'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'customs_dutys' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'customs_img'   => new sfValidatorPass(array('required' => false)),
      'customs_rate'  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'cost_unit'     => new sfValidatorPass(array('required' => false)),
      'status'        => new sfValidatorPass(array('required' => false)),
      'check_status'  => new sfValidatorPass(array('required' => false)),
      'create_time'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'check_time'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_order_customs_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'kllOrderCustoms';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'logisticsId'   => 'Text',
      'order_number'  => 'Number',
      'order_price'   => 'Number',
      'customs_dutys' => 'Number',
      'customs_img'   => 'Text',
      'customs_rate'  => 'Number',
      'cost_unit'     => 'Text',
      'status'        => 'Text',
      'check_status'  => 'Text',
      'create_time'   => 'Date',
      'check_time'    => 'Date',
    );
  }
}
