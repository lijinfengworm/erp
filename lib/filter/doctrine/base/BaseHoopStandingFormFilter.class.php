<?php

/**
 * HoopStanding filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseHoopStandingFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'team_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('hoopTeam'), 'add_empty' => true)),
      'season'      => new sfWidgetFormFilterInput(),
      'season_type' => new sfWidgetFormFilterInput(),
      'flag'        => new sfWidgetFormFilterInput(),
      'rank'        => new sfWidgetFormFilterInput(),
      'won'         => new sfWidgetFormFilterInput(),
      'lost'        => new sfWidgetFormFilterInput(),
      'win_rate'    => new sfWidgetFormFilterInput(),
      'gb'          => new sfWidgetFormFilterInput(),
      'home'        => new sfWidgetFormFilterInput(),
      'road'        => new sfWidgetFormFilterInput(),
      'div'         => new sfWidgetFormFilterInput(),
      'conf'        => new sfWidgetFormFilterInput(),
      'pf'          => new sfWidgetFormFilterInput(),
      'pa'          => new sfWidgetFormFilterInput(),
      'diff'        => new sfWidgetFormFilterInput(),
      'strk'        => new sfWidgetFormFilterInput(),
      'last_ten'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'team_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('hoopTeam'), 'column' => 'id')),
      'season'      => new sfValidatorPass(array('required' => false)),
      'season_type' => new sfValidatorPass(array('required' => false)),
      'flag'        => new sfValidatorPass(array('required' => false)),
      'rank'        => new sfValidatorPass(array('required' => false)),
      'won'         => new sfValidatorPass(array('required' => false)),
      'lost'        => new sfValidatorPass(array('required' => false)),
      'win_rate'    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'gb'          => new sfValidatorPass(array('required' => false)),
      'home'        => new sfValidatorPass(array('required' => false)),
      'road'        => new sfValidatorPass(array('required' => false)),
      'div'         => new sfValidatorPass(array('required' => false)),
      'conf'        => new sfValidatorPass(array('required' => false)),
      'pf'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'pa'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'diff'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'strk'        => new sfValidatorPass(array('required' => false)),
      'last_ten'    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('hoop_standing_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'HoopStanding';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'team_id'     => 'ForeignKey',
      'season'      => 'Text',
      'season_type' => 'Text',
      'flag'        => 'Text',
      'rank'        => 'Text',
      'won'         => 'Text',
      'lost'        => 'Text',
      'win_rate'    => 'Number',
      'gb'          => 'Text',
      'home'        => 'Text',
      'road'        => 'Text',
      'div'         => 'Text',
      'conf'        => 'Text',
      'pf'          => 'Number',
      'pa'          => 'Number',
      'diff'        => 'Number',
      'strk'        => 'Text',
      'last_ten'    => 'Text',
    );
  }
}
