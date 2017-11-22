<?php

/**
 * WwwMedalStandings filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseWwwMedalStandingsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'rank'   => new sfWidgetFormFilterInput(),
      'nation' => new sfWidgetFormFilterInput(),
      'golden' => new sfWidgetFormFilterInput(),
      'silver' => new sfWidgetFormFilterInput(),
      'bronze' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'rank'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nation' => new sfValidatorPass(array('required' => false)),
      'golden' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'silver' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'bronze' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('www_medal_standings_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'WwwMedalStandings';
  }

  public function getFields()
  {
    return array(
      'id'     => 'Number',
      'rank'   => 'Number',
      'nation' => 'Text',
      'golden' => 'Number',
      'silver' => 'Number',
      'bronze' => 'Number',
    );
  }
}
