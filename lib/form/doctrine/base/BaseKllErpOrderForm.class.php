<?php

/**
 * KllErpOrder form base class.
 *
 * @method KllErpOrder getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllErpOrderForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'order_number' => new sfWidgetFormInputText(),
      'audit_status' => new sfWidgetFormInputText(),
      'channel'      => new sfWidgetFormInputText(),
      'audit_user'   => new sfWidgetFormInputText(),
      'audit_time'   => new sfWidgetFormInputText(),
      'create_time'  => new sfWidgetFormInputText(),
      'update_time'  => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number' => new sfValidatorPass(array('required' => false)),
      'audit_status' => new sfValidatorPass(array('required' => false)),
      'channel'      => new sfValidatorPass(array('required' => false)),
      'audit_user'   => new sfValidatorInteger(array('required' => false)),
      'audit_time'   => new sfValidatorInteger(array('required' => false)),
      'create_time'  => new sfValidatorInteger(array('required' => false)),
      'update_time'  => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_erp_order[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllErpOrder';
  }

}
