<?php

/**
 * twitterTopicGroup filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasetwitterTopicGroupFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'twitter_topic_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('twitterTopic'), 'add_empty' => true)),
      'name'                 => new sfWidgetFormFilterInput(),
      'order_num'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'show_timeline'        => new sfWidgetFormFilterInput(),
      'type'                 => new sfWidgetFormFilterInput(),
      'rank_title'           => new sfWidgetFormFilterInput(),
      'focus_link'           => new sfWidgetFormFilterInput(),
      'voice_tag_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('voiceTag'), 'add_empty' => true)),
      'tag_order_num'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'twitter_message_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'twitterMessage')),
    ));

    $this->setValidators(array(
      'twitter_topic_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('twitterTopic'), 'column' => 'id')),
      'name'                 => new sfValidatorPass(array('required' => false)),
      'order_num'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'show_timeline'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rank_title'           => new sfValidatorPass(array('required' => false)),
      'focus_link'           => new sfValidatorPass(array('required' => false)),
      'voice_tag_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('voiceTag'), 'column' => 'id')),
      'tag_order_num'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'twitter_message_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'twitterMessage', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('twitter_topic_group_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addTwitterMessageListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.twitterTopicGroupMessage twitterTopicGroupMessage')
      ->andWhereIn('twitterTopicGroupMessage.twitter_message_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'twitterTopicGroup';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'twitter_topic_id'     => 'ForeignKey',
      'name'                 => 'Text',
      'order_num'            => 'Number',
      'show_timeline'        => 'Number',
      'type'                 => 'Number',
      'rank_title'           => 'Text',
      'focus_link'           => 'Text',
      'voice_tag_id'         => 'ForeignKey',
      'tag_order_num'        => 'Number',
      'twitter_message_list' => 'ManyKey',
    );
  }
}
