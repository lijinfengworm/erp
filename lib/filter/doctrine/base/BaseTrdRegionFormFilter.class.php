<?php

/**
 * TrdRegion filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdRegionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'parent_id'   => new sfWidgetFormFilterInput(),
      'region_name' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'region_type' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'parent_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'region_name' => new sfValidatorPass(array('required' => false)),
      'region_type' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_region_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdRegion';
  }

  public function getFields()
  {
    return array(
      'region_id'   => 'Number',
      'parent_id'   => 'Number',
      'region_name' => 'Text',
      'region_type' => 'Number',
    );
  }
}
