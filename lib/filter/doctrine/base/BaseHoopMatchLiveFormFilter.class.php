<?php

/**
 * HoopMatchLive filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseHoopMatchLiveFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'match_id'          => new sfWidgetFormFilterInput(),
      'sequence_id'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'minutes'           => new sfWidgetFormFilterInput(),
      'seconds'           => new sfWidgetFormFilterInput(),
      'team_id'           => new sfWidgetFormFilterInput(),
      'player_id'         => new sfWidgetFormFilterInput(),
      'home_score'        => new sfWidgetFormFilterInput(),
      'away_score'        => new sfWidgetFormFilterInput(),
      'event_type'        => new sfWidgetFormFilterInput(),
      'event_detail_type' => new sfWidgetFormFilterInput(),
      'shot_coord_x'      => new sfWidgetFormFilterInput(),
      'shot_coord_y'      => new sfWidgetFormFilterInput(),
      'quarter'           => new sfWidgetFormFilterInput(),
      'event_text_cn'     => new sfWidgetFormFilterInput(),
      'event_text_en'     => new sfWidgetFormFilterInput(),
      'distance'          => new sfWidgetFormFilterInput(),
      'player_score'      => new sfWidgetFormFilterInput(),
      'player_fouls'      => new sfWidgetFormFilterInput(),
      'match_time'        => new sfWidgetFormFilterInput(),
      'points_type'       => new sfWidgetFormFilterInput(),
      'is_home'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'match_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sequence_id'       => new sfValidatorPass(array('required' => false)),
      'minutes'           => new sfValidatorPass(array('required' => false)),
      'seconds'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'team_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'player_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'home_score'        => new sfValidatorPass(array('required' => false)),
      'away_score'        => new sfValidatorPass(array('required' => false)),
      'event_type'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'event_detail_type' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'shot_coord_x'      => new sfValidatorPass(array('required' => false)),
      'shot_coord_y'      => new sfValidatorPass(array('required' => false)),
      'quarter'           => new sfValidatorPass(array('required' => false)),
      'event_text_cn'     => new sfValidatorPass(array('required' => false)),
      'event_text_en'     => new sfValidatorPass(array('required' => false)),
      'distance'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'player_score'      => new sfValidatorPass(array('required' => false)),
      'player_fouls'      => new sfValidatorPass(array('required' => false)),
      'match_time'        => new sfValidatorPass(array('required' => false)),
      'points_type'       => new sfValidatorPass(array('required' => false)),
      'is_home'           => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('hoop_match_live_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'HoopMatchLive';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'match_id'          => 'Number',
      'sequence_id'       => 'Text',
      'minutes'           => 'Text',
      'seconds'           => 'Number',
      'team_id'           => 'Number',
      'player_id'         => 'Number',
      'home_score'        => 'Text',
      'away_score'        => 'Text',
      'event_type'        => 'Number',
      'event_detail_type' => 'Number',
      'shot_coord_x'      => 'Text',
      'shot_coord_y'      => 'Text',
      'quarter'           => 'Text',
      'event_text_cn'     => 'Text',
      'event_text_en'     => 'Text',
      'distance'          => 'Number',
      'player_score'      => 'Text',
      'player_fouls'      => 'Text',
      'match_time'        => 'Text',
      'points_type'       => 'Text',
      'is_home'           => 'Text',
    );
  }
}
