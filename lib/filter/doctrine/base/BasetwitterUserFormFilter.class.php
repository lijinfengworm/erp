<?php

/**
 * twitterUser filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasetwitterUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'twitter_tag_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('twitterTag'), 'add_empty' => true)),
      'name'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'         => new sfWidgetFormFilterInput(),
      'avatar_url'          => new sfWidgetFormFilterInput(),
      'slug'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'identity'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'hits'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'voice_tag_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('voiceTag'), 'add_empty' => true)),
      'twitter_topics_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'twitterTopic')),
    ));

    $this->setValidators(array(
      'twitter_tag_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('twitterTag'), 'column' => 'id')),
      'name'                => new sfValidatorPass(array('required' => false)),
      'description'         => new sfValidatorPass(array('required' => false)),
      'avatar_url'          => new sfValidatorPass(array('required' => false)),
      'slug'                => new sfValidatorPass(array('required' => false)),
      'identity'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hits'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'voice_tag_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('voiceTag'), 'column' => 'id')),
      'twitter_topics_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'twitterTopic', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('twitter_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addTwitterTopicsListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.twitterTopicUser twitterTopicUser')
      ->andWhereIn('twitterTopicUser.twitter_topic_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'twitterUser';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'twitter_tag_id'      => 'ForeignKey',
      'name'                => 'Text',
      'description'         => 'Text',
      'avatar_url'          => 'Text',
      'slug'                => 'Text',
      'identity'            => 'Number',
      'hits'                => 'Number',
      'voice_tag_id'        => 'ForeignKey',
      'twitter_topics_list' => 'ManyKey',
    );
  }
}
