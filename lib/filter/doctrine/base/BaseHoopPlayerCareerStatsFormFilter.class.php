<?php

/**
 * HoopPlayerCareerStats filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseHoopPlayerCareerStatsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'player_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('hoopPlayer'), 'add_empty' => true)),
      'player_name'   => new sfWidgetFormFilterInput(),
      'team_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('hoopTeam'), 'add_empty' => true)),
      'team_name'     => new sfWidgetFormFilterInput(),
      'season'        => new sfWidgetFormFilterInput(),
      'match_type'    => new sfWidgetFormFilterInput(),
      'games'         => new sfWidgetFormFilterInput(),
      'games_started' => new sfWidgetFormFilterInput(),
      'mins'          => new sfWidgetFormFilterInput(),
      'pts'           => new sfWidgetFormFilterInput(),
      'fga'           => new sfWidgetFormFilterInput(),
      'fgm'           => new sfWidgetFormFilterInput(),
      'fgp'           => new sfWidgetFormFilterInput(),
      'tpt'           => new sfWidgetFormFilterInput(),
      'tpa'           => new sfWidgetFormFilterInput(),
      'tpm'           => new sfWidgetFormFilterInput(),
      'tpp'           => new sfWidgetFormFilterInput(),
      'fpt'           => new sfWidgetFormFilterInput(),
      'fta'           => new sfWidgetFormFilterInput(),
      'ftm'           => new sfWidgetFormFilterInput(),
      'ftp'           => new sfWidgetFormFilterInput(),
      'dreb'          => new sfWidgetFormFilterInput(),
      'oreb'          => new sfWidgetFormFilterInput(),
      'reb'           => new sfWidgetFormFilterInput(),
      'asts'          => new sfWidgetFormFilterInput(),
      'stl'           => new sfWidgetFormFilterInput(),
      'blk'           => new sfWidgetFormFilterInput(),
      'to'            => new sfWidgetFormFilterInput(),
      'pf'            => new sfWidgetFormFilterInput(),
      'tf'            => new sfWidgetFormFilterInput(),
      'plus_minus'    => new sfWidgetFormFilterInput(),
      'sequence'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'player_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('hoopPlayer'), 'column' => 'id')),
      'player_name'   => new sfValidatorPass(array('required' => false)),
      'team_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('hoopTeam'), 'column' => 'id')),
      'team_name'     => new sfValidatorPass(array('required' => false)),
      'season'        => new sfValidatorPass(array('required' => false)),
      'match_type'    => new sfValidatorPass(array('required' => false)),
      'games'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'games_started' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'mins'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pts'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fga'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fgm'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fgp'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'tpt'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tpa'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tpm'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tpp'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'fpt'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fta'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ftm'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ftp'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'dreb'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'oreb'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reb'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'asts'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stl'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'blk'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'to'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pf'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tf'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'plus_minus'    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'sequence'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('hoop_player_career_stats_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'HoopPlayerCareerStats';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'player_id'     => 'ForeignKey',
      'player_name'   => 'Text',
      'team_id'       => 'ForeignKey',
      'team_name'     => 'Text',
      'season'        => 'Text',
      'match_type'    => 'Text',
      'games'         => 'Number',
      'games_started' => 'Number',
      'mins'          => 'Number',
      'pts'           => 'Number',
      'fga'           => 'Number',
      'fgm'           => 'Number',
      'fgp'           => 'Number',
      'tpt'           => 'Number',
      'tpa'           => 'Number',
      'tpm'           => 'Number',
      'tpp'           => 'Number',
      'fpt'           => 'Number',
      'fta'           => 'Number',
      'ftm'           => 'Number',
      'ftp'           => 'Number',
      'dreb'          => 'Number',
      'oreb'          => 'Number',
      'reb'           => 'Number',
      'asts'          => 'Number',
      'stl'           => 'Number',
      'blk'           => 'Number',
      'to'            => 'Number',
      'pf'            => 'Number',
      'tf'            => 'Number',
      'plus_minus'    => 'Number',
      'sequence'      => 'Number',
    );
  }
}
