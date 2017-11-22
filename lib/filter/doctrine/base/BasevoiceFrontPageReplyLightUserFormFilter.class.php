<?php

/**
 * voiceFrontPageReplyLightUser filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceFrontPageReplyLightUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'fp_reply_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('voiceFrontPageReply'), 'add_empty' => true)),
      'user_id'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'fp_reply_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('voiceFrontPageReply'), 'column' => 'id')),
      'user_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('voice_front_page_reply_light_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceFrontPageReplyLightUser';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'fp_reply_id' => 'ForeignKey',
      'user_id'     => 'Number',
    );
  }
}
