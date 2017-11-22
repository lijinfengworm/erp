<?php

/**
 * KaluliShoppingCart form base class.
 *
 * @method KaluliShoppingCart getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKaluliShoppingCartForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'hupu_uid'      => new sfWidgetFormInputText(),
      'hupu_username' => new sfWidgetFormInputText(),
      'product_id'    => new sfWidgetFormInputText(),
      'goods_id'      => new sfWidgetFormInputText(),
      'number'        => new sfWidgetFormInputText(),
      'source'        => new sfWidgetFormInputText(),
      'type'          => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'hupu_uid'      => new sfValidatorInteger(array('required' => false)),
      'hupu_username' => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'product_id'    => new sfValidatorInteger(array('required' => false)),
      'goods_id'      => new sfValidatorInteger(array('required' => false)),
      'number'        => new sfValidatorInteger(array('required' => false)),
      'source'        => new sfValidatorInteger(array('required' => false)),
      'type'          => new sfValidatorInteger(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kaluli_shopping_cart[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliShoppingCart';
  }

}
