<?php

/**
 * omMatchGroup filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseomMatchGroupFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'om_match_round_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMatchRound'), 'add_empty' => true)),
      'name'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'om_match_round_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMatchRound'), 'column' => 'id')),
      'name'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('om_match_group_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'omMatchGroup';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'om_match_round_id' => 'ForeignKey',
      'name'              => 'Number',
    );
  }
}
