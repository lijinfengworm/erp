<?php

/**
 * voiceUserObject filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceUserObjectFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('voiceUser'), 'add_empty' => true)),
      'voice_object_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('voiceObject'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'user_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('voiceUser'), 'column' => 'user_id')),
      'voice_object_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('voiceObject'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('voice_user_object_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceUserObject';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'user_id'         => 'ForeignKey',
      'voice_object_id' => 'ForeignKey',
    );
  }
}
