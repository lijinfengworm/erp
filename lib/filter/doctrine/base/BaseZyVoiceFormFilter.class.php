<?php

/**
 * ZyVoice filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseZyVoiceFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'url'                   => new sfWidgetFormFilterInput(),
      'voice_id'              => new sfWidgetFormFilterInput(),
      'title'                 => new sfWidgetFormFilterInput(),
      'content'               => new sfWidgetFormFilterInput(),
      'detail_content'        => new sfWidgetFormFilterInput(),
      'publish_date'          => new sfWidgetFormFilterInput(),
      'source'                => new sfWidgetFormFilterInput(),
      'source_url'            => new sfWidgetFormFilterInput(),
      'publish_user'          => new sfWidgetFormFilterInput(),
      'publish_user_id'       => new sfWidgetFormFilterInput(),
      'publish_user_url'      => new sfWidgetFormFilterInput(),
      'image_thumb'           => new sfWidgetFormFilterInput(),
      'image_bmiddle'         => new sfWidgetFormFilterInput(),
      'image_large'           => new sfWidgetFormFilterInput(),
      'video'                 => new sfWidgetFormFilterInput(),
      'video_cover_img'       => new sfWidgetFormFilterInput(),
      'publisher_name'        => new sfWidgetFormFilterInput(),
      'publisher_avatar_url'  => new sfWidgetFormFilterInput(),
      'publisher_url'         => new sfWidgetFormFilterInput(),
      'publisher_description' => new sfWidgetFormFilterInput(),
      'message_type'          => new sfWidgetFormFilterInput(),
      'type'                  => new sfWidgetFormFilterInput(),
      'visit_count'           => new sfWidgetFormFilterInput(),
      'reply_count'           => new sfWidgetFormFilterInput(),
      'light_count'           => new sfWidgetFormFilterInput(),
      'good_count'            => new sfWidgetFormFilterInput(),
      'rank'                  => new sfWidgetFormFilterInput(),
      'last_reply_time'       => new sfWidgetFormFilterInput(),
      'status'                => new sfWidgetFormFilterInput(),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'url'                   => new sfValidatorPass(array('required' => false)),
      'voice_id'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'                 => new sfValidatorPass(array('required' => false)),
      'content'               => new sfValidatorPass(array('required' => false)),
      'detail_content'        => new sfValidatorPass(array('required' => false)),
      'publish_date'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'source'                => new sfValidatorPass(array('required' => false)),
      'source_url'            => new sfValidatorPass(array('required' => false)),
      'publish_user'          => new sfValidatorPass(array('required' => false)),
      'publish_user_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'publish_user_url'      => new sfValidatorPass(array('required' => false)),
      'image_thumb'           => new sfValidatorPass(array('required' => false)),
      'image_bmiddle'         => new sfValidatorPass(array('required' => false)),
      'image_large'           => new sfValidatorPass(array('required' => false)),
      'video'                 => new sfValidatorPass(array('required' => false)),
      'video_cover_img'       => new sfValidatorPass(array('required' => false)),
      'publisher_name'        => new sfValidatorPass(array('required' => false)),
      'publisher_avatar_url'  => new sfValidatorPass(array('required' => false)),
      'publisher_url'         => new sfValidatorPass(array('required' => false)),
      'publisher_description' => new sfValidatorPass(array('required' => false)),
      'message_type'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'visit_count'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reply_count'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'light_count'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'good_count'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rank'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'last_reply_time'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('zy_voice_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZyVoice';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'url'                   => 'Text',
      'voice_id'              => 'Number',
      'title'                 => 'Text',
      'content'               => 'Text',
      'detail_content'        => 'Text',
      'publish_date'          => 'Number',
      'source'                => 'Text',
      'source_url'            => 'Text',
      'publish_user'          => 'Text',
      'publish_user_id'       => 'Number',
      'publish_user_url'      => 'Text',
      'image_thumb'           => 'Text',
      'image_bmiddle'         => 'Text',
      'image_large'           => 'Text',
      'video'                 => 'Text',
      'video_cover_img'       => 'Text',
      'publisher_name'        => 'Text',
      'publisher_avatar_url'  => 'Text',
      'publisher_url'         => 'Text',
      'publisher_description' => 'Text',
      'message_type'          => 'Number',
      'type'                  => 'Number',
      'visit_count'           => 'Number',
      'reply_count'           => 'Number',
      'light_count'           => 'Number',
      'good_count'            => 'Number',
      'rank'                  => 'Number',
      'last_reply_time'       => 'Number',
      'status'                => 'Number',
      'created_at'            => 'Date',
      'updated_at'            => 'Date',
    );
  }
}
