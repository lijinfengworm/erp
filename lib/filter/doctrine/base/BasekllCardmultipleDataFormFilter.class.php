<?php

/**
 * kllCardmultipleData filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasekllCardmultipleDataFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'm_card'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'm_id'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'card_data'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'uid'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'u_time'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'status'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'm_card'     => new sfValidatorPass(array('required' => false)),
      'm_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'card_data'  => new sfValidatorPass(array('required' => false)),
      'uid'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'u_time'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_cardmultiple_data_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'kllCardmultipleData';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'm_card'     => 'Text',
      'm_id'       => 'Number',
      'card_data'  => 'Text',
      'uid'        => 'Number',
      'u_time'     => 'Number',
      'status'     => 'Number',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
