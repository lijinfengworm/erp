<?php

/**
 * KllWarehousesTax form base class.
 *
 * @method KllWarehousesTax getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllWarehousesTaxForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'warehouse_id' => new sfWidgetFormInputText(),
      'tax_start'    => new sfWidgetFormInputText(),
      'tax_rate'     => new sfWidgetFormInputText(),
      'tax_note'     => new sfWidgetFormInputText(),
      'tax_rule'     => new sfWidgetFormInputText(),
      'status'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'warehouse_id' => new sfValidatorInteger(array('required' => false)),
      'tax_start'    => new sfValidatorInteger(array('required' => false)),
      'tax_rate'     => new sfValidatorNumber(array('required' => false)),
      'tax_note'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'tax_rule'     => new sfValidatorPass(array('required' => false)),
      'status'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_warehouses_tax[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllWarehousesTax';
  }

}
