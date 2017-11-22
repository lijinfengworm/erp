<?php

/**
 * newWpOrder form base class.
 *
 * @method newWpOrder getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasenewWpOrderForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'status'         => new sfWidgetFormInputText(),
      'user_id'        => new sfWidgetFormInputText(),
      'user_name'      => new sfWidgetFormInputText(),
      'role_user_id'   => new sfWidgetFormInputText(),
      'role_user_name' => new sfWidgetFormInputText(),
      'wpgame_name'    => new sfWidgetFormInputText(),
      'wpserver_name'  => new sfWidgetFormInputText(),
      'wppayment_name' => new sfWidgetFormInputText(),
      'wppayment_id'   => new sfWidgetFormInputText(),
      'order_no'       => new sfWidgetFormInputText(),
      'amount'         => new sfWidgetFormInputText(),
      'ip'             => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'status'         => new sfValidatorInteger(array('required' => false)),
      'user_id'        => new sfValidatorInteger(),
      'user_name'      => new sfValidatorString(array('max_length' => 45)),
      'role_user_id'   => new sfValidatorString(array('max_length' => 25, 'required' => false)),
      'role_user_name' => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'wpgame_name'    => new sfValidatorString(array('max_length' => 45)),
      'wpserver_name'  => new sfValidatorString(array('max_length' => 45)),
      'wppayment_name' => new sfValidatorString(array('max_length' => 45)),
      'wppayment_id'   => new sfValidatorInteger(),
      'order_no'       => new sfValidatorInteger(),
      'amount'         => new sfValidatorNumber(),
      'ip'             => new sfValidatorString(array('max_length' => 15, 'required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('new_wp_order[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'newWpOrder';
  }

}
