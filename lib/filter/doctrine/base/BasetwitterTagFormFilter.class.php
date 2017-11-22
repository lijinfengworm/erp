<?php

/**
 * twitterTag filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasetwitterTagFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_changeable' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'root_id'       => new sfWidgetFormFilterInput(),
      'slug'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lft'           => new sfWidgetFormFilterInput(),
      'rgt'           => new sfWidgetFormFilterInput(),
      'level'         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'          => new sfValidatorPass(array('required' => false)),
      'is_changeable' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'root_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'slug'          => new sfValidatorPass(array('required' => false)),
      'lft'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('twitter_tag_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'twitterTag';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'name'          => 'Text',
      'is_changeable' => 'Boolean',
      'root_id'       => 'Number',
      'slug'          => 'Text',
      'lft'           => 'Number',
      'rgt'           => 'Number',
      'level'         => 'Number',
    );
  }
}
