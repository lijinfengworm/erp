<?php

/**
 * llArticles form base class.
 *
 * @method llArticles getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasellArticlesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                           => new sfWidgetFormInputHidden(),
      'small_title'                  => new sfWidgetFormInputText(),
      'title'                        => new sfWidgetFormInputText(),
      'zone_type'                    => new sfWidgetFormInputText(),
      'zone_index'                   => new sfWidgetFormInputText(),
      'zone_order'                   => new sfWidgetFormInputText(),
      'type'                         => new sfWidgetFormInputText(),
      'preview_content'              => new sfWidgetFormTextarea(),
      'content'                      => new sfWidgetFormTextarea(),
      'is_published'                 => new sfWidgetFormInputCheckbox(),
      'published_date'               => new sfWidgetFormDateTime(),
      'hasCover'                     => new sfWidgetFormInputCheckbox(),
      'cover_pic_url'                => new sfWidgetFormInputText(),
      'cover_smallpic_url'           => new sfWidgetFormInputText(),
      'hasRedirect'                  => new sfWidgetFormInputCheckbox(),
      'redirect_url'                 => new sfWidgetFormInputText(),
      'hasRecommendedGroup'          => new sfWidgetFormInputCheckbox(),
      'reply_content'                => new sfWidgetFormTextarea(),
      'video_type'                   => new sfWidgetFormInputText(),
      'original_video_id'            => new sfWidgetFormInputText(),
      'original_video_collection_id' => new sfWidgetFormInputText(),
      'original_album_id'            => new sfWidgetFormInputText(),
      'created_at'                   => new sfWidgetFormDateTime(),
      'updated_at'                   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'small_title'                  => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'title'                        => new sfValidatorString(array('max_length' => 45)),
      'zone_type'                    => new sfValidatorInteger(array('required' => false)),
      'zone_index'                   => new sfValidatorInteger(array('required' => false)),
      'zone_order'                   => new sfValidatorInteger(array('required' => false)),
      'type'                         => new sfValidatorInteger(),
      'preview_content'              => new sfValidatorString(array('max_length' => 500)),
      'content'                      => new sfValidatorString(array('required' => false)),
      'is_published'                 => new sfValidatorBoolean(array('required' => false)),
      'published_date'               => new sfValidatorDateTime(array('required' => false)),
      'hasCover'                     => new sfValidatorBoolean(),
      'cover_pic_url'                => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'cover_smallpic_url'           => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'hasRedirect'                  => new sfValidatorBoolean(array('required' => false)),
      'redirect_url'                 => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'hasRecommendedGroup'          => new sfValidatorBoolean(array('required' => false)),
      'reply_content'                => new sfValidatorString(array('max_length' => 3000, 'required' => false)),
      'video_type'                   => new sfValidatorInteger(array('required' => false)),
      'original_video_id'            => new sfValidatorInteger(array('required' => false)),
      'original_video_collection_id' => new sfValidatorInteger(array('required' => false)),
      'original_album_id'            => new sfValidatorInteger(array('required' => false)),
      'created_at'                   => new sfValidatorDateTime(),
      'updated_at'                   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ll_articles[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'llArticles';
  }

}
