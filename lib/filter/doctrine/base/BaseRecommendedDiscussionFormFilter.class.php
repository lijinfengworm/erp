<?php

/**
 * RecommendedDiscussion filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseRecommendedDiscussionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'recommended_group_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('recommendedGroup'), 'add_empty' => true)),
      'discussion_id'        => new sfWidgetFormFilterInput(),
      'name'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'order_num'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'title'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'url'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'discuss_type'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'hasReply'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'reply_content'        => new sfWidgetFormFilterInput(),
      'reply_url'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'recommended_group_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('recommendedGroup'), 'column' => 'id')),
      'discussion_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'                 => new sfValidatorPass(array('required' => false)),
      'order_num'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'                => new sfValidatorPass(array('required' => false)),
      'url'                  => new sfValidatorPass(array('required' => false)),
      'discuss_type'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hasReply'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'reply_content'        => new sfValidatorPass(array('required' => false)),
      'reply_url'            => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('recommended_discussion_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'RecommendedDiscussion';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'recommended_group_id' => 'ForeignKey',
      'discussion_id'        => 'Number',
      'name'                 => 'Text',
      'order_num'            => 'Number',
      'title'                => 'Text',
      'url'                  => 'Text',
      'discuss_type'         => 'Number',
      'hasReply'             => 'Boolean',
      'reply_content'        => 'Text',
      'reply_url'            => 'Text',
    );
  }
}
