<?php

/**
 * voiceObjectTag filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceObjectTagFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'voice_object_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('voiceObject'), 'add_empty' => true)),
      'voice_tag_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('voiceTag'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'voice_object_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('voiceObject'), 'column' => 'id')),
      'voice_tag_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('voiceTag'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('voice_object_tag_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceObjectTag';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'voice_object_id' => 'ForeignKey',
      'voice_tag_id'    => 'ForeignKey',
    );
  }
}
