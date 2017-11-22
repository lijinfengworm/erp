<?php

/**
 * omMatch filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseomMatchFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'                => new sfWidgetFormFilterInput(),
      'team_match_status'    => new sfWidgetFormChoice(array('choices' => array('' => '', '3V3' => '3V3', '5V5' => '5V5'))),
      'player_match_status'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'age_group'            => new sfWidgetFormChoice(array('choices' => array('' => '', 'U19' => 'U19', 'U23' => 'U23', 'ALL' => 'ALL'))),
      'sex'                  => new sfWidgetFormChoice(array('choices' => array('' => '', 'MALE' => 'MALE', 'FEMALE' => 'FEMALE', 'ALL' => 'ALL'))),
      'apply_end_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'match_start_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'match_content'        => new sfWidgetFormFilterInput(),
      'apply_limitation'     => new sfWidgetFormFilterInput(),
      'fee'                  => new sfWidgetFormFilterInput(),
      'is_finish'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'logo_url'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'reward1_title'        => new sfWidgetFormFilterInput(),
      'reward1_pic_url'      => new sfWidgetFormFilterInput(),
      'reward1_redirect_url' => new sfWidgetFormFilterInput(),
      'reward2_title'        => new sfWidgetFormFilterInput(),
      'reward2_pic_url'      => new sfWidgetFormFilterInput(),
      'reward2_redirect_url' => new sfWidgetFormFilterInput(),
      'reward3_title'        => new sfWidgetFormFilterInput(),
      'reward3_pic_url'      => new sfWidgetFormFilterInput(),
      'reward3_redirect_url' => new sfWidgetFormFilterInput(),
      'slogan'               => new sfWidgetFormFilterInput(),
      'description'          => new sfWidgetFormFilterInput(),
      'question_and_answer'  => new sfWidgetFormFilterInput(),
      'show_on_homepage'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'om_players_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'omPlayer')),
      'locations_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Location')),
    ));

    $this->setValidators(array(
      'title'                => new sfValidatorPass(array('required' => false)),
      'team_match_status'    => new sfValidatorChoice(array('required' => false, 'choices' => array('3V3' => '3V3', '5V5' => '5V5'))),
      'player_match_status'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'age_group'            => new sfValidatorChoice(array('required' => false, 'choices' => array('U19' => 'U19', 'U23' => 'U23', 'ALL' => 'ALL'))),
      'sex'                  => new sfValidatorChoice(array('required' => false, 'choices' => array('MALE' => 'MALE', 'FEMALE' => 'FEMALE', 'ALL' => 'ALL'))),
      'apply_end_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'match_start_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'match_content'        => new sfValidatorPass(array('required' => false)),
      'apply_limitation'     => new sfValidatorPass(array('required' => false)),
      'fee'                  => new sfValidatorPass(array('required' => false)),
      'is_finish'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'logo_url'             => new sfValidatorPass(array('required' => false)),
      'reward1_title'        => new sfValidatorPass(array('required' => false)),
      'reward1_pic_url'      => new sfValidatorPass(array('required' => false)),
      'reward1_redirect_url' => new sfValidatorPass(array('required' => false)),
      'reward2_title'        => new sfValidatorPass(array('required' => false)),
      'reward2_pic_url'      => new sfValidatorPass(array('required' => false)),
      'reward2_redirect_url' => new sfValidatorPass(array('required' => false)),
      'reward3_title'        => new sfValidatorPass(array('required' => false)),
      'reward3_pic_url'      => new sfValidatorPass(array('required' => false)),
      'reward3_redirect_url' => new sfValidatorPass(array('required' => false)),
      'slogan'               => new sfValidatorPass(array('required' => false)),
      'description'          => new sfValidatorPass(array('required' => false)),
      'question_and_answer'  => new sfValidatorPass(array('required' => false)),
      'show_on_homepage'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'om_players_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'omPlayer', 'required' => false)),
      'locations_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Location', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('om_match_filters[%s]');

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
      ->leftJoin($query->getRootAlias().'.omMatchPlayer omMatchPlayer')
      ->andWhereIn('omMatchPlayer.om_player_id', $values)
    ;
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
      ->leftJoin($query->getRootAlias().'.omMatchCity omMatchCity')
      ->andWhereIn('omMatchCity.location_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'omMatch';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'title'                => 'Text',
      'team_match_status'    => 'Enum',
      'player_match_status'  => 'Number',
      'age_group'            => 'Enum',
      'sex'                  => 'Enum',
      'apply_end_at'         => 'Date',
      'match_start_at'       => 'Date',
      'match_content'        => 'Text',
      'apply_limitation'     => 'Text',
      'fee'                  => 'Text',
      'is_finish'            => 'Boolean',
      'logo_url'             => 'Text',
      'reward1_title'        => 'Text',
      'reward1_pic_url'      => 'Text',
      'reward1_redirect_url' => 'Text',
      'reward2_title'        => 'Text',
      'reward2_pic_url'      => 'Text',
      'reward2_redirect_url' => 'Text',
      'reward3_title'        => 'Text',
      'reward3_pic_url'      => 'Text',
      'reward3_redirect_url' => 'Text',
      'slogan'               => 'Text',
      'description'          => 'Text',
      'question_and_answer'  => 'Text',
      'show_on_homepage'     => 'Boolean',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
      'om_players_list'      => 'ManyKey',
      'locations_list'       => 'ManyKey',
    );
  }
}
