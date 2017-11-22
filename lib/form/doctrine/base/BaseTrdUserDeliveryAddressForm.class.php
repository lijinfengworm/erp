<?php

/**
 * TrdUserDeliveryAddress form base class.
 *
 * @method TrdUserDeliveryAddress getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdUserDeliveryAddressForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'hupu_uid'        => new sfWidgetFormInputText(),
      'hupu_username'   => new sfWidgetFormInputText(),
      'name'            => new sfWidgetFormInputText(),
      'postcode'        => new sfWidgetFormInputText(),
      'province'        => new sfWidgetFormInputText(),
      'city'            => new sfWidgetFormInputText(),
      'area'            => new sfWidgetFormInputText(),
      'mobile'          => new sfWidgetFormInputText(),
      'phonesection'    => new sfWidgetFormInputText(),
      'phonecode'       => new sfWidgetFormInputText(),
      'phoneext'        => new sfWidgetFormInputText(),
      'region'          => new sfWidgetFormInputText(),
      'street'          => new sfWidgetFormInputText(),
      'identity_number' => new sfWidgetFormInputText(),
      'defaultflag'     => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'hupu_uid'        => new sfValidatorInteger(array('required' => false)),
      'hupu_username'   => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'name'            => new sfValidatorString(array('max_length' => 30)),
      'postcode'        => new sfValidatorString(array('max_length' => 6)),
      'province'        => new sfValidatorInteger(array('required' => false)),
      'city'            => new sfValidatorInteger(array('required' => false)),
      'area'            => new sfValidatorInteger(array('required' => false)),
      'mobile'          => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'phonesection'    => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'phonecode'       => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'phoneext'        => new sfValidatorString(array('max_length' => 6, 'required' => false)),
      'region'          => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'street'          => new sfValidatorString(array('max_length' => 120)),
      'identity_number' => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'defaultflag'     => new sfValidatorInteger(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_user_delivery_address[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdUserDeliveryAddress';
  }

}
