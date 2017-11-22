<?php

/**
 * voiceUserNotice filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceUserNoticeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'voice_tag_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('voiceTag'), 'add_empty' => true)),
      'twitter_topic_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('twitterTopic'), 'add_empty' => true)),
      'number'           => new sfWidgetFormFilterInput(),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'user_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'voice_tag_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('voiceTag'), 'column' => 'id')),
      'twitter_topic_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('twitterTopic'), 'column' => 'id')),
      'number'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('voice_user_notice_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceUserNotice';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'user_id'          => 'Number',
      'voice_tag_id'     => 'ForeignKey',
      'twitter_topic_id' => 'ForeignKey',
      'number'           => 'Number',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
    );
  }
}
