<?php

/**
 * HoopMatchArticle filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseHoopMatchArticleFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'tid'                   => new sfWidgetFormFilterInput(),
      'match_id'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('hoopMatch'), 'add_empty' => true)),
      'season'                => new sfWidgetFormFilterInput(),
      'location'              => new sfWidgetFormFilterInput(),
      'live_name'             => new sfWidgetFormFilterInput(),
      'live_link'             => new sfWidgetFormFilterInput(),
      'broadcast'             => new sfWidgetFormFilterInput(),
      'external_txtlive_link' => new sfWidgetFormFilterInput(),
      'hotline_link'          => new sfWidgetFormFilterInput(),
      'title'                 => new sfWidgetFormFilterInput(),
      'content'               => new sfWidgetFormFilterInput(),
      'img'                   => new sfWidgetFormFilterInput(),
      'forum_link'            => new sfWidgetFormFilterInput(),
      'video_link'            => new sfWidgetFormFilterInput(),
      'gallery_link'          => new sfWidgetFormFilterInput(),
      'external_match_link'   => new sfWidgetFormFilterInput(),
      'author'                => new sfWidgetFormFilterInput(),
      'author_id'             => new sfWidgetFormFilterInput(),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'tid'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'match_id'              => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('hoopMatch'), 'column' => 'id')),
      'season'                => new sfValidatorPass(array('required' => false)),
      'location'              => new sfValidatorPass(array('required' => false)),
      'live_name'             => new sfValidatorPass(array('required' => false)),
      'live_link'             => new sfValidatorPass(array('required' => false)),
      'broadcast'             => new sfValidatorPass(array('required' => false)),
      'external_txtlive_link' => new sfValidatorPass(array('required' => false)),
      'hotline_link'          => new sfValidatorPass(array('required' => false)),
      'title'                 => new sfValidatorPass(array('required' => false)),
      'content'               => new sfValidatorPass(array('required' => false)),
      'img'                   => new sfValidatorPass(array('required' => false)),
      'forum_link'            => new sfValidatorPass(array('required' => false)),
      'video_link'            => new sfValidatorPass(array('required' => false)),
      'gallery_link'          => new sfValidatorPass(array('required' => false)),
      'external_match_link'   => new sfValidatorPass(array('required' => false)),
      'author'                => new sfValidatorPass(array('required' => false)),
      'author_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('hoop_match_article_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'HoopMatchArticle';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'tid'                   => 'Number',
      'match_id'              => 'ForeignKey',
      'season'                => 'Text',
      'location'              => 'Text',
      'live_name'             => 'Text',
      'live_link'             => 'Text',
      'broadcast'             => 'Text',
      'external_txtlive_link' => 'Text',
      'hotline_link'          => 'Text',
      'title'                 => 'Text',
      'content'               => 'Text',
      'img'                   => 'Text',
      'forum_link'            => 'Text',
      'video_link'            => 'Text',
      'gallery_link'          => 'Text',
      'external_match_link'   => 'Text',
      'author'                => 'Text',
      'author_id'             => 'Number',
      'created_at'            => 'Date',
      'updated_at'            => 'Date',
    );
  }
}
