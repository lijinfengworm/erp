<?php

/**
 * twitterTopicGroupMessage filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasetwitterTopicGroupMessageFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'twitter_topic_group_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('twitterTopicGroup'), 'add_empty' => true)),
      'twitter_message_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('twitterMessage'), 'add_empty' => true)),
      'order_num'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'live_time'              => new sfWidgetFormFilterInput(),
      'rank_info'              => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'twitter_topic_group_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('twitterTopicGroup'), 'column' => 'id')),
      'twitter_message_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('twitterMessage'), 'column' => 'id')),
      'order_num'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'live_time'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rank_info'              => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('twitter_topic_group_message_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'twitterTopicGroupMessage';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'twitter_topic_group_id' => 'ForeignKey',
      'twitter_message_id'     => 'ForeignKey',
      'order_num'              => 'Number',
      'live_time'              => 'Number',
      'rank_info'              => 'Text',
    );
  }
}
