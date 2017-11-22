<?php

/**
 * HoopTeamVote filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseHoopTeamVoteFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'match_id'  => new sfWidgetFormFilterInput(),
      'home_vote' => new sfWidgetFormFilterInput(),
      'away_vote' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'match_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'home_vote' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'away_vote' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('hoop_team_vote_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'HoopTeamVote';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'match_id'  => 'Number',
      'home_vote' => 'Number',
      'away_vote' => 'Number',
    );
  }
}
