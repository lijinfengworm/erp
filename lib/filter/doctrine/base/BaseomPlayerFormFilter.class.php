<?php

/**
 * omPlayer filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseomPlayerFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'              => new sfWidgetFormFilterInput(),
      'id_number'         => new sfWidgetFormFilterInput(),
      'nick_name'         => new sfWidgetFormFilterInput(),
      'avatar_url'        => new sfWidgetFormFilterInput(),
      'number'            => new sfWidgetFormFilterInput(),
      'height'            => new sfWidgetFormFilterInput(),
      'weight'            => new sfWidgetFormFilterInput(),
      'age'               => new sfWidgetFormFilterInput(),
      'qq'                => new sfWidgetFormFilterInput(),
      'mobile'            => new sfWidgetFormFilterInput(),
      'remarks'           => new sfWidgetFormFilterInput(),
      'position'          => new sfWidgetFormFilterInput(),
      'om_teams_list'     => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'omTeam')),
      'gh_positions_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'ghPosition')),
      'om_matchs_list'    => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'omMatch')),
      'om_notices_list'   => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'omNotice')),
    ));

    $this->setValidators(array(
      'name'              => new sfValidatorPass(array('required' => false)),
      'id_number'         => new sfValidatorPass(array('required' => false)),
      'nick_name'         => new sfValidatorPass(array('required' => false)),
      'avatar_url'        => new sfValidatorPass(array('required' => false)),
      'number'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'height'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'weight'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'age'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'qq'                => new sfValidatorPass(array('required' => false)),
      'mobile'            => new sfValidatorPass(array('required' => false)),
      'remarks'           => new sfValidatorPass(array('required' => false)),
      'position'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'om_teams_list'     => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'omTeam', 'required' => false)),
      'gh_positions_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'ghPosition', 'required' => false)),
      'om_matchs_list'    => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'omMatch', 'required' => false)),
      'om_notices_list'   => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'omNotice', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('om_player_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addOmTeamsListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.omMembership omMembership')
      ->andWhereIn('omMembership.om_team_id', $values)
    ;
  }

  public function addGhPositionsListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.omPlayerPosition omPlayerPosition')
      ->andWhereIn('omPlayerPosition.gh_position_id', $values)
    ;
  }

  public function addOmMatchsListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.omMatchPlayer omMatchPlayer')
      ->andWhereIn('omMatchPlayer.om_match_id', $values)
    ;
  }

  public function addOmNoticesListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.omNoticePlayer omNoticePlayer')
      ->andWhereIn('omNoticePlayer.om_notice_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'omPlayer';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'name'              => 'Text',
      'id_number'         => 'Text',
      'nick_name'         => 'Text',
      'avatar_url'        => 'Text',
      'number'            => 'Number',
      'height'            => 'Number',
      'weight'            => 'Number',
      'age'               => 'Number',
      'qq'                => 'Text',
      'mobile'            => 'Text',
      'remarks'           => 'Text',
      'position'          => 'Number',
      'om_teams_list'     => 'ManyKey',
      'gh_positions_list' => 'ManyKey',
      'om_matchs_list'    => 'ManyKey',
      'om_notices_list'   => 'ManyKey',
    );
  }
}
