<?php

/**
 * llMatchSchema filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasellMatchSchemaFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'home_score'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'away_score'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'data_url'          => new sfWidgetFormFilterInput(),
      'original_match_id' => new sfWidgetFormFilterInput(),
      'league_type'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'home_score'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'away_score'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'data_url'          => new sfValidatorPass(array('required' => false)),
      'original_match_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'league_type'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('ll_match_schema_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'llMatchSchema';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'home_score'        => 'Number',
      'away_score'        => 'Number',
      'data_url'          => 'Text',
      'original_match_id' => 'Number',
      'league_type'       => 'Number',
    );
  }
}
