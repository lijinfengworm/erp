<?php

/**
 * voiceFeedMessage filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceFeedMessageFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'twitter_user_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('twitterUser'), 'add_empty' => true)),
      'twitter_account_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('twitterAccount'), 'add_empty' => true)),
      'voice_media_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('voiceMedia'), 'add_empty' => true)),
      'text'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'detail_title'         => new sfWidgetFormFilterInput(),
      'detail_text'          => new sfWidgetFormFilterInput(),
      'orginal_url'          => new sfWidgetFormFilterInput(),
      'orginal_type'         => new sfWidgetFormFilterInput(),
      'publish_date'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'is_delete'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'hits'                 => new sfWidgetFormFilterInput(),
      'reply_count'          => new sfWidgetFormFilterInput(),
      'translate_count'      => new sfWidgetFormFilterInput(),
      'light_count'          => new sfWidgetFormFilterInput(),
      'is_locked'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_recommend'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'last_reply_date'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'recommend_date'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'img_link'             => new sfWidgetFormFilterInput(),
      'img_path'             => new sfWidgetFormFilterInput(),
      'video'                => new sfWidgetFormFilterInput(),
      'video_cover_img'      => new sfWidgetFormFilterInput(),
      'type'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'category'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'publish_user_id'      => new sfWidgetFormFilterInput(),
      'publish_user'         => new sfWidgetFormFilterInput(),
      'publish_user_url'     => new sfWidgetFormFilterInput(),
      'publish_category'     => new sfWidgetFormFilterInput(),
      'publish_category_url' => new sfWidgetFormFilterInput(),
      'voice_url'            => new sfWidgetFormFilterInput(),
      'vote_id'              => new sfWidgetFormFilterInput(),
      'twitter_topic_id'     => new sfWidgetFormFilterInput(),
      'author_id'            => new sfWidgetFormFilterInput(),
      'editor_id'            => new sfWidgetFormFilterInput(),
      'show_intro'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'twitter_user_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('twitterUser'), 'column' => 'id')),
      'twitter_account_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('twitterAccount'), 'column' => 'id')),
      'voice_media_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('voiceMedia'), 'column' => 'id')),
      'text'                 => new sfValidatorPass(array('required' => false)),
      'detail_title'         => new sfValidatorPass(array('required' => false)),
      'detail_text'          => new sfValidatorPass(array('required' => false)),
      'orginal_url'          => new sfValidatorPass(array('required' => false)),
      'orginal_type'         => new sfValidatorPass(array('required' => false)),
      'publish_date'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'is_delete'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'hits'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reply_count'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'translate_count'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'light_count'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_locked'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_recommend'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'last_reply_date'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'recommend_date'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'img_link'             => new sfValidatorPass(array('required' => false)),
      'img_path'             => new sfValidatorPass(array('required' => false)),
      'video'                => new sfValidatorPass(array('required' => false)),
      'video_cover_img'      => new sfValidatorPass(array('required' => false)),
      'type'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'category'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'publish_user_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'publish_user'         => new sfValidatorPass(array('required' => false)),
      'publish_user_url'     => new sfValidatorPass(array('required' => false)),
      'publish_category'     => new sfValidatorPass(array('required' => false)),
      'publish_category_url' => new sfValidatorPass(array('required' => false)),
      'voice_url'            => new sfValidatorPass(array('required' => false)),
      'vote_id'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'twitter_topic_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'author_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'editor_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'show_intro'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('voice_feed_message_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceFeedMessage';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'twitter_user_id'      => 'ForeignKey',
      'twitter_account_id'   => 'ForeignKey',
      'voice_media_id'       => 'ForeignKey',
      'text'                 => 'Text',
      'detail_title'         => 'Text',
      'detail_text'          => 'Text',
      'orginal_url'          => 'Text',
      'orginal_type'         => 'Text',
      'publish_date'         => 'Date',
      'is_delete'            => 'Boolean',
      'hits'                 => 'Number',
      'reply_count'          => 'Number',
      'translate_count'      => 'Number',
      'light_count'          => 'Number',
      'is_locked'            => 'Boolean',
      'is_recommend'         => 'Boolean',
      'last_reply_date'      => 'Date',
      'recommend_date'       => 'Date',
      'img_link'             => 'Text',
      'img_path'             => 'Text',
      'video'                => 'Text',
      'video_cover_img'      => 'Text',
      'type'                 => 'Number',
      'category'             => 'Number',
      'publish_user_id'      => 'Number',
      'publish_user'         => 'Text',
      'publish_user_url'     => 'Text',
      'publish_category'     => 'Text',
      'publish_category_url' => 'Text',
      'voice_url'            => 'Text',
      'vote_id'              => 'Number',
      'twitter_topic_id'     => 'Number',
      'author_id'            => 'Number',
      'editor_id'            => 'Number',
      'show_intro'           => 'Boolean',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
    );
  }
}
