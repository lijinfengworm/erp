<?php

/**
 * voiceTag filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceTagFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'hidden'               => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'category'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'hits'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'hot'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'show_order'           => new sfWidgetFormFilterInput(),
      'weight'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'twitter_message_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'twitterMessage')),
      'voice_tag_lists_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'voiceTagList')),
      'voice_objects_list'   => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'voiceObject')),
    ));

    $this->setValidators(array(
      'name'                 => new sfValidatorPass(array('required' => false)),
      'hidden'               => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'category'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hits'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hot'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'show_order'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'weight'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'twitter_message_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'twitterMessage', 'required' => false)),
      'voice_tag_lists_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'voiceTagList', 'required' => false)),
      'voice_objects_list'   => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'voiceObject', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('voice_tag_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addTwitterMessageListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.voiceTagTwitterMessage voiceTagTwitterMessage')
      ->andWhereIn('voiceTagTwitterMessage.twitter_message_id', $values)
    ;
  }

  public function addVoiceTagListsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.voiceTagListVoiceTag voiceTagListVoiceTag')
      ->andWhereIn('voiceTagListVoiceTag.voice_tag_list_id', $values)
    ;
  }

  public function addVoiceObjectsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.voiceObjectTag voiceObjectTag')
      ->andWhereIn('voiceObjectTag.voice_object_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'voiceTag';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'name'                 => 'Text',
      'hidden'               => 'Boolean',
      'category'             => 'Number',
      'hits'                 => 'Number',
      'hot'                  => 'Number',
      'show_order'           => 'Number',
      'weight'               => 'Number',
      'twitter_message_list' => 'ManyKey',
      'voice_tag_lists_list' => 'ManyKey',
      'voice_objects_list'   => 'ManyKey',
    );
  }
}
