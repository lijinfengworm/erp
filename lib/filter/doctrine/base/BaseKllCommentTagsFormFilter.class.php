<?php

/**
 * KllCommentTags filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllCommentTagsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'root_name'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'child_name' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'attrs'      => new sfWidgetFormFilterInput(),
      'status'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'root_name'  => new sfValidatorPass(array('required' => false)),
      'child_name' => new sfValidatorPass(array('required' => false)),
      'attrs'      => new sfValidatorPass(array('required' => false)),
      'status'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_comment_tags_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllCommentTags';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'root_name'  => 'Text',
      'child_name' => 'Text',
      'attrs'      => 'Text',
      'status'     => 'Number',
    );
  }
}
