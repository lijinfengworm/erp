<?php

/**
 * TrdGoodsSupplierComment filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsSupplierCommentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'goods_id'      => new sfWidgetFormFilterInput(),
      'supplier_id'   => new sfWidgetFormFilterInput(),
      'supplier_name' => new sfWidgetFormFilterInput(),
      'supplier_url'  => new sfWidgetFormFilterInput(),
      'nickname'      => new sfWidgetFormFilterInput(),
      'content'       => new sfWidgetFormFilterInput(),
      'img_attr'      => new sfWidgetFormFilterInput(),
      'info'          => new sfWidgetFormFilterInput(),
      'type'          => new sfWidgetFormFilterInput(),
      'status'        => new sfWidgetFormFilterInput(),
      'unique_id'     => new sfWidgetFormFilterInput(),
      'sku'           => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'goods_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'supplier_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'supplier_name' => new sfValidatorPass(array('required' => false)),
      'supplier_url'  => new sfValidatorPass(array('required' => false)),
      'nickname'      => new sfValidatorPass(array('required' => false)),
      'content'       => new sfValidatorPass(array('required' => false)),
      'img_attr'      => new sfValidatorPass(array('required' => false)),
      'info'          => new sfValidatorPass(array('required' => false)),
      'type'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'unique_id'     => new sfValidatorPass(array('required' => false)),
      'sku'           => new sfValidatorPass(array('required' => false)),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_supplier_comment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsSupplierComment';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'goods_id'      => 'Number',
      'supplier_id'   => 'Number',
      'supplier_name' => 'Text',
      'supplier_url'  => 'Text',
      'nickname'      => 'Text',
      'content'       => 'Text',
      'img_attr'      => 'Text',
      'info'          => 'Text',
      'type'          => 'Number',
      'status'        => 'Number',
      'unique_id'     => 'Text',
      'sku'           => 'Text',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
