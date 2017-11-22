<?php

/**
 * TrdProductAttr form base class.
 *
 * @method TrdProductAttr getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdProductAttrForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'news_id'           => new sfWidgetFormInputText(),
      'goods_id'          => new sfWidgetFormInputText(),
      'title'             => new sfWidgetFormInputText(),
      'name'              => new sfWidgetFormInputText(),
      'url'               => new sfWidgetFormInputText(),
      'img_path'          => new sfWidgetFormInputText(),
      'intro'             => new sfWidgetFormTextarea(),
      'memo'              => new sfWidgetFormTextarea(),
      'price'             => new sfWidgetFormInputText(),
      'exchange'          => new sfWidgetFormInputText(),
      'original_cost'     => new sfWidgetFormInputText(),
      'freight'           => new sfWidgetFormInputText(),
      'business_weight'   => new sfWidgetFormInputText(),
      'weight'            => new sfWidgetFormInputText(),
      'limits'            => new sfWidgetFormInputText(),
      'content'           => new sfWidgetFormTextarea(),
      'business'          => new sfWidgetFormInputText(),
      'start_date'        => new sfWidgetFormInputText(),
      'end_date'          => new sfWidgetFormInputText(),
      'crawl_flag'        => new sfWidgetFormInputText(),
      'show_flag'         => new sfWidgetFormInputText(),
      'root_id'           => new sfWidgetFormInputText(),
      'children_id'       => new sfWidgetFormInputText(),
      'brand_id'          => new sfWidgetFormInputText(),
      'hits'              => new sfWidgetFormInputText(),
      'praise'            => new sfWidgetFormInputText(),
      'external_username' => new sfWidgetFormInputText(),
      'last_crawl_date'   => new sfWidgetFormInputText(),
      'display'           => new sfWidgetFormInputText(),
      'status'            => new sfWidgetFormInputText(),
      'author_id'         => new sfWidgetFormInputText(),
      'editor_id'         => new sfWidgetFormInputText(),
      'publish_date'      => new sfWidgetFormDateTime(),
      'purchase_flag'     => new sfWidgetFormInputText(),
      'purchase_uid'      => new sfWidgetFormInputText(),
      'purchase_msg'      => new sfWidgetFormInputText(),
      'purchase_date'     => new sfWidgetFormInputText(),
      'dace_hits'         => new sfWidgetFormInputText(),
      'dace_buy_hits'     => new sfWidgetFormInputText(),
      'comment_count'     => new sfWidgetFormInputText(),
      'comment_count_img' => new sfWidgetFormInputText(),
      'tags_attr'         => new sfWidgetFormTextarea(),
      'discount'          => new sfWidgetFormInputText(),
      'discount_endtime'  => new sfWidgetFormDateTime(),
      'collect_count'     => new sfWidgetFormInputText(),
      'shaiwu_count'      => new sfWidgetFormInputText(),
      'commodity'         => new sfWidgetFormTextarea(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'news_id'           => new sfValidatorInteger(array('required' => false)),
      'goods_id'          => new sfValidatorInteger(array('required' => false)),
      'title'             => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'name'              => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'url'               => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'img_path'          => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'intro'             => new sfValidatorString(array('max_length' => 5000, 'required' => false)),
      'memo'              => new sfValidatorString(array('max_length' => 20000, 'required' => false)),
      'price'             => new sfValidatorNumber(array('required' => false)),
      'exchange'          => new sfValidatorNumber(array('required' => false)),
      'original_cost'     => new sfValidatorNumber(array('required' => false)),
      'freight'           => new sfValidatorNumber(array('required' => false)),
      'business_weight'   => new sfValidatorNumber(array('required' => false)),
      'weight'            => new sfValidatorNumber(array('required' => false)),
      'limits'            => new sfValidatorInteger(array('required' => false)),
      'content'           => new sfValidatorString(array('max_length' => 3000)),
      'business'          => new sfValidatorString(array('max_length' => 30)),
      'start_date'        => new sfValidatorInteger(array('required' => false)),
      'end_date'          => new sfValidatorInteger(array('required' => false)),
      'crawl_flag'        => new sfValidatorInteger(array('required' => false)),
      'show_flag'         => new sfValidatorInteger(array('required' => false)),
      'root_id'           => new sfValidatorInteger(array('required' => false)),
      'children_id'       => new sfValidatorInteger(array('required' => false)),
      'brand_id'          => new sfValidatorInteger(array('required' => false)),
      'hits'              => new sfValidatorInteger(array('required' => false)),
      'praise'            => new sfValidatorInteger(array('required' => false)),
      'external_username' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'last_crawl_date'   => new sfValidatorInteger(array('required' => false)),
      'display'           => new sfValidatorInteger(array('required' => false)),
      'status'            => new sfValidatorInteger(array('required' => false)),
      'author_id'         => new sfValidatorInteger(array('required' => false)),
      'editor_id'         => new sfValidatorInteger(array('required' => false)),
      'publish_date'      => new sfValidatorDateTime(),
      'purchase_flag'     => new sfValidatorInteger(array('required' => false)),
      'purchase_uid'      => new sfValidatorInteger(array('required' => false)),
      'purchase_msg'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'purchase_date'     => new sfValidatorInteger(array('required' => false)),
      'dace_hits'         => new sfValidatorInteger(array('required' => false)),
      'dace_buy_hits'     => new sfValidatorInteger(array('required' => false)),
      'comment_count'     => new sfValidatorInteger(array('required' => false)),
      'comment_count_img' => new sfValidatorInteger(array('required' => false)),
      'tags_attr'         => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'discount'          => new sfValidatorNumber(array('required' => false)),
      'discount_endtime'  => new sfValidatorDateTime(array('required' => false)),
      'collect_count'     => new sfValidatorInteger(array('required' => false)),
      'shaiwu_count'      => new sfValidatorInteger(array('required' => false)),
      'commodity'         => new sfValidatorString(array('max_length' => 511, 'required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_product_attr[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdProductAttr';
  }

}
