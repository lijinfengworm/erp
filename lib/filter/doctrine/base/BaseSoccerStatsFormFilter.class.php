<?php

/**
 * SoccerStats filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSoccerStatsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'schid'         => new sfWidgetFormFilterInput(),
      'team_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('soocerTeam'), 'add_empty' => true)),
      'team'          => new sfWidgetFormFilterInput(),
      'goal'          => new sfWidgetFormFilterInput(),
      'shots'         => new sfWidgetFormFilterInput(),
      'shots_ongoal'  => new sfWidgetFormFilterInput(),
      'corner_kicks'  => new sfWidgetFormFilterInput(),
      'offsides'      => new sfWidgetFormFilterInput(),
      'yellow_cards'  => new sfWidgetFormFilterInput(),
      'red_cards'     => new sfWidgetFormFilterInput(),
      'fouls'         => new sfWidgetFormFilterInput(),
      'possession'    => new sfWidgetFormFilterInput(),
      'penaltyGoals'  => new sfWidgetFormFilterInput(),
      'blocks'        => new sfWidgetFormFilterInput(),
      'interceptions' => new sfWidgetFormFilterInput(),
      'ishome'        => new sfWidgetFormFilterInput(),
      'crosses'       => new sfWidgetFormFilterInput(),
      'passes'        => new sfWidgetFormFilterInput(),
      'league'        => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'schid'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'team_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('soocerTeam'), 'column' => 'id')),
      'team'          => new sfValidatorPass(array('required' => false)),
      'goal'          => new sfValidatorPass(array('required' => false)),
      'shots'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'shots_ongoal'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'corner_kicks'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'offsides'      => new sfValidatorPass(array('required' => false)),
      'yellow_cards'  => new sfValidatorPass(array('required' => false)),
      'red_cards'     => new sfValidatorPass(array('required' => false)),
      'fouls'         => new sfValidatorPass(array('required' => false)),
      'possession'    => new sfValidatorPass(array('required' => false)),
      'penaltyGoals'  => new sfValidatorPass(array('required' => false)),
      'blocks'        => new sfValidatorPass(array('required' => false)),
      'interceptions' => new sfValidatorPass(array('required' => false)),
      'ishome'        => new sfValidatorPass(array('required' => false)),
      'crosses'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'passes'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'league'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('soccer_stats_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SoccerStats';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'schid'         => 'Number',
      'team_id'       => 'ForeignKey',
      'team'          => 'Text',
      'goal'          => 'Text',
      'shots'         => 'Number',
      'shots_ongoal'  => 'Number',
      'corner_kicks'  => 'Number',
      'offsides'      => 'Text',
      'yellow_cards'  => 'Text',
      'red_cards'     => 'Text',
      'fouls'         => 'Text',
      'possession'    => 'Text',
      'penaltyGoals'  => 'Text',
      'blocks'        => 'Text',
      'interceptions' => 'Text',
      'ishome'        => 'Text',
      'crosses'       => 'Number',
      'passes'        => 'Number',
      'league'        => 'Text',
    );
  }
}
