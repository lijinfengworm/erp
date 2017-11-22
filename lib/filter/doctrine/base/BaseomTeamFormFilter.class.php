<?php

/**
 * omTeam filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseomTeamFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'              => new sfWidgetFormFilterInput(),
      'leader_id'            => new sfWidgetFormFilterInput(),
      'name'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'mobile'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'leader_qq_number'     => new sfWidgetFormFilterInput(),
      'leader_id_number'     => new sfWidgetFormFilterInput(),
      'leader_email'         => new sfWidgetFormFilterInput(),
      'status'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'avg_high'             => new sfWidgetFormFilterInput(),
      'avg_weight'           => new sfWidgetFormFilterInput(),
      'avg_age'              => new sfWidgetFormFilterInput(),
      'leader_name'          => new sfWidgetFormFilterInput(),
      'members_count'        => new sfWidgetFormFilterInput(),
      'invite_code'          => new sfWidgetFormFilterInput(),
      'invite_code_status'   => new sfWidgetFormFilterInput(),
      'created_at'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'logo_status'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'logo_url'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'hasBall'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'hasReferee'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'uniform_color'        => new sfWidgetFormFilterInput(),
      'brake_rate'           => new sfWidgetFormFilterInput(),
      'match_logs'           => new sfWidgetFormFilterInput(),
      'is_need_admin_help'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_apply_appointment' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'operator'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_register_finish'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'om_players_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'omPlayer')),
      'om_notices_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'omNotice')),
    ));

    $this->setValidators(array(
      'user_id'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'leader_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'                 => new sfValidatorPass(array('required' => false)),
      'mobile'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'leader_qq_number'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'leader_id_number'     => new sfValidatorPass(array('required' => false)),
      'leader_email'         => new sfValidatorPass(array('required' => false)),
      'status'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'avg_high'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'avg_weight'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'avg_age'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'leader_name'          => new sfValidatorPass(array('required' => false)),
      'members_count'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'invite_code'          => new sfValidatorPass(array('required' => false)),
      'invite_code_status'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'logo_status'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'logo_url'             => new sfValidatorPass(array('required' => false)),
      'hasBall'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'hasReferee'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'uniform_color'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'brake_rate'           => new sfValidatorPass(array('required' => false)),
      'match_logs'           => new sfValidatorPass(array('required' => false)),
      'is_need_admin_help'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_apply_appointment' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'operator'             => new sfValidatorPass(array('required' => false)),
      'is_register_finish'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'om_players_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'omPlayer', 'required' => false)),
      'om_notices_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'omNotice', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('om_team_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
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
      ->leftJoin($query->getRootAlias().'.omMembership omMembership')
      ->andWhereIn('omMembership.om_player_id', $values)
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
      ->leftJoin($query->getRootAlias().'.omNoticeTeam omNoticeTeam')
      ->andWhereIn('omNoticeTeam.om_notice_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'omTeam';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'user_id'              => 'Number',
      'leader_id'            => 'Number',
      'name'                 => 'Text',
      'mobile'               => 'Number',
      'leader_qq_number'     => 'Number',
      'leader_id_number'     => 'Text',
      'leader_email'         => 'Text',
      'status'               => 'Number',
      'avg_high'             => 'Number',
      'avg_weight'           => 'Number',
      'avg_age'              => 'Number',
      'leader_name'          => 'Text',
      'members_count'        => 'Number',
      'invite_code'          => 'Text',
      'invite_code_status'   => 'Number',
      'created_at'           => 'Number',
      'logo_status'          => 'Number',
      'logo_url'             => 'Text',
      'hasBall'              => 'Boolean',
      'hasReferee'           => 'Boolean',
      'uniform_color'        => 'Number',
      'brake_rate'           => 'Text',
      'match_logs'           => 'Text',
      'is_need_admin_help'   => 'Boolean',
      'is_apply_appointment' => 'Boolean',
      'operator'             => 'Text',
      'is_register_finish'   => 'Boolean',
      'om_players_list'      => 'ManyKey',
      'om_notices_list'      => 'ManyKey',
    );
  }
}
