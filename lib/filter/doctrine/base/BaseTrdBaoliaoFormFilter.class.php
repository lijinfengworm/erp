<?php

/**
 * TrdBaoliao filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdBaoliaoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'brand_id'          => new sfWidgetFormFilterInput(),
      'brand'             => new sfWidgetFormFilterInput(),
      'item_num'          => new sfWidgetFormFilterInput(),
      'is_showsports'     => new sfWidgetFormFilterInput(),
      'shop_id'           => new sfWidgetFormFilterInput(),
      'item_id'           => new sfWidgetFormFilterInput(),
      'name'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sub_name'          => new sfWidgetFormFilterInput(),
      'memo'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'       => new sfWidgetFormFilterInput(),
      'url'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'encrypt_url'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'price'             => new sfWidgetFormFilterInput(),
      'original_price'    => new sfWidgetFormFilterInput(),
      'mart'              => new sfWidgetFormFilterInput(),
      'sold_count'        => new sfWidgetFormFilterInput(),
      'is_soldout'        => new sfWidgetFormFilterInput(),
      'img_url'           => new sfWidgetFormFilterInput(),
      'give_money'        => new sfWidgetFormFilterInput(),
      'category_id'       => new sfWidgetFormFilterInput(),
      'type'              => new sfWidgetFormFilterInput(),
      'root_id'           => new sfWidgetFormFilterInput(),
      'children_id'       => new sfWidgetFormFilterInput(),
      'attr_collect'      => new sfWidgetFormFilterInput(),
      'pic_collect'       => new sfWidgetFormFilterInput(),
      'tag_collect'       => new sfWidgetFormFilterInput(),
      'model_id'          => new sfWidgetFormFilterInput(),
      'external_username' => new sfWidgetFormFilterInput(),
      'publish_date'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'hupu_uid'          => new sfWidgetFormFilterInput(),
      'hupu_username'     => new sfWidgetFormFilterInput(),
      'status'            => new sfWidgetFormFilterInput(),
      'commodity'         => new sfWidgetFormFilterInput(),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'brand_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'brand'             => new sfValidatorPass(array('required' => false)),
      'item_num'          => new sfValidatorPass(array('required' => false)),
      'is_showsports'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'shop_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'item_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'              => new sfValidatorPass(array('required' => false)),
      'sub_name'          => new sfValidatorPass(array('required' => false)),
      'memo'              => new sfValidatorPass(array('required' => false)),
      'description'       => new sfValidatorPass(array('required' => false)),
      'url'               => new sfValidatorPass(array('required' => false)),
      'encrypt_url'       => new sfValidatorPass(array('required' => false)),
      'price'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'original_price'    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'mart'              => new sfValidatorPass(array('required' => false)),
      'sold_count'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_soldout'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'img_url'           => new sfValidatorPass(array('required' => false)),
      'give_money'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'category_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'root_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'children_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'attr_collect'      => new sfValidatorPass(array('required' => false)),
      'pic_collect'       => new sfValidatorPass(array('required' => false)),
      'tag_collect'       => new sfValidatorPass(array('required' => false)),
      'model_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'external_username' => new sfValidatorPass(array('required' => false)),
      'publish_date'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'hupu_uid'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username'     => new sfValidatorPass(array('required' => false)),
      'status'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'commodity'         => new sfValidatorPass(array('required' => false)),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_baoliao_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdBaoliao';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'brand_id'          => 'Number',
      'brand'             => 'Text',
      'item_num'          => 'Text',
      'is_showsports'     => 'Number',
      'shop_id'           => 'Number',
      'item_id'           => 'Number',
      'name'              => 'Text',
      'sub_name'          => 'Text',
      'memo'              => 'Text',
      'description'       => 'Text',
      'url'               => 'Text',
      'encrypt_url'       => 'Text',
      'price'             => 'Number',
      'original_price'    => 'Number',
      'mart'              => 'Text',
      'sold_count'        => 'Number',
      'is_soldout'        => 'Number',
      'img_url'           => 'Text',
      'give_money'        => 'Number',
      'category_id'       => 'Number',
      'type'              => 'Number',
      'root_id'           => 'Number',
      'children_id'       => 'Number',
      'attr_collect'      => 'Text',
      'pic_collect'       => 'Text',
      'tag_collect'       => 'Text',
      'model_id'          => 'Number',
      'external_username' => 'Text',
      'publish_date'      => 'Date',
      'hupu_uid'          => 'Number',
      'hupu_username'     => 'Text',
      'status'            => 'Number',
      'commodity'         => 'Text',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
    );
  }
}
