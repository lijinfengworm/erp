<?php

/**
 * voiceColumnReplyLightUser filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceColumnReplyLightUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'column_reply_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('voiceColumnReply'), 'add_empty' => true)),
      'user_id'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'column_reply_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('voiceColumnReply'), 'column' => 'id')),
      'user_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('voice_column_reply_light_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceColumnReplyLightUser';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'column_reply_id' => 'ForeignKey',
      'user_id'         => 'Number',
    );
  }
}
