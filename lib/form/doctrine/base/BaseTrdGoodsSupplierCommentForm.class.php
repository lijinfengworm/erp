<?php

/**
 * TrdGoodsSupplierComment form base class.
 *
 * @method TrdGoodsSupplierComment getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsSupplierCommentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'goods_id'      => new sfWidgetFormInputText(),
      'supplier_id'   => new sfWidgetFormInputText(),
      'supplier_name' => new sfWidgetFormInputText(),
      'supplier_url'  => new sfWidgetFormInputText(),
      'nickname'      => new sfWidgetFormInputText(),
      'content'       => new sfWidgetFormTextarea(),
      'img_attr'      => new sfWidgetFormTextarea(),
      'info'          => new sfWidgetFormTextarea(),
      'type'          => new sfWidgetFormInputText(),
      'status'        => new sfWidgetFormInputText(),
      'unique_id'     => new sfWidgetFormInputText(),
      'sku'           => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'goods_id'      => new sfValidatorInteger(array('required' => false)),
      'supplier_id'   => new sfValidatorInteger(array('required' => false)),
      'supplier_name' => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'supplier_url'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'nickname'      => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'content'       => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'img_attr'      => new sfValidatorString(array('max_length' => 1024, 'required' => false)),
      'info'          => new sfValidatorString(array('max_length' => 1024, 'required' => false)),
      'type'          => new sfValidatorInteger(array('required' => false)),
      'status'        => new sfValidatorInteger(array('required' => false)),
      'unique_id'     => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'sku'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_supplier_comment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsSupplierComment';
  }

}
