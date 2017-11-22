<?php

/**
 * TrdProductCollocation filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdProductCollocationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'product_id'       => new sfWidgetFormFilterInput(),
      'collocation_attr' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'shaiwu_attr'      => new sfWidgetFormFilterInput(),
      'status'           => new sfWidgetFormFilterInput(),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'product_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'collocation_attr' => new sfValidatorPass(array('required' => false)),
      'shaiwu_attr'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_product_collocation_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdProductCollocation';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'product_id'       => 'Number',
      'collocation_attr' => 'Text',
      'shaiwu_attr'      => 'Number',
      'status'           => 'Number',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
    );
  }
}