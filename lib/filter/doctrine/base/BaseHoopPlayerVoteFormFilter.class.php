<?php

/**
 * HoopPlayerVote filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseHoopPlayerVoteFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'match_id'  => new sfWidgetFormFilterInput(),
      'player_id' => new sfWidgetFormFilterInput(),
      'vote'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'match_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'player_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'vote'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('hoop_player_vote_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'HoopPlayerVote';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'match_id'  => 'Number',
      'player_id' => 'Number',
      'vote'      => 'Number',
    );
  }
}
