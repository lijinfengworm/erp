<?php

/**
 * voiceUserFollow filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceUserFollowFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'voice_media_ids'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'voice_tag_ids'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'voice_reporter_ids' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'voice_media_ids'    => new sfValidatorPass(array('required' => false)),
      'voice_tag_ids'      => new sfValidatorPass(array('required' => false)),
      'voice_reporter_ids' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('voice_user_follow_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceUserFollow';
  }

  public function getFields()
  {
    return array(
      'user_id'            => 'Number',
      'voice_media_ids'    => 'Text',
      'voice_tag_ids'      => 'Text',
      'voice_reporter_ids' => 'Text',
    );
  }
}
