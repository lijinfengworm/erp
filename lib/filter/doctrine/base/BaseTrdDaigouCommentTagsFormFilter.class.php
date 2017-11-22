<?php

/**
 * TrdDaigouCommentTags filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdDaigouCommentTagsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'daigou_comment_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DaigouComment'), 'add_empty' => true)),
      'tag_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Tag'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'daigou_comment_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('DaigouComment'), 'column' => 'id')),
      'tag_id'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Tag'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('trd_daigou_comment_tags_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdDaigouCommentTags';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'daigou_comment_id' => 'ForeignKey',
      'tag_id'            => 'ForeignKey',
    );
  }
}
