<?php

/**
 * voiceHotEvent filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceHotEventFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'voice_tag_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('voiceTag'), 'add_empty' => true)),
      'twitter_topic_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('twitterTopic'), 'add_empty' => true)),
      'topic_core_message_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('twitterTopicCoreMessage'), 'add_empty' => true)),
      'parent_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('parent'), 'add_empty' => true)),
      'category'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'show_order'            => new sfWidgetFormFilterInput(),
      'homepage_order'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_column'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'twitter_message_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('twitterMessage'), 'add_empty' => true)),
      'user_id'               => new sfWidgetFormFilterInput(),
      'user_name'             => new sfWidgetFormFilterInput(),
      'user_reply'            => new sfWidgetFormFilterInput(),
      'description'           => new sfWidgetFormFilterInput(),
      'img_path'              => new sfWidgetFormFilterInput(),
      'img_tail'              => new sfWidgetFormFilterInput(),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'name'                  => new sfValidatorPass(array('required' => false)),
      'voice_tag_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('voiceTag'), 'column' => 'id')),
      'twitter_topic_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('twitterTopic'), 'column' => 'id')),
      'topic_core_message_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('twitterTopicCoreMessage'), 'column' => 'id')),
      'parent_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('parent'), 'column' => 'id')),
      'category'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'show_order'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'homepage_order'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_column'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'twitter_message_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('twitterMessage'), 'column' => 'id')),
      'user_id'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_name'             => new sfValidatorPass(array('required' => false)),
      'user_reply'            => new sfValidatorPass(array('required' => false)),
      'description'           => new sfValidatorPass(array('required' => false)),
      'img_path'              => new sfValidatorPass(array('required' => false)),
      'img_tail'              => new sfValidatorPass(array('required' => false)),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('voice_hot_event_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceHotEvent';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'name'                  => 'Text',
      'voice_tag_id'          => 'ForeignKey',
      'twitter_topic_id'      => 'ForeignKey',
      'topic_core_message_id' => 'ForeignKey',
      'parent_id'             => 'ForeignKey',
      'category'              => 'Number',
      'show_order'            => 'Number',
      'homepage_order'        => 'Number',
      'type'                  => 'Number',
      'is_column'             => 'Number',
      'twitter_message_id'    => 'ForeignKey',
      'user_id'               => 'Number',
      'user_name'             => 'Text',
      'user_reply'            => 'Text',
      'description'           => 'Text',
      'img_path'              => 'Text',
      'img_tail'              => 'Text',
      'created_at'            => 'Date',
      'updated_at'            => 'Date',
    );
  }
}
