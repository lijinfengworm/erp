<?php

/**
 * omApplyAppointment filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseomApplyAppointmentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'status'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'apply_date'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'reason_status'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'status'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'apply_date'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reason_status'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('om_apply_appointment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'omApplyAppointment';
  }

  public function getFields()
  {
    return array(
      'om_team_id'        => 'Number',
      'om_appointment_id' => 'Number',
      'status'            => 'Number',
      'type'              => 'Number',
      'apply_date'        => 'Number',
      'reason_status'     => 'Number',
    );
  }
}
