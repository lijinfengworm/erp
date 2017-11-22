<?php

/**
 * twitterTopic filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasetwitterTopicFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'              => new sfWidgetFormFilterInput(),
      'description'        => new sfWidgetFormFilterInput(),
      'img'                => new sfWidgetFormFilterInput(),
      'large_img'          => new sfWidgetFormFilterInput(),
      'slug'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'               => new sfWidgetFormChoice(array('choices' => array('' => '', 'USER' => 'USER', 'MESSAGE' => 'MESSAGE'))),
      'topic_type'         => new sfWidgetFormFilterInput(),
      'category'           => new sfWidgetFormFilterInput(),
      'hits'               => new sfWidgetFormFilterInput(),
      'start_time'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'end_time'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'css_name'           => new sfWidgetFormFilterInput(),
      'publish_date'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'qq_notice_content'  => new sfWidgetFormFilterInput(),
      'qq_notice_time'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'qq_notice_advance'  => new sfWidgetFormFilterInput(),
      'summary'            => new sfWidgetFormFilterInput(),
      'summary_title'      => new sfWidgetFormFilterInput(),
      'reply_count'        => new sfWidgetFormFilterInput(),
      'light_count'        => new sfWidgetFormFilterInput(),
      'reply_switch'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'warning_switch'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'hide_header'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'team_name'          => new sfWidgetFormFilterInput(),
      'voice_pk_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('voicePk'), 'add_empty' => true)),
      'user_id'            => new sfWidgetFormFilterInput(),
      'twitter_message_id' => new sfWidgetFormFilterInput(),
      'user_name'          => new sfWidgetFormFilterInput(),
      'user_reply'         => new sfWidgetFormFilterInput(),
      'support'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'against'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'twitter_user_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'twitterUser')),
    ));

    $this->setValidators(array(
      'title'              => new sfValidatorPass(array('required' => false)),
      'description'        => new sfValidatorPass(array('required' => false)),
      'img'                => new sfValidatorPass(array('required' => false)),
      'large_img'          => new sfValidatorPass(array('required' => false)),
      'slug'               => new sfValidatorPass(array('required' => false)),
      'type'               => new sfValidatorChoice(array('required' => false, 'choices' => array('USER' => 'USER', 'MESSAGE' => 'MESSAGE'))),
      'topic_type'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'category'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hits'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'start_time'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'end_time'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'css_name'           => new sfValidatorPass(array('required' => false)),
      'publish_date'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'qq_notice_content'  => new sfValidatorPass(array('required' => false)),
      'qq_notice_time'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'qq_notice_advance'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'summary'            => new sfValidatorPass(array('required' => false)),
      'summary_title'      => new sfValidatorPass(array('required' => false)),
      'reply_count'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'light_count'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reply_switch'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'warning_switch'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'hide_header'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'team_name'          => new sfValidatorPass(array('required' => false)),
      'voice_pk_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('voicePk'), 'column' => 'id')),
      'user_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'twitter_message_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_name'          => new sfValidatorPass(array('required' => false)),
      'user_reply'         => new sfValidatorPass(array('required' => false)),
      'support'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'against'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'twitter_user_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'twitterUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('twitter_topic_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addTwitterUserListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.twitterTopicUser twitterTopicUser')
      ->andWhereIn('twitterTopicUser.twitter_user_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'twitterTopic';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'title'              => 'Text',
      'description'        => 'Text',
      'img'                => 'Text',
      'large_img'          => 'Text',
      'slug'               => 'Text',
      'type'               => 'Enum',
      'topic_type'         => 'Number',
      'category'           => 'Number',
      'hits'               => 'Number',
      'start_time'         => 'Date',
      'end_time'           => 'Date',
      'css_name'           => 'Text',
      'publish_date'       => 'Date',
      'qq_notice_content'  => 'Text',
      'qq_notice_time'     => 'Date',
      'qq_notice_advance'  => 'Number',
      'summary'            => 'Text',
      'summary_title'      => 'Text',
      'reply_count'        => 'Number',
      'light_count'        => 'Number',
      'reply_switch'       => 'Boolean',
      'warning_switch'     => 'Boolean',
      'hide_header'        => 'Boolean',
      'team_name'          => 'Text',
      'voice_pk_id'        => 'ForeignKey',
      'user_id'            => 'Number',
      'twitter_message_id' => 'Number',
      'user_name'          => 'Text',
      'user_reply'         => 'Text',
      'support'            => 'Number',
      'against'            => 'Number',
      'twitter_user_list'  => 'ManyKey',
    );
  }
}
