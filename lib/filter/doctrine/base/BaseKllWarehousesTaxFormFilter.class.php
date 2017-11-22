<?php

/**
 * KllWarehousesTax filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllWarehousesTaxFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'warehouse_id' => new sfWidgetFormFilterInput(),
      'tax_start'    => new sfWidgetFormFilterInput(),
      'tax_rate'     => new sfWidgetFormFilterInput(),
      'tax_note'     => new sfWidgetFormFilterInput(),
      'tax_rule'     => new sfWidgetFormFilterInput(),
      'status'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'warehouse_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tax_start'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tax_rate'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'tax_note'     => new sfValidatorPass(array('required' => false)),
      'tax_rule'     => new sfValidatorPass(array('required' => false)),
      'status'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_warehouses_tax_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllWarehousesTax';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'warehouse_id' => 'Number',
      'tax_start'    => 'Number',
      'tax_rate'     => 'Number',
      'tax_note'     => 'Text',
      'tax_rule'     => 'Text',
      'status'       => 'Text',
    );
  }
}
