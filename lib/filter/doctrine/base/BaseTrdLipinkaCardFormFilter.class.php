<?php

/**
 * TrdLipinkaCard filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdLipinkaCardFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'record_id'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lipinka_id'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'account'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_id'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_large'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'large_id'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'postpone_type' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'postpone_day'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'overdue_time'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'stime'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'etime'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'status'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'create_type'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sync_status'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'amount'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'record_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lipinka_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'account'       => new sfValidatorPass(array('required' => false)),
      'user_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_large'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'large_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'postpone_type' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'postpone_day'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'overdue_time'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stime'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'etime'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'create_type'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sync_status'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'amount'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_lipinka_card_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdLipinkaCard';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'record_id'     => 'Number',
      'lipinka_id'    => 'Number',
      'account'       => 'Text',
      'user_id'       => 'Number',
      'is_large'      => 'Number',
      'large_id'      => 'Number',
      'postpone_type' => 'Number',
      'postpone_day'  => 'Number',
      'overdue_time'  => 'Number',
      'stime'         => 'Number',
      'etime'         => 'Number',
      'status'        => 'Number',
      'create_type'   => 'Number',
      'sync_status'   => 'Number',
      'amount'        => 'Number',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
