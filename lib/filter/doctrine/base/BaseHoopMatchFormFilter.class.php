<?php

/**
 * HoopMatch filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseHoopMatchFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'espn_id'           => new sfWidgetFormFilterInput(),
      'beitai_mid'        => new sfWidgetFormFilterInput(),
      'lehecai_id'        => new sfWidgetFormFilterInput(),
      'stadium_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('stadium'), 'add_empty' => true)),
      'status'            => new sfWidgetFormFilterInput(),
      'home_team_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('homeTeam'), 'add_empty' => true)),
      'home_team_name'    => new sfWidgetFormFilterInput(),
      'home_score'        => new sfWidgetFormFilterInput(),
      'away_team_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('awayTeam'), 'add_empty' => true)),
      'away_team_name'    => new sfWidgetFormFilterInput(),
      'away_score'        => new sfWidgetFormFilterInput(),
      'attendance'        => new sfWidgetFormFilterInput(),
      'china_time'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'usa_time'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'season'            => new sfWidgetFormFilterInput(),
      'match_type'        => new sfWidgetFormFilterInput(),
      'memo'              => new sfWidgetFormFilterInput(),
      'game_time'         => new sfWidgetFormFilterInput(),
      'match_time'        => new sfWidgetFormFilterInput(),
      'home_fast'         => new sfWidgetFormFilterInput(),
      'away_fast'         => new sfWidgetFormFilterInput(),
      'home_biggest_lead' => new sfWidgetFormFilterInput(),
      'away_biggest_lead' => new sfWidgetFormFilterInput(),
      'home_paint'        => new sfWidgetFormFilterInput(),
      'away_paint'        => new sfWidgetFormFilterInput(),
      'lottery'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'espn_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'beitai_mid'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lehecai_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stadium_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('stadium'), 'column' => 'id')),
      'status'            => new sfValidatorPass(array('required' => false)),
      'home_team_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('homeTeam'), 'column' => 'id')),
      'home_team_name'    => new sfValidatorPass(array('required' => false)),
      'home_score'        => new sfValidatorPass(array('required' => false)),
      'away_team_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('awayTeam'), 'column' => 'id')),
      'away_team_name'    => new sfValidatorPass(array('required' => false)),
      'away_score'        => new sfValidatorPass(array('required' => false)),
      'attendance'        => new sfValidatorPass(array('required' => false)),
      'china_time'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'usa_time'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'season'            => new sfValidatorPass(array('required' => false)),
      'match_type'        => new sfValidatorPass(array('required' => false)),
      'memo'              => new sfValidatorPass(array('required' => false)),
      'game_time'         => new sfValidatorPass(array('required' => false)),
      'match_time'        => new sfValidatorPass(array('required' => false)),
      'home_fast'         => new sfValidatorPass(array('required' => false)),
      'away_fast'         => new sfValidatorPass(array('required' => false)),
      'home_biggest_lead' => new sfValidatorPass(array('required' => false)),
      'away_biggest_lead' => new sfValidatorPass(array('required' => false)),
      'home_paint'        => new sfValidatorPass(array('required' => false)),
      'away_paint'        => new sfValidatorPass(array('required' => false)),
      'lottery'           => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('hoop_match_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'HoopMatch';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'espn_id'           => 'Number',
      'beitai_mid'        => 'Number',
      'lehecai_id'        => 'Number',
      'stadium_id'        => 'ForeignKey',
      'status'            => 'Text',
      'home_team_id'      => 'ForeignKey',
      'home_team_name'    => 'Text',
      'home_score'        => 'Text',
      'away_team_id'      => 'ForeignKey',
      'away_team_name'    => 'Text',
      'away_score'        => 'Text',
      'attendance'        => 'Text',
      'china_time'        => 'Date',
      'usa_time'          => 'Date',
      'season'            => 'Text',
      'match_type'        => 'Text',
      'memo'              => 'Text',
      'game_time'         => 'Text',
      'match_time'        => 'Text',
      'home_fast'         => 'Text',
      'away_fast'         => 'Text',
      'home_biggest_lead' => 'Text',
      'away_biggest_lead' => 'Text',
      'home_paint'        => 'Text',
      'away_paint'        => 'Text',
      'lottery'           => 'Text',
    );
  }
}
