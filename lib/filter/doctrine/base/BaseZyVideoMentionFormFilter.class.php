<?php

/**
 * ZyVideoMention filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseZyVideoMentionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'object_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Object'), 'add_empty' => true)),
      'video_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Video'), 'add_empty' => true)),
      'last_reply_time' => new sfWidgetFormFilterInput(),
      'is_mention'      => new sfWidgetFormFilterInput(),
      'is_mention_show' => new sfWidgetFormFilterInput(),
      'status'          => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'object_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Object'), 'column' => 'id')),
      'video_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Video'), 'column' => 'id')),
      'last_reply_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_mention'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_mention_show' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('zy_video_mention_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZyVideoMention';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'object_id'       => 'ForeignKey',
      'video_id'        => 'ForeignKey',
      'last_reply_time' => 'Number',
      'is_mention'      => 'Number',
      'is_mention_show' => 'Number',
      'status'          => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
