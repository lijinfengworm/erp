<?php

/**
 * HoopPlayerMatchStats filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseHoopPlayerMatchStatsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'player_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('hoopPlayer'), 'add_empty' => true)),
      'beitai_pid'  => new sfWidgetFormFilterInput(),
      'player_name' => new sfWidgetFormFilterInput(),
      'team_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('hoopTeam'), 'add_empty' => true)),
      'team_name'   => new sfWidgetFormFilterInput(),
      'match_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('hoopMatch'), 'add_empty' => true)),
      'usa_time'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'china_time'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'season'      => new sfWidgetFormFilterInput(),
      'match_type'  => new sfWidgetFormFilterInput(),
      'mins'        => new sfWidgetFormFilterInput(),
      'pts'         => new sfWidgetFormFilterInput(),
      'fga'         => new sfWidgetFormFilterInput(),
      'fgm'         => new sfWidgetFormFilterInput(),
      'tpa'         => new sfWidgetFormFilterInput(),
      'tpm'         => new sfWidgetFormFilterInput(),
      'fta'         => new sfWidgetFormFilterInput(),
      'ftm'         => new sfWidgetFormFilterInput(),
      'dreb'        => new sfWidgetFormFilterInput(),
      'oreb'        => new sfWidgetFormFilterInput(),
      'reb'         => new sfWidgetFormFilterInput(),
      'asts'        => new sfWidgetFormFilterInput(),
      'stl'         => new sfWidgetFormFilterInput(),
      'blk'         => new sfWidgetFormFilterInput(),
      'to'          => new sfWidgetFormFilterInput(),
      'pf'          => new sfWidgetFormFilterInput(),
      'tf'          => new sfWidgetFormFilterInput(),
      'ff'          => new sfWidgetFormFilterInput(),
      'blkr'        => new sfWidgetFormFilterInput(),
      'foulr'       => new sfWidgetFormFilterInput(),
      'net_points'  => new sfWidgetFormFilterInput(),
      'dnp'         => new sfWidgetFormFilterInput(),
      'is_starter'  => new sfWidgetFormFilterInput(),
      'on_court'    => new sfWidgetFormFilterInput(),
      'position'    => new sfWidgetFormFilterInput(),
      'pfa'         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'player_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('hoopPlayer'), 'column' => 'id')),
      'beitai_pid'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'player_name' => new sfValidatorPass(array('required' => false)),
      'team_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('hoopTeam'), 'column' => 'id')),
      'team_name'   => new sfValidatorPass(array('required' => false)),
      'match_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('hoopMatch'), 'column' => 'id')),
      'usa_time'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'china_time'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'season'      => new sfValidatorPass(array('required' => false)),
      'match_type'  => new sfValidatorPass(array('required' => false)),
      'mins'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pts'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fga'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fgm'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tpa'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tpm'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fta'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ftm'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'dreb'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'oreb'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reb'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'asts'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stl'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'blk'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'to'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pf'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tf'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ff'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'blkr'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'foulr'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'net_points'  => new sfValidatorPass(array('required' => false)),
      'dnp'         => new sfValidatorPass(array('required' => false)),
      'is_starter'  => new sfValidatorPass(array('required' => false)),
      'on_court'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'position'    => new sfValidatorPass(array('required' => false)),
      'pfa'         => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('hoop_player_match_stats_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'HoopPlayerMatchStats';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'player_id'   => 'ForeignKey',
      'beitai_pid'  => 'Number',
      'player_name' => 'Text',
      'team_id'     => 'ForeignKey',
      'team_name'   => 'Text',
      'match_id'    => 'ForeignKey',
      'usa_time'    => 'Date',
      'china_time'  => 'Date',
      'season'      => 'Text',
      'match_type'  => 'Text',
      'mins'        => 'Number',
      'pts'         => 'Number',
      'fga'         => 'Number',
      'fgm'         => 'Number',
      'tpa'         => 'Number',
      'tpm'         => 'Number',
      'fta'         => 'Number',
      'ftm'         => 'Number',
      'dreb'        => 'Number',
      'oreb'        => 'Number',
      'reb'         => 'Number',
      'asts'        => 'Number',
      'stl'         => 'Number',
      'blk'         => 'Number',
      'to'          => 'Number',
      'pf'          => 'Number',
      'tf'          => 'Number',
      'ff'          => 'Number',
      'blkr'        => 'Number',
      'foulr'       => 'Number',
      'net_points'  => 'Text',
      'dnp'         => 'Text',
      'is_starter'  => 'Text',
      'on_court'    => 'Number',
      'position'    => 'Text',
      'pfa'         => 'Text',
    );
  }
}
