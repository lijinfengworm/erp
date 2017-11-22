<?php

/**
 * KaluliTags filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKaluliTagsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'type'   => new sfWidgetFormFilterInput(),
      'name'   => new sfWidgetFormFilterInput(),
      'weight' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'type'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'   => new sfValidatorPass(array('required' => false)),
      'weight' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kaluli_tags_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliTags';
  }

  public function getFields()
  {
    return array(
      'id'     => 'Number',
      'type'   => 'Number',
      'name'   => 'Text',
      'weight' => 'Number',
    );
  }
}
