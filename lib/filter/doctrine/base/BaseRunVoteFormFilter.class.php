<?php

/**
 * RunVote filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseRunVoteFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'original_vote_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'domain'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'nid'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_on_homepage'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'original_vote_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'domain'           => new sfValidatorPass(array('required' => false)),
      'nid'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_on_homepage'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('run_vote_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'RunVote';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'original_vote_id' => 'Number',
      'domain'           => 'Text',
      'nid'              => 'Number',
      'is_on_homepage'   => 'Boolean',
    );
  }
}
