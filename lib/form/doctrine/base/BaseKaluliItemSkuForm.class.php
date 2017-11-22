<?php

/**
 * KaluliItemSku form base class.
 *
 * @method KaluliItemSku getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKaluliItemSkuForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'item_id'        => new sfWidgetFormInputText(),
      'code'           => new sfWidgetFormInputText(),
      'goods_no'       => new sfWidgetFormInputText(),
      'attr'           => new sfWidgetFormTextarea(),
      'ware_sku'       => new sfWidgetFormInputText(),
      'price'          => new sfWidgetFormInputText(),
      'discount_price' => new sfWidgetFormInputText(),
      'weight'         => new sfWidgetFormInputText(),
      'pic'            => new sfWidgetFormTextarea(),
      'total_num'      => new sfWidgetFormInputText(),
      'lock_num'       => new sfWidgetFormInputText(),
      'storehouse_id'  => new sfWidgetFormInputText(),
      'status'         => new sfWidgetFormInputText(),
      'wupdate_time'   => new sfWidgetFormInputText(),
      'sort'           => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'item_id'        => new sfValidatorInteger(array('required' => false)),
      'code'           => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'goods_no'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'attr'           => new sfValidatorString(array('max_length' => 5000, 'required' => false)),
      'ware_sku'       => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'price'          => new sfValidatorNumber(array('required' => false)),
      'discount_price' => new sfValidatorNumber(array('required' => false)),
      'weight'         => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'pic'            => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'total_num'      => new sfValidatorInteger(array('required' => false)),
      'lock_num'       => new sfValidatorInteger(array('required' => false)),
      'storehouse_id'  => new sfValidatorInteger(array('required' => false)),
      'status'         => new sfValidatorInteger(array('required' => false)),
      'wupdate_time'   => new sfValidatorInteger(array('required' => false)),
      'sort'           => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kaluli_item_sku[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliItemSku';
  }

}
