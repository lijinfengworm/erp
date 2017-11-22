<?php

/**
 * TrdGoodsAddress form base class.
 *
 * @method TrdGoodsAddress getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsAddressForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'activity_id'   => new sfWidgetFormInputText(),
      'hupu_uid'      => new sfWidgetFormInputText(),
      'hupu_username' => new sfWidgetFormInputText(),
      'name'          => new sfWidgetFormInputText(),
      'tel'           => new sfWidgetFormInputText(),
      'province'      => new sfWidgetFormInputText(),
      'city'          => new sfWidgetFormInputText(),
      'address'       => new sfWidgetFormInputText(),
      'note'          => new sfWidgetFormInputText(),
      'status'        => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'activity_id'   => new sfValidatorInteger(array('required' => false)),
      'hupu_uid'      => new sfValidatorInteger(array('required' => false)),
      'hupu_username' => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'name'          => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'tel'           => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'province'      => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'city'          => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'address'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'note'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'status'        => new sfValidatorInteger(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_address[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsAddress';
  }

}
