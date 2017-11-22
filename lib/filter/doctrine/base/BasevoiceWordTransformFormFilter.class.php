<?php

/**
 * voiceWordTransform filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceWordTransformFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'original'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'transformed' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'original'    => new sfValidatorPass(array('required' => false)),
      'transformed' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('voice_word_transform_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceWordTransform';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'original'    => 'Text',
      'transformed' => 'Text',
    );
  }
}
