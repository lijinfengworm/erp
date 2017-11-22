<?php

/**
 * HoopPlayer filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseHoopPlayerFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hupuid'         => new sfWidgetFormFilterInput(),
      'team_id'        => new sfWidgetFormFilterInput(),
      'beitai_pid'     => new sfWidgetFormFilterInput(),
      'name'           => new sfWidgetFormFilterInput(),
      'alias'          => new sfWidgetFormFilterInput(),
      'eng_name'       => new sfWidgetFormFilterInput(),
      'first_name'     => new sfWidgetFormFilterInput(),
      'last_name'      => new sfWidgetFormFilterInput(),
      'photo'          => new sfWidgetFormFilterInput(),
      'big_photo'      => new sfWidgetFormFilterInput(),
      'espn_id'        => new sfWidgetFormFilterInput(),
      'espn_name'      => new sfWidgetFormFilterInput(),
      'number'         => new sfWidgetFormFilterInput(),
      'position'       => new sfWidgetFormFilterInput(),
      'age'            => new sfWidgetFormFilterInput(),
      'birth_date'     => new sfWidgetFormFilterInput(),
      'first_year'     => new sfWidgetFormFilterInput(),
      'height'         => new sfWidgetFormFilterInput(),
      'weight'         => new sfWidgetFormFilterInput(),
      'wage'           => new sfWidgetFormFilterInput(),
      'salary'         => new sfWidgetFormFilterInput(),
      'country'        => new sfWidgetFormFilterInput(),
      'draft_year'     => new sfWidgetFormFilterInput(),
      'draft_round'    => new sfWidgetFormFilterInput(),
      'draft_pick'     => new sfWidgetFormFilterInput(),
      'high_school'    => new sfWidgetFormFilterInput(),
      'college_school' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'hupuid'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'team_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'beitai_pid'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'           => new sfValidatorPass(array('required' => false)),
      'alias'          => new sfValidatorPass(array('required' => false)),
      'eng_name'       => new sfValidatorPass(array('required' => false)),
      'first_name'     => new sfValidatorPass(array('required' => false)),
      'last_name'      => new sfValidatorPass(array('required' => false)),
      'photo'          => new sfValidatorPass(array('required' => false)),
      'big_photo'      => new sfValidatorPass(array('required' => false)),
      'espn_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'espn_name'      => new sfValidatorPass(array('required' => false)),
      'number'         => new sfValidatorPass(array('required' => false)),
      'position'       => new sfValidatorPass(array('required' => false)),
      'age'            => new sfValidatorPass(array('required' => false)),
      'birth_date'     => new sfValidatorPass(array('required' => false)),
      'first_year'     => new sfValidatorPass(array('required' => false)),
      'height'         => new sfValidatorPass(array('required' => false)),
      'weight'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'wage'           => new sfValidatorPass(array('required' => false)),
      'salary'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'country'        => new sfValidatorPass(array('required' => false)),
      'draft_year'     => new sfValidatorPass(array('required' => false)),
      'draft_round'    => new sfValidatorPass(array('required' => false)),
      'draft_pick'     => new sfValidatorPass(array('required' => false)),
      'high_school'    => new sfValidatorPass(array('required' => false)),
      'college_school' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('hoop_player_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'HoopPlayer';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'hupuid'         => 'Number',
      'team_id'        => 'Number',
      'beitai_pid'     => 'Number',
      'name'           => 'Text',
      'alias'          => 'Text',
      'eng_name'       => 'Text',
      'first_name'     => 'Text',
      'last_name'      => 'Text',
      'photo'          => 'Text',
      'big_photo'      => 'Text',
      'espn_id'        => 'Number',
      'espn_name'      => 'Text',
      'number'         => 'Text',
      'position'       => 'Text',
      'age'            => 'Text',
      'birth_date'     => 'Text',
      'first_year'     => 'Text',
      'height'         => 'Text',
      'weight'         => 'Number',
      'wage'           => 'Text',
      'salary'         => 'Number',
      'country'        => 'Text',
      'draft_year'     => 'Text',
      'draft_round'    => 'Text',
      'draft_pick'     => 'Text',
      'high_school'    => 'Text',
      'college_school' => 'Text',
    );
  }
}
