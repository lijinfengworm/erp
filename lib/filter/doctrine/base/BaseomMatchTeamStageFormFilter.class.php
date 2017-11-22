<?php

/**
 * omMatchTeamStage filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseomMatchTeamStageFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'om_match_team_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMatchTeam'), 'add_empty' => true)),
      'om_match_round_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMatchRound'), 'add_empty' => true)),
      'om_match_group_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMatchGroup'), 'add_empty' => true)),
      'rank'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'points'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_passed'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'om_match_team_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMatchTeam'), 'column' => 'id')),
      'om_match_round_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMatchRound'), 'column' => 'id')),
      'om_match_group_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMatchGroup'), 'column' => 'id')),
      'rank'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'points'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_passed'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('om_match_team_stage_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'omMatchTeamStage';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'om_match_team_id'  => 'ForeignKey',
      'om_match_round_id' => 'ForeignKey',
      'om_match_group_id' => 'ForeignKey',
      'rank'              => 'Number',
      'points'            => 'Number',
      'is_passed'         => 'Boolean',
    );
  }
}
