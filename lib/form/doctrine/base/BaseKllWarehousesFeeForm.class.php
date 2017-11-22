<?php

/**
 * KllWarehousesFee form base class.
 *
 * @method KllWarehousesFee getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllWarehousesFeeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'warehouse_id' => new sfWidgetFormInputText(),
      'total_price'  => new sfWidgetFormInputText(),
      'note'         => new sfWidgetFormInputText(),
      'ct_time'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'warehouse_id' => new sfValidatorInteger(array('required' => false)),
      'total_price'  => new sfValidatorNumber(array('required' => false)),
      'note'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'ct_time'      => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_warehouses_fee[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllWarehousesFee';
  }

}
