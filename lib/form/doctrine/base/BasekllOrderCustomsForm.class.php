<?php

/**
 * kllOrderCustoms form base class.
 *
 * @method kllOrderCustoms getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasekllOrderCustomsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'logisticsId'   => new sfWidgetFormInputText(),
      'order_number'  => new sfWidgetFormInputText(),
      'order_price'   => new sfWidgetFormInputText(),
      'customs_dutys' => new sfWidgetFormInputText(),
      'customs_img'   => new sfWidgetFormTextarea(),
      'customs_rate'  => new sfWidgetFormInputText(),
      'cost_unit'     => new sfWidgetFormInputText(),
      'status'        => new sfWidgetFormInputText(),
      'check_status'  => new sfWidgetFormInputText(),
      'create_time'   => new sfWidgetFormDateTime(),
      'check_time'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'logisticsId'   => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'order_number'  => new sfValidatorInteger(array('required' => false)),
      'order_price'   => new sfValidatorNumber(array('required' => false)),
      'customs_dutys' => new sfValidatorNumber(array('required' => false)),
      'customs_img'   => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'customs_rate'  => new sfValidatorNumber(array('required' => false)),
      'cost_unit'     => new sfValidatorPass(array('required' => false)),
      'status'        => new sfValidatorPass(array('required' => false)),
      'check_status'  => new sfValidatorPass(array('required' => false)),
      'create_time'   => new sfValidatorDateTime(array('required' => false)),
      'check_time'    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_order_customs[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'kllOrderCustoms';
  }

}
