<?php

/**
 * twitterReply filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasetwitterReplyFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'twitter_message_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('twitterMessage'), 'add_empty' => true)),
      'twitter_topic_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('twitterTopic'), 'add_empty' => true)),
      'user_id'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_name'             => new sfWidgetFormFilterInput(),
      'content'               => new sfWidgetFormFilterInput(),
      'device'                => new sfWidgetFormFilterInput(),
      'is_translate'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_delete'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'translate_agree_count' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'light_count'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lighted_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'is_push'               => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'oppose_count'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'reply_from'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'from_id'               => new sfWidgetFormFilterInput(),
      'attributes'            => new sfWidgetFormFilterInput(),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'twitter_message_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('twitterMessage'), 'column' => 'id')),
      'twitter_topic_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('twitterTopic'), 'column' => 'id')),
      'user_id'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_name'             => new sfValidatorPass(array('required' => false)),
      'content'               => new sfValidatorPass(array('required' => false)),
      'device'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_translate'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_delete'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'translate_agree_count' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'light_count'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lighted_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'is_push'               => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'oppose_count'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reply_from'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'from_id'               => new sfValidatorPass(array('required' => false)),
      'attributes'            => new sfValidatorPass(array('required' => false)),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('twitter_reply_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'twitterReply';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'twitter_message_id'    => 'ForeignKey',
      'twitter_topic_id'      => 'ForeignKey',
      'user_id'               => 'Number',
      'user_name'             => 'Text',
      'content'               => 'Text',
      'device'                => 'Number',
      'is_translate'          => 'Boolean',
      'is_delete'             => 'Boolean',
      'translate_agree_count' => 'Number',
      'light_count'           => 'Number',
      'lighted_at'            => 'Date',
      'is_push'               => 'Boolean',
      'oppose_count'          => 'Number',
      'reply_from'            => 'Number',
      'from_id'               => 'Text',
      'attributes'            => 'Text',
      'created_at'            => 'Date',
      'updated_at'            => 'Date',
    );
  }
}
