<?php

/**
 * SoccerLiveEvents filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSoccerLiveEventsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'schid'          => new sfWidgetFormFilterInput(),
      'sequence_id'    => new sfWidgetFormFilterInput(),
      'player'         => new sfWidgetFormFilterInput(),
      'rel_player'     => new sfWidgetFormFilterInput(),
      'team_id'        => new sfWidgetFormFilterInput(),
      'team'           => new sfWidgetFormFilterInput(),
      'live_goals'     => new sfWidgetFormFilterInput(),
      'live_out_goals' => new sfWidgetFormFilterInput(),
      'half_id'        => new sfWidgetFormFilterInput(),
      'live_time'      => new sfWidgetFormFilterInput(),
      'event_number'   => new sfWidgetFormFilterInput(),
      'event_text'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'schid'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sequence_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'player'         => new sfValidatorPass(array('required' => false)),
      'rel_player'     => new sfValidatorPass(array('required' => false)),
      'team_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'team'           => new sfValidatorPass(array('required' => false)),
      'live_goals'     => new sfValidatorPass(array('required' => false)),
      'live_out_goals' => new sfValidatorPass(array('required' => false)),
      'half_id'        => new sfValidatorPass(array('required' => false)),
      'live_time'      => new sfValidatorPass(array('required' => false)),
      'event_number'   => new sfValidatorPass(array('required' => false)),
      'event_text'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('soccer_live_events_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SoccerLiveEvents';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'schid'          => 'Number',
      'sequence_id'    => 'Number',
      'player'         => 'Text',
      'rel_player'     => 'Text',
      'team_id'        => 'Number',
      'team'           => 'Text',
      'live_goals'     => 'Text',
      'live_out_goals' => 'Text',
      'half_id'        => 'Text',
      'live_time'      => 'Text',
      'event_number'   => 'Text',
      'event_text'     => 'Text',
    );
  }
}
