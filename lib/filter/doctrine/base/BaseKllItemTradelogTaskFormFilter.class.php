<?php

/**
 * KllItemTradelogTask filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllItemTradelogTaskFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'product_id'   => new sfWidgetFormFilterInput(),
      'total_num'    => new sfWidgetFormFilterInput(),
      'current_num'  => new sfWidgetFormFilterInput(),
      'end_time'     => new sfWidgetFormFilterInput(),
      'updated_time' => new sfWidgetFormFilterInput(),
      'status'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'product_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'total_num'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'current_num'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'end_time'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'updated_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_item_tradelog_task_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllItemTradelogTask';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'product_id'   => 'Number',
      'total_num'    => 'Number',
      'current_num'  => 'Number',
      'end_time'     => 'Number',
      'updated_time' => 'Number',
      'status'       => 'Number',
    );
  }
}
