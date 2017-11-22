<?php

/**
 * TrdGroupon form base class.
 *
 * @method TrdGroupon getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdGrouponForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'brand_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Brand'), 'add_empty' => true)),
      'shop_id'         => new sfWidgetFormInputText(),
      'item_id'         => new sfWidgetFormInputText(),
      'hupu_uid'        => new sfWidgetFormInputText(),
      'hupu_username'   => new sfWidgetFormInputText(),
      'ibilling_number' => new sfWidgetFormInputText(),
      'pay_status'      => new sfWidgetFormInputText(),
      'intro'           => new sfWidgetFormTextarea(),
      'memo'            => new sfWidgetFormTextarea(),
      'title'           => new sfWidgetFormInputText(),
      'url'             => new sfWidgetFormTextarea(),
      'attend_count'    => new sfWidgetFormInputText(),
      'price'           => new sfWidgetFormInputText(),
      'original_price'  => new sfWidgetFormInputText(),
      'discount'        => new sfWidgetFormInputText(),
      'praise'          => new sfWidgetFormInputText(),
      'category_id'     => new sfWidgetFormInputText(),
      'start_time'      => new sfWidgetFormInputText(),
      'end_time'        => new sfWidgetFormInputText(),
      'attr'            => new sfWidgetFormTextarea(),
      'goods_num'       => new sfWidgetFormInputText(),
      'color_num'       => new sfWidgetFormInputText(),
      'pic_attr'        => new sfWidgetFormTextarea(),
      'rank'            => new sfWidgetFormInputText(),
      'alliance'        => new sfWidgetFormInputText(),
      'is_sold'         => new sfWidgetFormInputText(),
      'collect_count'   => new sfWidgetFormInputText(),
      'status'          => new sfWidgetFormInputText(),
      'usp_logo'        => new sfWidgetFormInputText(),
      'type'            => new sfWidgetFormInputText(),
      'is_ad'           => new sfWidgetFormInputText(),
      'pay_type'        => new sfWidgetFormInputText(),
      'pay_date'        => new sfWidgetFormInputText(),
      'commodity'       => new sfWidgetFormTextarea(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'deleted_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'brand_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Brand'), 'required' => false)),
      'shop_id'         => new sfValidatorInteger(array('required' => false)),
      'item_id'         => new sfValidatorInteger(array('required' => false)),
      'hupu_uid'        => new sfValidatorInteger(array('required' => false)),
      'hupu_username'   => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'ibilling_number' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'pay_status'      => new sfValidatorInteger(array('required' => false)),
      'intro'           => new sfValidatorString(array('max_length' => 5000, 'required' => false)),
      'memo'            => new sfValidatorString(array('max_length' => 10000, 'required' => false)),
      'title'           => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'url'             => new sfValidatorString(array('max_length' => 1000)),
      'attend_count'    => new sfValidatorInteger(array('required' => false)),
      'price'           => new sfValidatorNumber(array('required' => false)),
      'original_price'  => new sfValidatorNumber(array('required' => false)),
      'discount'        => new sfValidatorNumber(array('required' => false)),
      'praise'          => new sfValidatorInteger(array('required' => false)),
      'category_id'     => new sfValidatorInteger(array('required' => false)),
      'start_time'      => new sfValidatorPass(array('required' => false)),
      'end_time'        => new sfValidatorPass(array('required' => false)),
      'attr'            => new sfValidatorString(array('max_length' => 5000, 'required' => false)),
      'goods_num'       => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'color_num'       => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'pic_attr'        => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'rank'            => new sfValidatorInteger(array('required' => false)),
      'alliance'        => new sfValidatorInteger(array('required' => false)),
      'is_sold'         => new sfValidatorInteger(array('required' => false)),
      'collect_count'   => new sfValidatorInteger(array('required' => false)),
      'status'          => new sfValidatorInteger(array('required' => false)),
      'usp_logo'        => new sfValidatorInteger(array('required' => false)),
      'type'            => new sfValidatorInteger(array('required' => false)),
      'is_ad'           => new sfValidatorInteger(array('required' => false)),
      'pay_type'        => new sfValidatorInteger(array('required' => false)),
      'pay_date'        => new sfValidatorInteger(array('required' => false)),
      'commodity'       => new sfValidatorString(array('max_length' => 511, 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
      'deleted_at'      => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_groupon[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGroupon';
  }

}
