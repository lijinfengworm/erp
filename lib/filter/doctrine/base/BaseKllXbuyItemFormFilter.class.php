<?php

/**
 * KllXbuyItem filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllXbuyItemFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'activity_id'  => new sfWidgetFormFilterInput(),
      'item_id'      => new sfWidgetFormFilterInput(),
      'number'       => new sfWidgetFormFilterInput(),
      'price'        => new sfWidgetFormFilterInput(),
      'status'       => new sfWidgetFormFilterInput(),
      'title'        => new sfWidgetFormFilterInput(),
      'origin_price' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'activity_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'item_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'number'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'price'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'status'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'        => new sfValidatorPass(array('required' => false)),
      'origin_price' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_xbuy_item_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllXbuyItem';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'activity_id'  => 'Number',
      'item_id'      => 'Number',
      'number'       => 'Number',
      'price'        => 'Number',
      'status'       => 'Number',
      'title'        => 'Text',
      'origin_price' => 'Number',
    );
  }
}
