<?php

/**
 * omAppointment filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseomAppointmentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'om_team_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omTeam'), 'add_empty' => true)),
      'om_activity_time_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omActivityTime'), 'add_empty' => true)),
      'status'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'                   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'agree_time'             => new sfWidgetFormFilterInput(),
      'hasBall'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'hasReferee'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'Uniform_color'          => new sfWidgetFormFilterInput(),
      'hasAssistant'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'IsFullGame'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'resultStatus'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_repeat'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'om_match_location_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'omMatchLocation')),
      'location_list'          => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Location')),
    ));

    $this->setValidators(array(
      'om_team_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omTeam'), 'column' => 'id')),
      'om_activity_time_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omActivityTime'), 'column' => 'id')),
      'status'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'agree_time'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hasBall'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'hasReferee'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'Uniform_color'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hasAssistant'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'IsFullGame'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'resultStatus'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_repeat'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'om_match_location_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'omMatchLocation', 'required' => false)),
      'location_list'          => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Location', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('om_appointment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addOmMatchLocationListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.omAppointmentMatchLocation omAppointmentMatchLocation')
      ->andWhereIn('omAppointmentMatchLocation.om_match_location_id', $values)
    ;
  }

  public function addLocationListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.omAppointmentLocation omAppointmentLocation')
      ->andWhereIn('omAppointmentLocation.location_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'omAppointment';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'om_team_id'             => 'ForeignKey',
      'om_activity_time_id'    => 'ForeignKey',
      'status'                 => 'Number',
      'type'                   => 'Number',
      'created_at'             => 'Number',
      'agree_time'             => 'Number',
      'hasBall'                => 'Boolean',
      'hasReferee'             => 'Boolean',
      'Uniform_color'          => 'Number',
      'hasAssistant'           => 'Boolean',
      'IsFullGame'             => 'Boolean',
      'resultStatus'           => 'Number',
      'is_repeat'              => 'Boolean',
      'om_match_location_list' => 'ManyKey',
      'location_list'          => 'ManyKey',
    );
  }
}
