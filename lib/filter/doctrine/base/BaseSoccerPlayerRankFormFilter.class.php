<?php

/**
 * SoccerPlayerRank filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSoccerPlayerRankFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'player_id'   => new sfWidgetFormFilterInput(),
      'sportrait'   => new sfWidgetFormFilterInput(),
      'player'      => new sfWidgetFormFilterInput(),
      'player_en'   => new sfWidgetFormFilterInput(),
      'position'    => new sfWidgetFormFilterInput(),
      'team'        => new sfWidgetFormFilterInput(),
      'team_id'     => new sfWidgetFormFilterInput(),
      'stats_value' => new sfWidgetFormFilterInput(),
      'ranking'     => new sfWidgetFormFilterInput(),
      'rank_type'   => new sfWidgetFormFilterInput(),
      'league'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'player_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sportrait'   => new sfValidatorPass(array('required' => false)),
      'player'      => new sfValidatorPass(array('required' => false)),
      'player_en'   => new sfValidatorPass(array('required' => false)),
      'position'    => new sfValidatorPass(array('required' => false)),
      'team'        => new sfValidatorPass(array('required' => false)),
      'team_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stats_value' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ranking'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rank_type'   => new sfValidatorPass(array('required' => false)),
      'league'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('soccer_player_rank_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SoccerPlayerRank';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'player_id'   => 'Number',
      'sportrait'   => 'Text',
      'player'      => 'Text',
      'player_en'   => 'Text',
      'position'    => 'Text',
      'team'        => 'Text',
      'team_id'     => 'Number',
      'stats_value' => 'Number',
      'ranking'     => 'Number',
      'rank_type'   => 'Text',
      'league'      => 'Text',
    );
  }
}
