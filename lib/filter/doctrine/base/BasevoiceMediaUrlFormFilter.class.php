<?php

/**
 * voiceMediaUrl filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceMediaUrlFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'voice_media_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('voiceMedia'), 'add_empty' => true)),
      'url'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'voice_media_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('voiceMedia'), 'column' => 'id')),
      'url'            => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('voice_media_url_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceMediaUrl';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'voice_media_id' => 'ForeignKey',
      'url'            => 'Text',
    );
  }
}
