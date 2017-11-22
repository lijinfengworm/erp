<?php

/**
 * TrdBaoliao form base class.
 *
 * @method TrdBaoliao getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdBaoliaoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'brand_id'          => new sfWidgetFormInputText(),
      'brand'             => new sfWidgetFormInputText(),
      'item_num'          => new sfWidgetFormInputText(),
      'is_showsports'     => new sfWidgetFormInputText(),
      'shop_id'           => new sfWidgetFormInputText(),
      'item_id'           => new sfWidgetFormInputText(),
      'name'              => new sfWidgetFormInputText(),
      'sub_name'          => new sfWidgetFormInputText(),
      'memo'              => new sfWidgetFormTextarea(),
      'description'       => new sfWidgetFormInputText(),
      'url'               => new sfWidgetFormTextarea(),
      'encrypt_url'       => new sfWidgetFormInputText(),
      'price'             => new sfWidgetFormInputText(),
      'original_price'    => new sfWidgetFormInputText(),
      'mart'              => new sfWidgetFormInputText(),
      'sold_count'        => new sfWidgetFormInputText(),
      'is_soldout'        => new sfWidgetFormInputText(),
      'img_url'           => new sfWidgetFormTextarea(),
      'give_money'        => new sfWidgetFormInputText(),
      'category_id'       => new sfWidgetFormInputText(),
      'type'              => new sfWidgetFormInputText(),
      'root_id'           => new sfWidgetFormInputText(),
      'children_id'       => new sfWidgetFormInputText(),
      'attr_collect'      => new sfWidgetFormInputText(),
      'pic_collect'       => new sfWidgetFormTextarea(),
      'tag_collect'       => new sfWidgetFormTextarea(),
      'model_id'          => new sfWidgetFormInputText(),
      'external_username' => new sfWidgetFormInputText(),
      'publish_date'      => new sfWidgetFormDateTime(),
      'hupu_uid'          => new sfWidgetFormInputText(),
      'hupu_username'     => new sfWidgetFormInputText(),
      'status'            => new sfWidgetFormInputText(),
      'commodity'         => new sfWidgetFormTextarea(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'brand_id'          => new sfValidatorInteger(array('required' => false)),
      'brand'             => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'item_num'          => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'is_showsports'     => new sfValidatorInteger(array('required' => false)),
      'shop_id'           => new sfValidatorInteger(array('required' => false)),
      'item_id'           => new sfValidatorInteger(array('required' => false)),
      'name'              => new sfValidatorString(array('max_length' => 100)),
      'sub_name'          => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'memo'              => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'description'       => new sfValidatorPass(array('required' => false)),
      'url'               => new sfValidatorString(array('max_length' => 2000)),
      'encrypt_url'       => new sfValidatorString(array('max_length' => 8)),
      'price'             => new sfValidatorNumber(array('required' => false)),
      'original_price'    => new sfValidatorNumber(array('required' => false)),
      'mart'              => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'sold_count'        => new sfValidatorInteger(array('required' => false)),
      'is_soldout'        => new sfValidatorInteger(array('required' => false)),
      'img_url'           => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'give_money'        => new sfValidatorNumber(array('required' => false)),
      'category_id'       => new sfValidatorInteger(array('required' => false)),
      'type'              => new sfValidatorInteger(array('required' => false)),
      'root_id'           => new sfValidatorInteger(array('required' => false)),
      'children_id'       => new sfValidatorInteger(array('required' => false)),
      'attr_collect'      => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'pic_collect'       => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'tag_collect'       => new sfValidatorString(array('max_length' => 300, 'required' => false)),
      'model_id'          => new sfValidatorInteger(array('required' => false)),
      'external_username' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'publish_date'      => new sfValidatorDateTime(array('required' => false)),
      'hupu_uid'          => new sfValidatorInteger(array('required' => false)),
      'hupu_username'     => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'status'            => new sfValidatorInteger(array('required' => false)),
      'commodity'         => new sfValidatorString(array('max_length' => 511, 'required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_baoliao[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdBaoliao';
  }

}
