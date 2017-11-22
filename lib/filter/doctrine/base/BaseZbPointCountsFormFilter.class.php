<?php

/**
 * ZbPointCounts filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseZbPointCountsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'series_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ZbSeries'), 'add_empty' => true)),
      'product_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ZbProducts'), 'add_empty' => true)),
      'point1'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'point2'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'point3'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'point4'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'point5'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'deleted_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'series_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ZbSeries'), 'column' => 'id')),
      'product_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ZbProducts'), 'column' => 'id')),
      'point1'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'point2'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'point3'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'point4'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'point5'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'deleted_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('zb_point_counts_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZbPointCounts';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'series_id'  => 'ForeignKey',
      'product_id' => 'ForeignKey',
      'point1'     => 'Number',
      'point2'     => 'Number',
      'point3'     => 'Number',
      'point4'     => 'Number',
      'point5'     => 'Number',
      'created_at' => 'Date',
      'updated_at' => 'Date',
      'deleted_at' => 'Date',
    );
  }
}
