<?php

/**
 * voiceAppNewsList filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceAppNewsListFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'      => new sfWidgetFormFilterInput(),
      'intro'      => new sfWidgetFormFilterInput(),
      'img_path'   => new sfWidgetFormFilterInput(),
      'news_url'   => new sfWidgetFormFilterInput(),
      'message_id' => new sfWidgetFormFilterInput(),
      'topic_id'   => new sfWidgetFormFilterInput(),
      'jump_url'   => new sfWidgetFormFilterInput(),
      'show_order' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'title'      => new sfValidatorPass(array('required' => false)),
      'intro'      => new sfValidatorPass(array('required' => false)),
      'img_path'   => new sfValidatorPass(array('required' => false)),
      'news_url'   => new sfValidatorPass(array('required' => false)),
      'message_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'topic_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'jump_url'   => new sfValidatorPass(array('required' => false)),
      'show_order' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('voice_app_news_list_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceAppNewsList';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'title'      => 'Text',
      'intro'      => 'Text',
      'img_path'   => 'Text',
      'news_url'   => 'Text',
      'message_id' => 'Number',
      'topic_id'   => 'Number',
      'jump_url'   => 'Text',
      'show_order' => 'Number',
    );
  }
}
