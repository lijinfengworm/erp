<?php

/**
 * TrdItem filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdItemFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'brand_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Brand'), 'add_empty' => true)),
      'shop_id'        => new sfWidgetFormFilterInput(),
      'item_id'        => new sfWidgetFormFilterInput(),
      'name'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'memo'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'title'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'url'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'price'          => new sfWidgetFormFilterInput(),
      'original_price' => new sfWidgetFormFilterInput(),
      'freight_payer'  => new sfWidgetFormFilterInput(),
      'img_url'        => new sfWidgetFormFilterInput(),
      'item_no'        => new sfWidgetFormFilterInput(),
      'size_ids'       => new sfWidgetFormFilterInput(),
      'category_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'add_empty' => true)),
      'style_ids'      => new sfWidgetFormFilterInput(),
      'color_ids'      => new sfWidgetFormFilterInput(),
      'sold_count'     => new sfWidgetFormFilterInput(),
      'is_soldout'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'click_count'    => new sfWidgetFormFilterInput(),
      'like_count'     => new sfWidgetFormFilterInput(),
      'rank'           => new sfWidgetFormFilterInput(),
      'is_hide'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_verified'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'give_money'     => new sfWidgetFormFilterInput(),
      'baoliao_id'     => new sfWidgetFormFilterInput(),
      'mart'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_recommend'   => new sfWidgetFormFilterInput(),
      'publish_date'   => new sfWidgetFormFilterInput(),
      'hupu_uid'       => new sfWidgetFormFilterInput(),
      'hupu_username'  => new sfWidgetFormFilterInput(),
      'status'         => new sfWidgetFormFilterInput(),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'brand_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Brand'), 'column' => 'id')),
      'shop_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'item_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'           => new sfValidatorPass(array('required' => false)),
      'memo'           => new sfValidatorPass(array('required' => false)),
      'title'          => new sfValidatorPass(array('required' => false)),
      'url'            => new sfValidatorPass(array('required' => false)),
      'price'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'original_price' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'freight_payer'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'img_url'        => new sfValidatorPass(array('required' => false)),
      'item_no'        => new sfValidatorPass(array('required' => false)),
      'size_ids'       => new sfValidatorPass(array('required' => false)),
      'category_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Category'), 'column' => 'id')),
      'style_ids'      => new sfValidatorPass(array('required' => false)),
      'color_ids'      => new sfValidatorPass(array('required' => false)),
      'sold_count'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_soldout'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'click_count'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'like_count'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rank'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_hide'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_verified'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'give_money'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'baoliao_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'mart'           => new sfValidatorPass(array('required' => false)),
      'is_recommend'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'publish_date'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_uid'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username'  => new sfValidatorPass(array('required' => false)),
      'status'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_item_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdItem';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'brand_id'       => 'ForeignKey',
      'shop_id'        => 'Number',
      'item_id'        => 'Number',
      'name'           => 'Text',
      'memo'           => 'Text',
      'title'          => 'Text',
      'url'            => 'Text',
      'price'          => 'Number',
      'original_price' => 'Number',
      'freight_payer'  => 'Number',
      'img_url'        => 'Text',
      'item_no'        => 'Text',
      'size_ids'       => 'Text',
      'category_id'    => 'ForeignKey',
      'style_ids'      => 'Text',
      'color_ids'      => 'Text',
      'sold_count'     => 'Number',
      'is_soldout'     => 'Boolean',
      'click_count'    => 'Number',
      'like_count'     => 'Number',
      'rank'           => 'Number',
      'is_hide'        => 'Boolean',
      'is_verified'    => 'Boolean',
      'give_money'     => 'Number',
      'baoliao_id'     => 'Number',
      'mart'           => 'Text',
      'is_recommend'   => 'Number',
      'publish_date'   => 'Number',
      'hupu_uid'       => 'Number',
      'hupu_username'  => 'Text',
      'status'         => 'Number',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
