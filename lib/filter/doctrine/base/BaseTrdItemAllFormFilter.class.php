<?php

/**
 * TrdItemAll filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdItemAllFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'shop_id'         => new sfWidgetFormFilterInput(),
      'item_id'         => new sfWidgetFormFilterInput(),
      'shoe_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TrdItem'), 'add_empty' => true)),
      'memo'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'     => new sfWidgetFormFilterInput(),
      'title'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'url'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'price'           => new sfWidgetFormFilterInput(),
      'original_price'  => new sfWidgetFormFilterInput(),
      'freight_payer'   => new sfWidgetFormFilterInput(),
      'img_url'         => new sfWidgetFormFilterInput(),
      'item_no'         => new sfWidgetFormFilterInput(),
      'category_all_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'add_empty' => true)),
      'sold_count'      => new sfWidgetFormFilterInput(),
      'is_soldout'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'click_count'     => new sfWidgetFormFilterInput(),
      'like_count'      => new sfWidgetFormFilterInput(),
      'rank'            => new sfWidgetFormFilterInput(),
      'is_hide'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'give_money'      => new sfWidgetFormFilterInput(),
      'baoliao_id'      => new sfWidgetFormFilterInput(),
      'mart'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_recommend'    => new sfWidgetFormFilterInput(),
      'publish_date'    => new sfWidgetFormFilterInput(),
      'hupu_uid'        => new sfWidgetFormFilterInput(),
      'hupu_username'   => new sfWidgetFormFilterInput(),
      'root_id'         => new sfWidgetFormFilterInput(),
      'children_id'     => new sfWidgetFormFilterInput(),
      'attr_collect'    => new sfWidgetFormFilterInput(),
      'heat'            => new sfWidgetFormFilterInput(),
      'praise'          => new sfWidgetFormFilterInput(),
      'model_id'        => new sfWidgetFormFilterInput(),
      'tag_collect'     => new sfWidgetFormFilterInput(),
      'pic_collect'     => new sfWidgetFormFilterInput(),
      'height'          => new sfWidgetFormFilterInput(),
      'width'           => new sfWidgetFormFilterInput(),
      'status'          => new sfWidgetFormFilterInput(),
      'brand'           => new sfWidgetFormFilterInput(),
      'item_num'        => new sfWidgetFormFilterInput(),
      'is_showsports'   => new sfWidgetFormFilterInput(),
      'commodity'       => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'shop_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'item_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'shoe_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('TrdItem'), 'column' => 'id')),
      'memo'            => new sfValidatorPass(array('required' => false)),
      'description'     => new sfValidatorPass(array('required' => false)),
      'title'           => new sfValidatorPass(array('required' => false)),
      'name'            => new sfValidatorPass(array('required' => false)),
      'url'             => new sfValidatorPass(array('required' => false)),
      'price'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'original_price'  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'freight_payer'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'img_url'         => new sfValidatorPass(array('required' => false)),
      'item_no'         => new sfValidatorPass(array('required' => false)),
      'category_all_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Category'), 'column' => 'id')),
      'sold_count'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_soldout'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'click_count'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'like_count'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rank'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_hide'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'give_money'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'baoliao_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'mart'            => new sfValidatorPass(array('required' => false)),
      'is_recommend'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'publish_date'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_uid'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username'   => new sfValidatorPass(array('required' => false)),
      'root_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'children_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'attr_collect'    => new sfValidatorPass(array('required' => false)),
      'heat'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'praise'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'model_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tag_collect'     => new sfValidatorPass(array('required' => false)),
      'pic_collect'     => new sfValidatorPass(array('required' => false)),
      'height'          => new sfValidatorPass(array('required' => false)),
      'width'           => new sfValidatorPass(array('required' => false)),
      'status'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'brand'           => new sfValidatorPass(array('required' => false)),
      'item_num'        => new sfValidatorPass(array('required' => false)),
      'is_showsports'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'commodity'       => new sfValidatorPass(array('required' => false)),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_item_all_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdItemAll';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'shop_id'         => 'Number',
      'item_id'         => 'Number',
      'shoe_id'         => 'ForeignKey',
      'memo'            => 'Text',
      'description'     => 'Text',
      'title'           => 'Text',
      'name'            => 'Text',
      'url'             => 'Text',
      'price'           => 'Number',
      'original_price'  => 'Number',
      'freight_payer'   => 'Number',
      'img_url'         => 'Text',
      'item_no'         => 'Text',
      'category_all_id' => 'ForeignKey',
      'sold_count'      => 'Number',
      'is_soldout'      => 'Boolean',
      'click_count'     => 'Number',
      'like_count'      => 'Number',
      'rank'            => 'Number',
      'is_hide'         => 'Boolean',
      'give_money'      => 'Number',
      'baoliao_id'      => 'Number',
      'mart'            => 'Text',
      'is_recommend'    => 'Number',
      'publish_date'    => 'Number',
      'hupu_uid'        => 'Number',
      'hupu_username'   => 'Text',
      'root_id'         => 'Number',
      'children_id'     => 'Number',
      'attr_collect'    => 'Text',
      'heat'            => 'Number',
      'praise'          => 'Number',
      'model_id'        => 'Number',
      'tag_collect'     => 'Text',
      'pic_collect'     => 'Text',
      'height'          => 'Text',
      'width'           => 'Text',
      'status'          => 'Number',
      'brand'           => 'Text',
      'item_num'        => 'Text',
      'is_showsports'   => 'Number',
      'commodity'       => 'Text',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
