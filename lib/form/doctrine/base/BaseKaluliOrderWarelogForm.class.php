<?php

/**
 * KaluliOrderWarelog form base class.
 *
 * @method KaluliOrderWarelog getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKaluliOrderWarelogForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'order_number'    => new sfWidgetFormInputText(),
      'order_id'        => new sfWidgetFormInputText(),
      'msg'             => new sfWidgetFormInputText(),
      'hupu_uid'        => new sfWidgetFormInputText(),
      'username'        => new sfWidgetFormInputText(),
      'ware_type'       => new sfWidgetFormInputText(),
      'status'          => new sfWidgetFormInputText(),
      'ware_order_type' => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'order_id'        => new sfValidatorInteger(array('required' => false)),
      'msg'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'hupu_uid'        => new sfValidatorInteger(),
      'username'        => new sfValidatorString(array('max_length' => 20)),
      'ware_type'       => new sfValidatorInteger(),
      'status'          => new sfValidatorInteger(),
      'ware_order_type' => new sfValidatorInteger(),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kaluli_order_warelog[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliOrderWarelog';
  }

}
