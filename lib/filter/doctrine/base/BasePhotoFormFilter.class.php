<?php

/**
 * Photo filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePhotoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'          => new sfWidgetFormFilterInput(),
      'original_id'    => new sfWidgetFormFilterInput(),
      'url'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_on_homepage' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'redirect_url'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'title'          => new sfValidatorPass(array('required' => false)),
      'original_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'url'            => new sfValidatorPass(array('required' => false)),
      'is_on_homepage' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'redirect_url'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('photo_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Photo';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'title'          => 'Text',
      'original_id'    => 'Number',
      'url'            => 'Text',
      'is_on_homepage' => 'Boolean',
      'redirect_url'   => 'Text',
    );
  }
}
