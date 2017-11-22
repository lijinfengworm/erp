<?php

/**
 * omMatchTeam filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseomMatchTeamFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'om_team_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omTeam'), 'add_empty' => true)),
      'om_match_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMatch'), 'add_empty' => true)),
      'status'               => new sfWidgetFormFilterInput(),
      'location_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('location'), 'add_empty' => true)),
      'age_group'            => new sfWidgetFormChoice(array('choices' => array('' => '', 'U19' => 'U19', 'U23' => 'U23', 'ALL' => 'ALL'))),
      'points'               => new sfWidgetFormFilterInput(),
      'rank'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'om_match_location_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMatchLocation'), 'add_empty' => true)),
      'is_passed'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'om_team_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omTeam'), 'column' => 'id')),
      'om_match_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMatch'), 'column' => 'id')),
      'status'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'location_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('location'), 'column' => 'id')),
      'age_group'            => new sfValidatorChoice(array('required' => false, 'choices' => array('U19' => 'U19', 'U23' => 'U23', 'ALL' => 'ALL'))),
      'points'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rank'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'om_match_location_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMatchLocation'), 'column' => 'id')),
      'is_passed'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('om_match_team_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'omMatchTeam';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'om_team_id'           => 'ForeignKey',
      'om_match_id'          => 'ForeignKey',
      'status'               => 'Number',
      'location_id'          => 'ForeignKey',
      'age_group'            => 'Enum',
      'points'               => 'Number',
      'rank'                 => 'Number',
      'om_match_location_id' => 'ForeignKey',
      'is_passed'            => 'Boolean',
    );
  }
}
