<?php

/**
 * ZyObjectNotification filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseZyObjectNotificationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'object_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Object'), 'add_empty' => true)),
      'message_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Message'), 'add_empty' => true)),
      'reply_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Reply'), 'add_empty' => true)),
      'voice_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Voice'), 'add_empty' => true)),
      'voice_reply_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('VoiceReply'), 'add_empty' => true)),
      'video_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Video'), 'add_empty' => true)),
      'type'           => new sfWidgetFormFilterInput(),
      'status'         => new sfWidgetFormFilterInput(),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'object_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Object'), 'column' => 'id')),
      'message_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Message'), 'column' => 'id')),
      'reply_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Reply'), 'column' => 'id')),
      'voice_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Voice'), 'column' => 'id')),
      'voice_reply_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('VoiceReply'), 'column' => 'id')),
      'video_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Video'), 'column' => 'id')),
      'type'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('zy_object_notification_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZyObjectNotification';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'object_id'      => 'ForeignKey',
      'message_id'     => 'ForeignKey',
      'reply_id'       => 'ForeignKey',
      'voice_id'       => 'ForeignKey',
      'voice_reply_id' => 'ForeignKey',
      'video_id'       => 'ForeignKey',
      'type'           => 'Number',
      'status'         => 'Number',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
