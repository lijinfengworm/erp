<?php

/**
 * TrdProductAttr filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdProductAttrFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'news_id'           => new sfWidgetFormFilterInput(),
      'goods_id'          => new sfWidgetFormFilterInput(),
      'title'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'url'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'img_path'          => new sfWidgetFormFilterInput(),
      'intro'             => new sfWidgetFormFilterInput(),
      'memo'              => new sfWidgetFormFilterInput(),
      'price'             => new sfWidgetFormFilterInput(),
      'exchange'          => new sfWidgetFormFilterInput(),
      'original_cost'     => new sfWidgetFormFilterInput(),
      'freight'           => new sfWidgetFormFilterInput(),
      'business_weight'   => new sfWidgetFormFilterInput(),
      'weight'            => new sfWidgetFormFilterInput(),
      'limits'            => new sfWidgetFormFilterInput(),
      'content'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'business'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'start_date'        => new sfWidgetFormFilterInput(),
      'end_date'          => new sfWidgetFormFilterInput(),
      'crawl_flag'        => new sfWidgetFormFilterInput(),
      'show_flag'         => new sfWidgetFormFilterInput(),
      'root_id'           => new sfWidgetFormFilterInput(),
      'children_id'       => new sfWidgetFormFilterInput(),
      'brand_id'          => new sfWidgetFormFilterInput(),
      'hits'              => new sfWidgetFormFilterInput(),
      'praise'            => new sfWidgetFormFilterInput(),
      'external_username' => new sfWidgetFormFilterInput(),
      'last_crawl_date'   => new sfWidgetFormFilterInput(),
      'display'           => new sfWidgetFormFilterInput(),
      'status'            => new sfWidgetFormFilterInput(),
      'author_id'         => new sfWidgetFormFilterInput(),
      'editor_id'         => new sfWidgetFormFilterInput(),
      'publish_date'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'purchase_flag'     => new sfWidgetFormFilterInput(),
      'purchase_uid'      => new sfWidgetFormFilterInput(),
      'purchase_msg'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'purchase_date'     => new sfWidgetFormFilterInput(),
      'dace_hits'         => new sfWidgetFormFilterInput(),
      'dace_buy_hits'     => new sfWidgetFormFilterInput(),
      'comment_count'     => new sfWidgetFormFilterInput(),
      'comment_count_img' => new sfWidgetFormFilterInput(),
      'tags_attr'         => new sfWidgetFormFilterInput(),
      'discount'          => new sfWidgetFormFilterInput(),
      'discount_endtime'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'collect_count'     => new sfWidgetFormFilterInput(),
      'shaiwu_count'      => new sfWidgetFormFilterInput(),
      'commodity'         => new sfWidgetFormFilterInput(),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'news_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'goods_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'             => new sfValidatorPass(array('required' => false)),
      'name'              => new sfValidatorPass(array('required' => false)),
      'url'               => new sfValidatorPass(array('required' => false)),
      'img_path'          => new sfValidatorPass(array('required' => false)),
      'intro'             => new sfValidatorPass(array('required' => false)),
      'memo'              => new sfValidatorPass(array('required' => false)),
      'price'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'exchange'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'original_cost'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'freight'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'business_weight'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'weight'            => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'limits'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'content'           => new sfValidatorPass(array('required' => false)),
      'business'          => new sfValidatorPass(array('required' => false)),
      'start_date'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'end_date'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'crawl_flag'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'show_flag'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'root_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'children_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'brand_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hits'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'praise'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'external_username' => new sfValidatorPass(array('required' => false)),
      'last_crawl_date'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'display'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'author_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'editor_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'publish_date'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'purchase_flag'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'purchase_uid'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'purchase_msg'      => new sfValidatorPass(array('required' => false)),
      'purchase_date'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'dace_hits'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'dace_buy_hits'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comment_count'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comment_count_img' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tags_attr'         => new sfValidatorPass(array('required' => false)),
      'discount'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'discount_endtime'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'collect_count'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'shaiwu_count'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'commodity'         => new sfValidatorPass(array('required' => false)),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_product_attr_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdProductAttr';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'news_id'           => 'Number',
      'goods_id'          => 'Number',
      'title'             => 'Text',
      'name'              => 'Text',
      'url'               => 'Text',
      'img_path'          => 'Text',
      'intro'             => 'Text',
      'memo'              => 'Text',
      'price'             => 'Number',
      'exchange'          => 'Number',
      'original_cost'     => 'Number',
      'freight'           => 'Number',
      'business_weight'   => 'Number',
      'weight'            => 'Number',
      'limits'            => 'Number',
      'content'           => 'Text',
      'business'          => 'Text',
      'start_date'        => 'Number',
      'end_date'          => 'Number',
      'crawl_flag'        => 'Number',
      'show_flag'         => 'Number',
      'root_id'           => 'Number',
      'children_id'       => 'Number',
      'brand_id'          => 'Number',
      'hits'              => 'Number',
      'praise'            => 'Number',
      'external_username' => 'Text',
      'last_crawl_date'   => 'Number',
      'display'           => 'Number',
      'status'            => 'Number',
      'author_id'         => 'Number',
      'editor_id'         => 'Number',
      'publish_date'      => 'Date',
      'purchase_flag'     => 'Number',
      'purchase_uid'      => 'Number',
      'purchase_msg'      => 'Text',
      'purchase_date'     => 'Number',
      'dace_hits'         => 'Number',
      'dace_buy_hits'     => 'Number',
      'comment_count'     => 'Number',
      'comment_count_img' => 'Number',
      'tags_attr'         => 'Text',
      'discount'          => 'Number',
      'discount_endtime'  => 'Date',
      'collect_count'     => 'Number',
      'shaiwu_count'      => 'Number',
      'commodity'         => 'Text',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
    );
  }
}
