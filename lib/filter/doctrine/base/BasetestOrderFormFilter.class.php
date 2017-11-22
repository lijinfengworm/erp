<?php

/**
 * testOrder filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasetestOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_no'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'cookie'            => new sfWidgetFormFilterInput(),
      'gamepay_collector' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'order_no'          => new sfValidatorPass(array('required' => false)),
      'cookie'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'gamepay_collector' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('test_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'testOrder';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'order_no'          => 'Text',
      'cookie'            => 'Number',
      'gamepay_collector' => 'Text',
    );
  }
}
