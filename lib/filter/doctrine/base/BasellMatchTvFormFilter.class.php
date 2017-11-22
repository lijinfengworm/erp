<?php

/**
 * llMatchTv filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasellMatchTvFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'             => new sfWidgetFormFilterInput(),
      'home_team'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'guest_team'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'match_time'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'play_url'          => new sfWidgetFormFilterInput(),
      'is_publish'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'league_type'       => new sfWidgetFormFilterInput(),
      'original_match_id' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'title'             => new sfValidatorPass(array('required' => false)),
      'home_team'         => new sfValidatorPass(array('required' => false)),
      'guest_team'        => new sfValidatorPass(array('required' => false)),
      'match_time'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'play_url'          => new sfValidatorPass(array('required' => false)),
      'is_publish'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'league_type'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'original_match_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('ll_match_tv_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'llMatchTv';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'title'             => 'Text',
      'home_team'         => 'Text',
      'guest_team'        => 'Text',
      'match_time'        => 'Date',
      'play_url'          => 'Text',
      'is_publish'        => 'Boolean',
      'league_type'       => 'Number',
      'original_match_id' => 'Number',
    );
  }
}
