<?php

/**
 * kllCardwareData filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasekllCardwareDataFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'w_id'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'amount'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'attr'       => new sfWidgetFormFilterInput(),
      'alert_num'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_alert'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'cache_key'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'w_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'amount'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'attr'       => new sfValidatorPass(array('required' => false)),
      'alert_num'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_alert'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'cache_key'  => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_cardware_data_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'kllCardwareData';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'w_id'       => 'Number',
      'amount'     => 'Number',
      'attr'       => 'Text',
      'alert_num'  => 'Number',
      'is_alert'   => 'Number',
      'cache_key'  => 'Text',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
