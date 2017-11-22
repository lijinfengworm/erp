<?php

/**
 * KaluliTagsRelate filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKaluliTagsRelateFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'type'   => new sfWidgetFormFilterInput(),
      'pid'    => new sfWidgetFormFilterInput(),
      'tag_id' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'type'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pid'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tag_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kaluli_tags_relate_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliTagsRelate';
  }

  public function getFields()
  {
    return array(
      'id'     => 'Number',
      'type'   => 'Number',
      'pid'    => 'Number',
      'tag_id' => 'Number',
    );
  }
}
