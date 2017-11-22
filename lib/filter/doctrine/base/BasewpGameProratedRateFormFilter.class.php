<?php

/**
 * wpGameProratedRate filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasewpGameProratedRateFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'wpgame_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('wpgame'), 'add_empty' => true)),
      'is_enable'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'from_amount' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'to_amount'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'rate'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'wpgame_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('wpgame'), 'column' => 'id')),
      'is_enable'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'from_amount' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'to_amount'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rate'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('wp_game_prorated_rate_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'wpGameProratedRate';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'wpgame_id'   => 'ForeignKey',
      'is_enable'   => 'Boolean',
      'from_amount' => 'Number',
      'to_amount'   => 'Number',
      'rate'        => 'Number',
    );
  }
}
