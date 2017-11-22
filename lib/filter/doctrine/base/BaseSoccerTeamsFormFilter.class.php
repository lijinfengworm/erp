<?php

/**
 * SoccerTeams filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSoccerTeamsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'team_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('soccerStats'), 'add_empty' => true)),
      'team'          => new sfWidgetFormFilterInput(),
      'full_name'     => new sfWidgetFormFilterInput(),
      'team_eng_name' => new sfWidgetFormFilterInput(),
      'eng_full_name' => new sfWidgetFormFilterInput(),
      'stadium'       => new sfWidgetFormFilterInput(),
      'eng_stadium'   => new sfWidgetFormFilterInput(),
      'found'         => new sfWidgetFormFilterInput(),
      'champions'     => new sfWidgetFormFilterInput(),
      'coach'         => new sfWidgetFormFilterInput(),
      'coach_age'     => new sfWidgetFormFilterInput(),
      'coach_year'    => new sfWidgetFormFilterInput(),
      'league'        => new sfWidgetFormFilterInput(),
      'group'         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'team_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('soccerStats'), 'column' => 'id')),
      'team'          => new sfValidatorPass(array('required' => false)),
      'full_name'     => new sfValidatorPass(array('required' => false)),
      'team_eng_name' => new sfValidatorPass(array('required' => false)),
      'eng_full_name' => new sfValidatorPass(array('required' => false)),
      'stadium'       => new sfValidatorPass(array('required' => false)),
      'eng_stadium'   => new sfValidatorPass(array('required' => false)),
      'found'         => new sfValidatorPass(array('required' => false)),
      'champions'     => new sfValidatorPass(array('required' => false)),
      'coach'         => new sfValidatorPass(array('required' => false)),
      'coach_age'     => new sfValidatorPass(array('required' => false)),
      'coach_year'    => new sfValidatorPass(array('required' => false)),
      'league'        => new sfValidatorPass(array('required' => false)),
      'group'         => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('soccer_teams_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SoccerTeams';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'team_id'       => 'ForeignKey',
      'team'          => 'Text',
      'full_name'     => 'Text',
      'team_eng_name' => 'Text',
      'eng_full_name' => 'Text',
      'stadium'       => 'Text',
      'eng_stadium'   => 'Text',
      'found'         => 'Text',
      'champions'     => 'Text',
      'coach'         => 'Text',
      'coach_age'     => 'Text',
      'coach_year'    => 'Text',
      'league'        => 'Text',
      'group'         => 'Text',
    );
  }
}
