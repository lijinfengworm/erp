<?php

/**
 * KaluliOrderHistory form base class.
 *
 * @method KaluliOrderHistory getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKaluliOrderHistoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'order_number'   => new sfWidgetFormInputText(),
      'hupu_uid'       => new sfWidgetFormInputText(),
      'hupu_username'  => new sfWidgetFormInputText(),
      'type'           => new sfWidgetFormInputText(),
      'explanation'    => new sfWidgetFormInputText(),
      'grant_uid'      => new sfWidgetFormInputText(),
      'grant_username' => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number'   => new sfValidatorInteger(array('required' => false)),
      'hupu_uid'       => new sfValidatorInteger(array('required' => false)),
      'hupu_username'  => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'type'           => new sfValidatorInteger(array('required' => false)),
      'explanation'    => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'grant_uid'      => new sfValidatorInteger(array('required' => false)),
      'grant_username' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kaluli_order_history[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliOrderHistory';
  }

}
