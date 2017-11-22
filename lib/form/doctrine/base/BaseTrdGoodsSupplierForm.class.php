<?php

/**
 * TrdGoodsSupplier form base class.
 *
 * @method TrdGoodsSupplier getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsSupplierForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'goods_id'            => new sfWidgetFormInputText(),
      'name'                => new sfWidgetFormInputText(),
      'store'               => new sfWidgetFormInputText(),
      'description'         => new sfWidgetFormInputText(),
      'price'               => new sfWidgetFormInputText(),
      'url'                 => new sfWidgetFormInputText(),
      'from_id'             => new sfWidgetFormInputText(),
      'from_type'           => new sfWidgetFormInputText(),
      'status'              => new sfWidgetFormInputText(),
      'unique_id'           => new sfWidgetFormInputText(),
      'update_time'         => new sfWidgetFormInputText(),
      'update_info'         => new sfWidgetFormInputText(),
      'update_error_num'    => new sfWidgetFormInputText(),
      'update_error_info'   => new sfWidgetFormInputText(),
      'comment_update_time' => new sfWidgetFormInputText(),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'goods_id'            => new sfValidatorInteger(array('required' => false)),
      'name'                => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'store'               => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'description'         => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'price'               => new sfValidatorNumber(array('required' => false)),
      'url'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'from_id'             => new sfValidatorInteger(array('required' => false)),
      'from_type'           => new sfValidatorInteger(array('required' => false)),
      'status'              => new sfValidatorInteger(array('required' => false)),
      'unique_id'           => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'update_time'         => new sfValidatorPass(array('required' => false)),
      'update_info'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'update_error_num'    => new sfValidatorInteger(array('required' => false)),
      'update_error_info'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'comment_update_time' => new sfValidatorPass(array('required' => false)),
      'created_at'          => new sfValidatorDateTime(),
      'updated_at'          => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_supplier[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsSupplier';
  }

}
