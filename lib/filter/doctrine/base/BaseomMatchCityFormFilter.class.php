<?php

/**
 * omMatchCity filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseomMatchCityFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'om_match_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMatch'), 'add_empty' => true)),
      'location_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Location'), 'add_empty' => true)),
      'champion_page'           => new sfWidgetFormFilterInput(),
      'om_match_locations_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'omMatchLocation')),
    ));

    $this->setValidators(array(
      'om_match_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMatch'), 'column' => 'id')),
      'location_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Location'), 'column' => 'id')),
      'champion_page'           => new sfValidatorPass(array('required' => false)),
      'om_match_locations_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'omMatchLocation', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('om_match_city_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addOmMatchLocationsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('omMatchCityLocation.om_match_location_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'omMatchCity';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'om_match_id'             => 'ForeignKey',
      'location_id'             => 'ForeignKey',
      'champion_page'           => 'Text',
      'om_match_locations_list' => 'ManyKey',
    );
  }
}
