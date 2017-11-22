<?php

/**
 * TrdItem form base class.
 *
 * @method TrdItem getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdItemForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'brand_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Brand'), 'add_empty' => true)),
      'shop_id'        => new sfWidgetFormInputText(),
      'item_id'        => new sfWidgetFormInputText(),
      'name'           => new sfWidgetFormInputText(),
      'memo'           => new sfWidgetFormTextarea(),
      'title'          => new sfWidgetFormInputText(),
      'url'            => new sfWidgetFormTextarea(),
      'price'          => new sfWidgetFormInputText(),
      'original_price' => new sfWidgetFormInputText(),
      'freight_payer'  => new sfWidgetFormInputText(),
      'img_url'        => new sfWidgetFormTextarea(),
      'item_no'        => new sfWidgetFormInputText(),
      'size_ids'       => new sfWidgetFormTextarea(),
      'category_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'add_empty' => true)),
      'style_ids'      => new sfWidgetFormTextarea(),
      'color_ids'      => new sfWidgetFormTextarea(),
      'sold_count'     => new sfWidgetFormInputText(),
      'is_soldout'     => new sfWidgetFormInputCheckbox(),
      'click_count'    => new sfWidgetFormInputText(),
      'like_count'     => new sfWidgetFormInputText(),
      'rank'           => new sfWidgetFormInputText(),
      'is_hide'        => new sfWidgetFormInputCheckbox(),
      'is_verified'    => new sfWidgetFormInputCheckbox(),
      'give_money'     => new sfWidgetFormInputText(),
      'baoliao_id'     => new sfWidgetFormInputText(),
      'mart'           => new sfWidgetFormInputText(),
      'is_recommend'   => new sfWidgetFormInputText(),
      'publish_date'   => new sfWidgetFormInputText(),
      'hupu_uid'       => new sfWidgetFormInputText(),
      'hupu_username'  => new sfWidgetFormInputText(),
      'status'         => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'brand_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Brand'), 'required' => false)),
      'shop_id'        => new sfValidatorInteger(array('required' => false)),
      'item_id'        => new sfValidatorInteger(array('required' => false)),
      'name'           => new sfValidatorString(array('max_length' => 100)),
      'memo'           => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'title'          => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'url'            => new sfValidatorString(array('max_length' => 2000)),
      'price'          => new sfValidatorNumber(array('required' => false)),
      'original_price' => new sfValidatorNumber(array('required' => false)),
      'freight_payer'  => new sfValidatorInteger(array('required' => false)),
      'img_url'        => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'item_no'        => new sfValidatorString(array('max_length' => 60, 'required' => false)),
      'size_ids'       => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'category_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'required' => false)),
      'style_ids'      => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'color_ids'      => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'sold_count'     => new sfValidatorInteger(array('required' => false)),
      'is_soldout'     => new sfValidatorBoolean(array('required' => false)),
      'click_count'    => new sfValidatorInteger(array('required' => false)),
      'like_count'     => new sfValidatorInteger(array('required' => false)),
      'rank'           => new sfValidatorInteger(array('required' => false)),
      'is_hide'        => new sfValidatorBoolean(array('required' => false)),
      'is_verified'    => new sfValidatorBoolean(array('required' => false)),
      'give_money'     => new sfValidatorNumber(array('required' => false)),
      'baoliao_id'     => new sfValidatorInteger(array('required' => false)),
      'mart'           => new sfValidatorString(array('max_length' => 20)),
      'is_recommend'   => new sfValidatorInteger(array('required' => false)),
      'publish_date'   => new sfValidatorInteger(array('required' => false)),
      'hupu_uid'       => new sfValidatorInteger(array('required' => false)),
      'hupu_username'  => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'status'         => new sfValidatorInteger(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_item[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdItem';
  }

}
