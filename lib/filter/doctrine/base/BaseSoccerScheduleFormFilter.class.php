<?php

/**
 * SoccerSchedule filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSoccerScheduleFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'home_team_id'    => new sfWidgetFormFilterInput(),
      'home_team'       => new sfWidgetFormFilterInput(),
      'home_score'      => new sfWidgetFormFilterInput(),
      'home_out_goals'  => new sfWidgetFormFilterInput(),
      'visit_team_id'   => new sfWidgetFormFilterInput(),
      'visit_team'      => new sfWidgetFormFilterInput(),
      'visit_score'     => new sfWidgetFormFilterInput(),
      'visit_out_goals' => new sfWidgetFormFilterInput(),
      'status'          => new sfWidgetFormFilterInput(),
      'match_type'      => new sfWidgetFormFilterInput(),
      'round'           => new sfWidgetFormFilterInput(),
      'league'          => new sfWidgetFormFilterInput(),
      'match_time'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'home_team_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'home_team'       => new sfValidatorPass(array('required' => false)),
      'home_score'      => new sfValidatorPass(array('required' => false)),
      'home_out_goals'  => new sfValidatorPass(array('required' => false)),
      'visit_team_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'visit_team'      => new sfValidatorPass(array('required' => false)),
      'visit_score'     => new sfValidatorPass(array('required' => false)),
      'visit_out_goals' => new sfValidatorPass(array('required' => false)),
      'status'          => new sfValidatorPass(array('required' => false)),
      'match_type'      => new sfValidatorPass(array('required' => false)),
      'round'           => new sfValidatorPass(array('required' => false)),
      'league'          => new sfValidatorPass(array('required' => false)),
      'match_time'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('soccer_schedule_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SoccerSchedule';
  }

  public function getFields()
  {
    return array(
      'schid'           => 'Number',
      'home_team_id'    => 'Number',
      'home_team'       => 'Text',
      'home_score'      => 'Text',
      'home_out_goals'  => 'Text',
      'visit_team_id'   => 'Number',
      'visit_team'      => 'Text',
      'visit_score'     => 'Text',
      'visit_out_goals' => 'Text',
      'status'          => 'Text',
      'match_type'      => 'Text',
      'round'           => 'Text',
      'league'          => 'Text',
      'match_time'      => 'Number',
    );
  }
}
