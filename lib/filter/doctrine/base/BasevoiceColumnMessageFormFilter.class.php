<?php

/**
 * voiceColumnMessage filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceColumnMessageFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'old_id'           => new sfWidgetFormFilterInput(),
      'title'            => new sfWidgetFormFilterInput(),
      'intro'            => new sfWidgetFormFilterInput(),
      'full_text'        => new sfWidgetFormFilterInput(),
      'category'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'cl_author_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('voiceColumnAuthor'), 'add_empty' => true)),
      'column_tag_lists' => new sfWidgetFormFilterInput(),
      'hits'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'reply_count'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'light_count'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_delete'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'img_link'         => new sfWidgetFormFilterInput(),
      'img_path'         => new sfWidgetFormFilterInput(),
      'video_url'        => new sfWidgetFormFilterInput(),
      'video_img'        => new sfWidgetFormFilterInput(),
      'publish_date'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'view_content'     => new sfWidgetFormFilterInput(),
      'attributes'       => new sfWidgetFormFilterInput(),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'old_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'            => new sfValidatorPass(array('required' => false)),
      'intro'            => new sfValidatorPass(array('required' => false)),
      'full_text'        => new sfValidatorPass(array('required' => false)),
      'category'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'cl_author_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('voiceColumnAuthor'), 'column' => 'id')),
      'column_tag_lists' => new sfValidatorPass(array('required' => false)),
      'hits'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reply_count'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'light_count'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_delete'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'img_link'         => new sfValidatorPass(array('required' => false)),
      'img_path'         => new sfValidatorPass(array('required' => false)),
      'video_url'        => new sfValidatorPass(array('required' => false)),
      'video_img'        => new sfValidatorPass(array('required' => false)),
      'publish_date'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'view_content'     => new sfValidatorPass(array('required' => false)),
      'attributes'       => new sfValidatorPass(array('required' => false)),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('voice_column_message_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceColumnMessage';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'old_id'           => 'Number',
      'title'            => 'Text',
      'intro'            => 'Text',
      'full_text'        => 'Text',
      'category'         => 'Number',
      'cl_author_id'     => 'ForeignKey',
      'column_tag_lists' => 'Text',
      'hits'             => 'Number',
      'reply_count'      => 'Number',
      'light_count'      => 'Number',
      'is_delete'        => 'Boolean',
      'img_link'         => 'Text',
      'img_path'         => 'Text',
      'video_url'        => 'Text',
      'video_img'        => 'Text',
      'publish_date'     => 'Date',
      'view_content'     => 'Text',
      'attributes'       => 'Text',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
    );
  }
}
