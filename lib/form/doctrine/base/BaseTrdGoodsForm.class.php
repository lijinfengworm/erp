<?php

/**
 * TrdGoods form base class.
 *
 * @method TrdGoods getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'name'              => new sfWidgetFormInputText(),
      'code'              => new sfWidgetFormInputText(),
      'root_brand_id'     => new sfWidgetFormInputText(),
      'child_brand_id'    => new sfWidgetFormInputText(),
      'root_category_id'  => new sfWidgetFormInputText(),
      'child_category_id' => new sfWidgetFormInputText(),
      'type'              => new sfWidgetFormInputText(),
      'from_type'         => new sfWidgetFormInputText(),
      'from_id'           => new sfWidgetFormInputText(),
      'pic'               => new sfWidgetFormInputText(),
      'supplier_count'    => new sfWidgetFormInputText(),
      'status'            => new sfWidgetFormInputText(),
      'comment'           => new sfWidgetFormInputText(),
      'admin_id'          => new sfWidgetFormInputText(),
      'is_delete'         => new sfWidgetFormInputText(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'              => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'code'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'root_brand_id'     => new sfValidatorInteger(array('required' => false)),
      'child_brand_id'    => new sfValidatorInteger(array('required' => false)),
      'root_category_id'  => new sfValidatorInteger(array('required' => false)),
      'child_category_id' => new sfValidatorInteger(array('required' => false)),
      'type'              => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'from_type'         => new sfValidatorInteger(array('required' => false)),
      'from_id'           => new sfValidatorInteger(array('required' => false)),
      'pic'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'supplier_count'    => new sfValidatorInteger(array('required' => false)),
      'status'            => new sfValidatorInteger(array('required' => false)),
      'comment'           => new sfValidatorInteger(array('required' => false)),
      'admin_id'          => new sfValidatorInteger(array('required' => false)),
      'is_delete'         => new sfValidatorInteger(array('required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_goods[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoods';
  }

}
