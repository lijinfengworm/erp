<?php

/**
 * HoopMatchStats filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseHoopMatchStatsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'match_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('hoopMatch'), 'add_empty' => true)),
      'team_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('hoopTeam'), 'add_empty' => true)),
      'team_name'      => new sfWidgetFormFilterInput(),
      'opt_team_id'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'opt_team_name'  => new sfWidgetFormFilterInput(),
      'usa_time'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'china_time'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'season'         => new sfWidgetFormFilterInput(),
      'match_type'     => new sfWidgetFormFilterInput(),
      'mins'           => new sfWidgetFormFilterInput(),
      'pts'            => new sfWidgetFormFilterInput(),
      'fga'            => new sfWidgetFormFilterInput(),
      'fgm'            => new sfWidgetFormFilterInput(),
      'tga'            => new sfWidgetFormFilterInput(),
      'tgm'            => new sfWidgetFormFilterInput(),
      'fta'            => new sfWidgetFormFilterInput(),
      'ftm'            => new sfWidgetFormFilterInput(),
      'dreb'           => new sfWidgetFormFilterInput(),
      'oreb'           => new sfWidgetFormFilterInput(),
      'reb'            => new sfWidgetFormFilterInput(),
      'ast'            => new sfWidgetFormFilterInput(),
      'st'             => new sfWidgetFormFilterInput(),
      'to'             => new sfWidgetFormFilterInput(),
      'pf'             => new sfWidgetFormFilterInput(),
      'tf'             => new sfWidgetFormFilterInput(),
      'ff'             => new sfWidgetFormFilterInput(),
      'blk'            => new sfWidgetFormFilterInput(),
      'team_to'        => new sfWidgetFormFilterInput(),
      'points_off'     => new sfWidgetFormFilterInput(),
      'fast_scores'    => new sfWidgetFormFilterInput(),
      'paint_scores'   => new sfWidgetFormFilterInput(),
      'max_leader'     => new sfWidgetFormFilterInput(),
      'first_scores'   => new sfWidgetFormFilterInput(),
      'second_scores'  => new sfWidgetFormFilterInput(),
      'third_scores'   => new sfWidgetFormFilterInput(),
      'fourth_scores'  => new sfWidgetFormFilterInput(),
      'ot_scores'      => new sfWidgetFormFilterInput(),
      'full_time_out'  => new sfWidgetFormFilterInput(),
      'short_time_out' => new sfWidgetFormFilterInput(),
      'fouls'          => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'match_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('hoopMatch'), 'column' => 'id')),
      'team_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('hoopTeam'), 'column' => 'id')),
      'team_name'      => new sfValidatorPass(array('required' => false)),
      'opt_team_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'opt_team_name'  => new sfValidatorPass(array('required' => false)),
      'usa_time'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'china_time'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'season'         => new sfValidatorPass(array('required' => false)),
      'match_type'     => new sfValidatorPass(array('required' => false)),
      'mins'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pts'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fga'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fgm'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tga'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tgm'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fta'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ftm'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'dreb'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'oreb'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reb'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ast'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'st'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'to'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pf'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tf'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ff'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'blk'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'team_to'        => new sfValidatorPass(array('required' => false)),
      'points_off'     => new sfValidatorPass(array('required' => false)),
      'fast_scores'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'paint_scores'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'max_leader'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'first_scores'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'second_scores'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'third_scores'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fourth_scores'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ot_scores'      => new sfValidatorPass(array('required' => false)),
      'full_time_out'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'short_time_out' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fouls'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('hoop_match_stats_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'HoopMatchStats';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'match_id'       => 'ForeignKey',
      'team_id'        => 'ForeignKey',
      'team_name'      => 'Text',
      'opt_team_id'    => 'Number',
      'opt_team_name'  => 'Text',
      'usa_time'       => 'Date',
      'china_time'     => 'Date',
      'season'         => 'Text',
      'match_type'     => 'Text',
      'mins'           => 'Number',
      'pts'            => 'Number',
      'fga'            => 'Number',
      'fgm'            => 'Number',
      'tga'            => 'Number',
      'tgm'            => 'Number',
      'fta'            => 'Number',
      'ftm'            => 'Number',
      'dreb'           => 'Number',
      'oreb'           => 'Number',
      'reb'            => 'Number',
      'ast'            => 'Number',
      'st'             => 'Number',
      'to'             => 'Number',
      'pf'             => 'Number',
      'tf'             => 'Number',
      'ff'             => 'Number',
      'blk'            => 'Number',
      'team_to'        => 'Text',
      'points_off'     => 'Text',
      'fast_scores'    => 'Number',
      'paint_scores'   => 'Number',
      'max_leader'     => 'Number',
      'first_scores'   => 'Number',
      'second_scores'  => 'Number',
      'third_scores'   => 'Number',
      'fourth_scores'  => 'Number',
      'ot_scores'      => 'Text',
      'full_time_out'  => 'Number',
      'short_time_out' => 'Number',
      'fouls'          => 'Number',
    );
  }
}
