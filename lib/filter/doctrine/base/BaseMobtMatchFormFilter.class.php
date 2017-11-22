<?php

/**
 * MobtMatch filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseMobtMatchFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'nowgoal_id'        => new sfWidgetFormFilterInput(),
      'state'             => new sfWidgetFormFilterInput(),
      'home_team_id'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'home_team_en_name' => new sfWidgetFormFilterInput(),
      'home_team_cn_name' => new sfWidgetFormFilterInput(),
      'home_nation_flag'  => new sfWidgetFormFilterInput(),
      'home_score'        => new sfWidgetFormFilterInput(),
      'away_team_id'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'away_team_en_name' => new sfWidgetFormFilterInput(),
      'away_team_cn_name' => new sfWidgetFormFilterInput(),
      'away_team_name'    => new sfWidgetFormFilterInput(),
      'away_nation_flag'  => new sfWidgetFormFilterInput(),
      'away_score'        => new sfWidgetFormFilterInput(),
      'china_time'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'recap_title'       => new sfWidgetFormFilterInput(),
      'recap_link'        => new sfWidgetFormFilterInput(),
      'live_link'         => new sfWidgetFormFilterInput(),
      'video_link'        => new sfWidgetFormFilterInput(),
      'match_time'        => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'nowgoal_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'state'             => new sfValidatorPass(array('required' => false)),
      'home_team_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'home_team_en_name' => new sfValidatorPass(array('required' => false)),
      'home_team_cn_name' => new sfValidatorPass(array('required' => false)),
      'home_nation_flag'  => new sfValidatorPass(array('required' => false)),
      'home_score'        => new sfValidatorPass(array('required' => false)),
      'away_team_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'away_team_en_name' => new sfValidatorPass(array('required' => false)),
      'away_team_cn_name' => new sfValidatorPass(array('required' => false)),
      'away_team_name'    => new sfValidatorPass(array('required' => false)),
      'away_nation_flag'  => new sfValidatorPass(array('required' => false)),
      'away_score'        => new sfValidatorPass(array('required' => false)),
      'china_time'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'recap_title'       => new sfValidatorPass(array('required' => false)),
      'recap_link'        => new sfValidatorPass(array('required' => false)),
      'live_link'         => new sfValidatorPass(array('required' => false)),
      'video_link'        => new sfValidatorPass(array('required' => false)),
      'match_time'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('mobt_match_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MobtMatch';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'nowgoal_id'        => 'Number',
      'state'             => 'Text',
      'home_team_id'      => 'Number',
      'home_team_en_name' => 'Text',
      'home_team_cn_name' => 'Text',
      'home_nation_flag'  => 'Text',
      'home_score'        => 'Text',
      'away_team_id'      => 'Number',
      'away_team_en_name' => 'Text',
      'away_team_cn_name' => 'Text',
      'away_team_name'    => 'Text',
      'away_nation_flag'  => 'Text',
      'away_score'        => 'Text',
      'china_time'        => 'Date',
      'recap_title'       => 'Text',
      'recap_link'        => 'Text',
      'live_link'         => 'Text',
      'video_link'        => 'Text',
      'match_time'        => 'Text',
    );
  }
}
