<?php

/**
 * omMatchDetail filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseomMatchDetailFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'om_match_group_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMatchGroup'), 'add_empty' => true)),
      'om_match_home_team_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMatchHomeTeam'), 'add_empty' => true)),
      'om_match_guest_team_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMatchGuestTeam'), 'add_empty' => true)),
      'home_score'             => new sfWidgetFormFilterInput(),
      'guest_score'            => new sfWidgetFormFilterInput(),
      'home_point'             => new sfWidgetFormFilterInput(),
      'guest_point'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'om_match_group_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMatchGroup'), 'column' => 'id')),
      'om_match_home_team_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMatchHomeTeam'), 'column' => 'id')),
      'om_match_guest_team_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMatchGuestTeam'), 'column' => 'id')),
      'home_score'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'guest_score'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'home_point'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'guest_point'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('om_match_detail_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'omMatchDetail';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'om_match_group_id'      => 'ForeignKey',
      'om_match_home_team_id'  => 'ForeignKey',
      'om_match_guest_team_id' => 'ForeignKey',
      'home_score'             => 'Number',
      'guest_score'            => 'Number',
      'home_point'             => 'Number',
      'guest_point'            => 'Number',
    );
  }
}
