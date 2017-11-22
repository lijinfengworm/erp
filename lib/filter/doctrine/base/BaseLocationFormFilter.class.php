<?php

/**
 * Location filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseLocationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'root_id'             => new sfWidgetFormFilterInput(),
      'name'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_match_city'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'lft'                 => new sfWidgetFormFilterInput(),
      'rgt'                 => new sfWidgetFormFilterInput(),
      'level'               => new sfWidgetFormFilterInput(),
      'om_appointment_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'omAppointment')),
      'om_matchs_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'omMatch')),
      'om_notices_list'     => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'omNotice')),
    ));

    $this->setValidators(array(
      'root_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'                => new sfValidatorPass(array('required' => false)),
      'is_match_city'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'lft'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'om_appointment_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'omAppointment', 'required' => false)),
      'om_matchs_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'omMatch', 'required' => false)),
      'om_notices_list'     => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'omNotice', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('location_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addOmAppointmentListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('omAppointmentLocation.om_appointment_id', $values)
    ;
  }

  public function addOmMatchsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.omMatchCity omMatchCity')
      ->andWhereIn('omMatchCity.om_match_id', $values)
    ;
  }

  public function addOmNoticesListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.omNoticeLocation omNoticeLocation')
      ->andWhereIn('omNoticeLocation.om_notice_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Location';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'root_id'             => 'Number',
      'name'                => 'Text',
      'is_match_city'       => 'Boolean',
      'lft'                 => 'Number',
      'rgt'                 => 'Number',
      'level'               => 'Number',
      'om_appointment_list' => 'ManyKey',
      'om_matchs_list'      => 'ManyKey',
      'om_notices_list'     => 'ManyKey',
    );
  }
}
