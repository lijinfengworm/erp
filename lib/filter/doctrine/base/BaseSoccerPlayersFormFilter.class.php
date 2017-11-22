<?php

/**
 * SoccerPlayers filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSoccerPlayersFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'        => new sfWidgetFormFilterInput(),
      'country'     => new sfWidgetFormFilterInput(),
      'full_name'   => new sfWidgetFormFilterInput(),
      'eng_name'    => new sfWidgetFormFilterInput(),
      'number'      => new sfWidgetFormFilterInput(),
      'position'    => new sfWidgetFormFilterInput(),
      'red_card'    => new sfWidgetFormFilterInput(),
      'yellow_card' => new sfWidgetFormFilterInput(),
      'goal'        => new sfWidgetFormFilterInput(),
      'injury'      => new sfWidgetFormFilterInput(),
      'teamID'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'        => new sfValidatorPass(array('required' => false)),
      'country'     => new sfValidatorPass(array('required' => false)),
      'full_name'   => new sfValidatorPass(array('required' => false)),
      'eng_name'    => new sfValidatorPass(array('required' => false)),
      'number'      => new sfValidatorPass(array('required' => false)),
      'position'    => new sfValidatorPass(array('required' => false)),
      'red_card'    => new sfValidatorPass(array('required' => false)),
      'yellow_card' => new sfValidatorPass(array('required' => false)),
      'goal'        => new sfValidatorPass(array('required' => false)),
      'injury'      => new sfValidatorPass(array('required' => false)),
      'teamID'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('soccer_players_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SoccerPlayers';
  }

  public function getFields()
  {
    return array(
      'player_id'   => 'Number',
      'name'        => 'Text',
      'country'     => 'Text',
      'full_name'   => 'Text',
      'eng_name'    => 'Text',
      'number'      => 'Text',
      'position'    => 'Text',
      'red_card'    => 'Text',
      'yellow_card' => 'Text',
      'goal'        => 'Text',
      'injury'      => 'Text',
      'teamID'      => 'Number',
    );
  }
}
