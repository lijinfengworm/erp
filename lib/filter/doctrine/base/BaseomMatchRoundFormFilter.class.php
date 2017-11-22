<?php

/**
 * omMatchRound filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseomMatchRoundFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'om_match_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMatch'), 'add_empty' => true)),
      'om_match_city_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMatchCity'), 'add_empty' => true)),
      'om_match_city_location_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMatchCityLocation'), 'add_empty' => true)),
      'name'                      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'                      => new sfWidgetFormFilterInput(),
      'age_group'                 => new sfWidgetFormChoice(array('choices' => array('' => '', 'U19' => 'U19', 'U23' => 'U23', 'ALL' => 'ALL'))),
      'teams_number'              => new sfWidgetFormFilterInput(),
      'status'                    => new sfWidgetFormFilterInput(),
      'is_merged'                 => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'om_match_id'               => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMatch'), 'column' => 'id')),
      'om_match_city_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMatchCity'), 'column' => 'id')),
      'om_match_city_location_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMatchCityLocation'), 'column' => 'id')),
      'name'                      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'                      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'age_group'                 => new sfValidatorChoice(array('required' => false, 'choices' => array('U19' => 'U19', 'U23' => 'U23', 'ALL' => 'ALL'))),
      'teams_number'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'                    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_merged'                 => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('om_match_round_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'omMatchRound';
  }

  public function getFields()
  {
    return array(
      'id'                        => 'Number',
      'om_match_id'               => 'ForeignKey',
      'om_match_city_id'          => 'ForeignKey',
      'om_match_city_location_id' => 'ForeignKey',
      'name'                      => 'Number',
      'type'                      => 'Number',
      'age_group'                 => 'Enum',
      'teams_number'              => 'Number',
      'status'                    => 'Number',
      'is_merged'                 => 'Boolean',
    );
  }
}
