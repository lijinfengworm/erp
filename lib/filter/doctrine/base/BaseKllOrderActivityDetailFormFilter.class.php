<?php

/**
 * KllOrderActivityDetail filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllOrderActivityDetailFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number' => new sfWidgetFormFilterInput(),
      'activity_id'  => new sfWidgetFormFilterInput(),
      'attr'         => new sfWidgetFormFilterInput(),
      'type'         => new sfWidgetFormFilterInput(),
      'refund_type'  => new sfWidgetFormFilterInput(),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'order_number' => new sfValidatorPass(array('required' => false)),
      'activity_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'attr'         => new sfValidatorPass(array('required' => false)),
      'type'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'refund_type'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_order_activity_detail_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllOrderActivityDetail';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'order_number' => 'Text',
      'activity_id'  => 'Number',
      'attr'         => 'Text',
      'type'         => 'Number',
      'refund_type'  => 'Number',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
