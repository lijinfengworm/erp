<?php

/**
 * omMatchLocation filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseomMatchLocationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                 => new sfWidgetFormFilterInput(),
      'address'              => new sfWidgetFormFilterInput(),
      'lat'                  => new sfWidgetFormFilterInput(),
      'lon'                  => new sfWidgetFormFilterInput(),
      'Location_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Location'), 'add_empty' => true)),
      'om_appointment_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'omAppointment')),
      'om_match_cities_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'omMatchCity')),
    ));

    $this->setValidators(array(
      'name'                 => new sfValidatorPass(array('required' => false)),
      'address'              => new sfValidatorPass(array('required' => false)),
      'lat'                  => new sfValidatorPass(array('required' => false)),
      'lon'                  => new sfValidatorPass(array('required' => false)),
      'Location_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Location'), 'column' => 'id')),
      'om_appointment_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'omAppointment', 'required' => false)),
      'om_match_cities_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'omMatchCity', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('om_match_location_filters[%s]');

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
      ->leftJoin($query->getRootAlias().'.omAppointmentMatchLocation omAppointmentMatchLocation')
      ->andWhereIn('omAppointmentMatchLocation.om_appointment_id', $values)
    ;
  }

  public function addOmMatchCitiesListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.omMatchCityLocation omMatchCityLocation')
      ->andWhereIn('omMatchCityLocation.om_match_city_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'omMatchLocation';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'name'                 => 'Text',
      'address'              => 'Text',
      'lat'                  => 'Text',
      'lon'                  => 'Text',
      'Location_id'          => 'ForeignKey',
      'om_appointment_list'  => 'ManyKey',
      'om_match_cities_list' => 'ManyKey',
    );
  }
}
