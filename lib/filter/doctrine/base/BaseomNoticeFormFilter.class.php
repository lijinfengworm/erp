<?php

/**
 * omNotice filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseomNoticeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'type'            => new sfWidgetFormChoice(array('choices' => array('' => '', 'TOPLAYER' => 'TOPLAYER', 'TOTEAM' => 'TOTEAM', 'TOLOCATION' => 'TOLOCATION', 'TOMATCH' => 'TOMATCH'))),
      'status'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'om_match_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMatch'), 'add_empty' => true)),
      'msg_type'        => new sfWidgetFormChoice(array('choices' => array('' => '', 'SMS' => 'SMS', 'EMAIL' => 'EMAIL', 'PM' => 'PM'))),
      'title'           => new sfWidgetFormFilterInput(),
      'content'         => new sfWidgetFormFilterInput(),
      'approve_user_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('approveUser'), 'add_empty' => true)),
      'locations_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Location')),
      'om_players_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'omPlayer')),
      'om_teams_list'   => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'omTeam')),
    ));

    $this->setValidators(array(
      'type'            => new sfValidatorChoice(array('required' => false, 'choices' => array('TOPLAYER' => 'TOPLAYER', 'TOTEAM' => 'TOTEAM', 'TOLOCATION' => 'TOLOCATION', 'TOMATCH' => 'TOMATCH'))),
      'status'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'om_match_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMatch'), 'column' => 'id')),
      'msg_type'        => new sfValidatorChoice(array('required' => false, 'choices' => array('SMS' => 'SMS', 'EMAIL' => 'EMAIL', 'PM' => 'PM'))),
      'title'           => new sfValidatorPass(array('required' => false)),
      'content'         => new sfValidatorPass(array('required' => false)),
      'approve_user_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('approveUser'), 'column' => 'id')),
      'locations_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Location', 'required' => false)),
      'om_players_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'omPlayer', 'required' => false)),
      'om_teams_list'   => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'omTeam', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('om_notice_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addLocationsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.omNoticeLocation omNoticeLocation')
      ->andWhereIn('omNoticeLocation.location_id', $values)
    ;
  }

  public function addOmPlayersListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('omNoticePlayer.om_player_id', $values)
    ;
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
      ->leftJoin($query->getRootAlias().'.omNoticeTeam omNoticeTeam')
      ->andWhereIn('omNoticeTeam.om_team_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'omNotice';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'type'            => 'Enum',
      'status'          => 'Number',
      'om_match_id'     => 'ForeignKey',
      'msg_type'        => 'Enum',
      'title'           => 'Text',
      'content'         => 'Text',
      'approve_user_id' => 'ForeignKey',
      'locations_list'  => 'ManyKey',
      'om_players_list' => 'ManyKey',
      'om_teams_list'   => 'ManyKey',
    );
  }
}
