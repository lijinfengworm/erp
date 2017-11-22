<?php

/**
 * omMatchCityLocation filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseomMatchCityLocationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'om_match_city_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMatchCity'), 'add_empty' => true)),
      'om_match_location_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMatchLocation'), 'add_empty' => true)),
      'match_limit'          => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'om_match_city_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMatchCity'), 'column' => 'id')),
      'om_match_location_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMatchLocation'), 'column' => 'id')),
      'match_limit'          => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('om_match_city_location_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'omMatchCityLocation';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'om_match_city_id'     => 'ForeignKey',
      'om_match_location_id' => 'ForeignKey',
      'match_limit'          => 'Text',
    );
  }
}
