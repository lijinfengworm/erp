<?php

/**
 * KllItemTradelog filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllItemTradelogFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'product_id'   => new sfWidgetFormFilterInput(),
      'username'     => new sfWidgetFormFilterInput(),
      'attr'         => new sfWidgetFormFilterInput(),
      'order_id'     => new sfWidgetFormFilterInput(),
      'num'          => new sfWidgetFormFilterInput(),
      'created_time' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'product_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'username'     => new sfValidatorPass(array('required' => false)),
      'attr'         => new sfValidatorPass(array('required' => false)),
      'order_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'num'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_item_tradelog_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllItemTradelog';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'product_id'   => 'Number',
      'username'     => 'Text',
      'attr'         => 'Text',
      'order_id'     => 'Number',
      'num'          => 'Number',
      'created_time' => 'Number',
    );
  }
}
