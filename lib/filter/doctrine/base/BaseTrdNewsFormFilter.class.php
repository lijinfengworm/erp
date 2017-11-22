<?php

/**
 * TrdNews filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdNewsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'intro'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'title'                => new sfWidgetFormFilterInput(),
      'text'                 => new sfWidgetFormFilterInput(),
      'orginal_url'          => new sfWidgetFormFilterInput(),
      'orginal_type'         => new sfWidgetFormFilterInput(),
      'product_id'           => new sfWidgetFormFilterInput(),
      'product_start_date'   => new sfWidgetFormFilterInput(),
      'product_end_date'     => new sfWidgetFormFilterInput(),
      'publish_date'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'price'                => new sfWidgetFormFilterInput(),
      'is_delete'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'hits'                 => new sfWidgetFormFilterInput(),
      'reply_count'          => new sfWidgetFormFilterInput(),
      'light_count'          => new sfWidgetFormFilterInput(),
      'last_reply_date'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'img_attr'             => new sfWidgetFormFilterInput(),
      'img_link'             => new sfWidgetFormFilterInput(),
      'img_path'             => new sfWidgetFormFilterInput(),
      'img_tail'             => new sfWidgetFormFilterInput(),
      'author_id'            => new sfWidgetFormFilterInput(),
      'editor_id'            => new sfWidgetFormFilterInput(),
      'show_intro'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'subtitle'             => new sfWidgetFormFilterInput(),
      'spreadtitle'          => new sfWidgetFormFilterInput(),
      'direct_words'         => new sfWidgetFormFilterInput(),
      'goods_state'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'support'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'against'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'praise'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'shoe_id'              => new sfWidgetFormFilterInput(),
      'item_all_id'          => new sfWidgetFormFilterInput(),
      'baoliao_id'           => new sfWidgetFormFilterInput(),
      'root_type'            => new sfWidgetFormFilterInput(),
      'root_id'              => new sfWidgetFormFilterInput(),
      'children_id'          => new sfWidgetFormFilterInput(),
      'type'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'height'               => new sfWidgetFormFilterInput(),
      'width'                => new sfWidgetFormFilterInput(),
      'store_id'             => new sfWidgetFormFilterInput(),
      'brand_id'             => new sfWidgetFormFilterInput(),
      'audit_user'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'audit_status'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'audit_message'        => new sfWidgetFormFilterInput(),
      'audit_date'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'timing_interval'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_display_index'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_shopping'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'attr'                 => new sfWidgetFormFilterInput(),
      'is_show_comment'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_show_buy_link'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'rank'                 => new sfWidgetFormFilterInput(),
      'revel_start_date'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'revel_end_date'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'commodity'            => new sfWidgetFormFilterInput(),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'trd_product_tag_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'TrdProductTag')),
    ));

    $this->setValidators(array(
      'intro'                => new sfValidatorPass(array('required' => false)),
      'title'                => new sfValidatorPass(array('required' => false)),
      'text'                 => new sfValidatorPass(array('required' => false)),
      'orginal_url'          => new sfValidatorPass(array('required' => false)),
      'orginal_type'         => new sfValidatorPass(array('required' => false)),
      'product_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'product_start_date'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'product_end_date'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'publish_date'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'price'                => new sfValidatorPass(array('required' => false)),
      'is_delete'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'hits'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reply_count'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'light_count'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'last_reply_date'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'img_attr'             => new sfValidatorPass(array('required' => false)),
      'img_link'             => new sfValidatorPass(array('required' => false)),
      'img_path'             => new sfValidatorPass(array('required' => false)),
      'img_tail'             => new sfValidatorPass(array('required' => false)),
      'author_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'editor_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'show_intro'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'subtitle'             => new sfValidatorPass(array('required' => false)),
      'spreadtitle'          => new sfValidatorPass(array('required' => false)),
      'direct_words'         => new sfValidatorPass(array('required' => false)),
      'goods_state'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'support'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'against'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'praise'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'shoe_id'              => new sfValidatorPass(array('required' => false)),
      'item_all_id'          => new sfValidatorPass(array('required' => false)),
      'baoliao_id'           => new sfValidatorPass(array('required' => false)),
      'root_type'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'root_id'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'children_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'height'               => new sfValidatorPass(array('required' => false)),
      'width'                => new sfValidatorPass(array('required' => false)),
      'store_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'brand_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'audit_user'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'audit_status'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'audit_message'        => new sfValidatorPass(array('required' => false)),
      'audit_date'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'timing_interval'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_display_index'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_shopping'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'attr'                 => new sfValidatorPass(array('required' => false)),
      'is_show_comment'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_show_buy_link'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rank'                 => new sfValidatorPass(array('required' => false)),
      'revel_start_date'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'revel_end_date'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'commodity'            => new sfValidatorPass(array('required' => false)),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'trd_product_tag_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'TrdProductTag', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_news_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function add
Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in /data/wwwroot/hupu/kaluli-erp-project/lib/util/sfToolkit.class.php on line 362

Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in /data/wwwroot/hupu/kaluli-erp-project/lib/util/sfToolkit.class.php on line 362
TrdProductTagListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.TrdNewsTag TrdNewsTag')
      ->andWhereIn('TrdNewsTag.trd_product_tag_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'TrdNews';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'intro'                => 'Text',
      'title'                => 'Text',
      'text'                 => 'Text',
      'orginal_url'          => 'Text',
      'orginal_type'         => 'Text',
      'product_id'           => 'Number',
      'product_start_date'   => 'Number',
      'product_end_date'     => 'Number',
      'publish_date'         => 'Date',
      'price'                => 'Text',
      'is_delete'            => 'Boolean',
      'hits'                 => 'Number',
      'reply_count'          => 'Number',
      'light_count'          => 'Number',
      'last_reply_date'      => 'Date',
      'img_attr'             => 'Text',
      'img_link'             => 'Text',
      'img_path'             => 'Text',
      'img_tail'             => 'Text',
      'author_id'            => 'Number',
      'editor_id'            => 'Number',
      'show_intro'           => 'Boolean',
      'subtitle'             => 'Text',
      'spreadtitle'          => 'Text',
      'direct_words'         => 'Text',
      'goods_state'          => 'Number',
      'support'              => 'Number',
      'against'              => 'Number',
      'praise'               => 'Number',
      'shoe_id'              => 'Text',
      'item_all_id'          => 'Text',
      'baoliao_id'           => 'Text',
      'root_type'            => 'Number',
      'root_id'              => 'Number',
      'children_id'          => 'Number',
      'type'                 => 'Number',
      'height'               => 'Text',
      'width'                => 'Text',
      'store_id'             => 'Number',
      'brand_id'             => 'Number',
      'audit_user'           => 'Number',
      'audit_status'         => 'Number',
      'audit_message'        => 'Text',
      'audit_date'           => 'Date',
      'timing_interval'      => 'Number',
      'is_display_index'     => 'Boolean',
      'is_shopping'          => 'Boolean',
      'attr'                 => 'Text',
      'is_show_comment'      => 'Number',
      'is_show_buy_link'     => 'Number',
      'rank'                 => 'Text',
      'revel_start_date'     => 'Date',
      'revel_end_date'       => 'Date',
      'commodity'            => 'Text',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
      'trd_product_tag_list' => 'ManyKey',
    );
  }
}
