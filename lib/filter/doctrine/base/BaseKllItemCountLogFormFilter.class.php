<?php

/**
 * KllItemCountLog filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllItemCountLogFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'item_id' => new sfWidgetFormFilterInput(),
      'pv'      => new sfWidgetFormFilterInput(),
      'uv'      => new sfWidgetFormFilterInput(),
      'time'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'item_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pv'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'uv'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'time'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_item_count_log_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllItemCountLog';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'item_id' => 'Number',
      'pv'      => 'Number',
      'uv'      => 'Number',
      'time'    => 'Number',
    );
  }
}
