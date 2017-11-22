<?php

/**
 * KaluliLipinka filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKaluliLipinkaFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'apply_user_id'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'activity_type'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'for_what'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'verify_user_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'stime'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'etime'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'amount'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'status'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_delete'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'title'          => new sfValidatorPass(array('required' => false)),
      'apply_user_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'activity_type'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'for_what'       => new sfValidatorPass(array('required' => false)),
      'verify_user_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stime'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'etime'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'amount'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_delete'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kaluli_lipinka_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliLipinka';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'title'          => 'Text',
      'apply_user_id'  => 'Number',
      'type'           => 'Number',
      'activity_type'  => 'Number',
      'for_what'       => 'Text',
      'verify_user_id' => 'Number',
      'stime'          => 'Number',
      'etime'          => 'Number',
      'amount'         => 'Number',
      'status'         => 'Number',
      'is_delete'      => 'Number',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
