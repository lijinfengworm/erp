<?php

/**
 * KllCommentTagsCount filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllCommentTagsCountFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'product_id' => new sfWidgetFormFilterInput(),
      'tag_id'     => new sfWidgetFormFilterInput(),
      'comment_id' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'product_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tag_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comment_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_comment_tags_count_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllCommentTagsCount';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'product_id' => 'Number',
      'tag_id'     => 'Number',
      'comment_id' => 'Number',
    );
  }
}
