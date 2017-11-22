<?php

/**
 * TrdAppActivityReceived filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdAppActivityReceivedFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'activity_id'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'detail_id'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'account'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'mobile'        => new sfWidgetFormFilterInput(),
      'start_time'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'end_time'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'hupu_uid'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'hupu_username' => new sfWidgetFormFilterInput(),
      'received_time' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'activity_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'detail_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'account'       => new sfValidatorPass(array('required' => false)),
      'mobile'        => new sfValidatorPass(array('required' => false)),
      'start_time'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'end_time'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'hupu_uid'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username' => new sfValidatorPass(array('required' => false)),
      'received_time' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_app_activity_received_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAppActivityReceived';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'activity_id'   => 'Number',
      'detail_id'     => 'Number',
      'account'       => 'Text',
      'mobile'        => 'Text',
      'start_time'    => 'Date',
      'end_time'      => 'Date',
      'hupu_uid'      => 'Number',
      'hupu_username' => 'Text',
      'received_time' => 'Date',
    );
  }
}
