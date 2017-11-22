<?php

/**
 * llArticles filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasellArticlesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'small_title'                  => new sfWidgetFormFilterInput(),
      'title'                        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'zone_type'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'zone_index'                   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'zone_order'                   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'                         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'preview_content'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'content'                      => new sfWidgetFormFilterInput(),
      'is_published'                 => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'published_date'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'hasCover'                     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'cover_pic_url'                => new sfWidgetFormFilterInput(),
      'cover_smallpic_url'           => new sfWidgetFormFilterInput(),
      'hasRedirect'                  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'redirect_url'                 => new sfWidgetFormFilterInput(),
      'hasRecommendedGroup'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'reply_content'                => new sfWidgetFormFilterInput(),
      'video_type'                   => new sfWidgetFormFilterInput(),
      'original_video_id'            => new sfWidgetFormFilterInput(),
      'original_video_collection_id' => new sfWidgetFormFilterInput(),
      'original_album_id'            => new sfWidgetFormFilterInput(),
      'created_at'                   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'                   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'small_title'                  => new sfValidatorPass(array('required' => false)),
      'title'                        => new sfValidatorPass(array('required' => false)),
      'zone_type'                    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'zone_index'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'zone_order'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'                         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'preview_content'              => new sfValidatorPass(array('required' => false)),
      'content'                      => new sfValidatorPass(array('required' => false)),
      'is_published'                 => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'published_date'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'hasCover'                     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'cover_pic_url'                => new sfValidatorPass(array('required' => false)),
      'cover_smallpic_url'           => new sfValidatorPass(array('required' => false)),
      'hasRedirect'                  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'redirect_url'                 => new sfValidatorPass(array('required' => false)),
      'hasRecommendedGroup'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'reply_content'                => new sfValidatorPass(array('required' => false)),
      'video_type'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'original_video_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'original_video_collection_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'original_album_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'                   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'                   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ll_articles_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'llArticles';
  }

  public function getFields()
  {
    return array(
      'id'                           => 'Number',
      'small_title'                  => 'Text',
      'title'                        => 'Text',
      'zone_type'                    => 'Number',
      'zone_index'                   => 'Number',
      'zone_order'                   => 'Number',
      'type'                         => 'Number',
      'preview_content'              => 'Text',
      'content'                      => 'Text',
      'is_published'                 => 'Boolean',
      'published_date'               => 'Date',
      'hasCover'                     => 'Boolean',
      'cover_pic_url'                => 'Text',
      'cover_smallpic_url'           => 'Text',
      'hasRedirect'                  => 'Boolean',
      'redirect_url'                 => 'Text',
      'hasRecommendedGroup'          => 'Boolean',
      'reply_content'                => 'Text',
      'video_type'                   => 'Number',
      'original_video_id'            => 'Number',
      'original_video_collection_id' => 'Number',
      'original_album_id'            => 'Number',
      'created_at'                   => 'Date',
      'updated_at'                   => 'Date',
    );
  }
}
