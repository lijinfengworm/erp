<?php

/**
 * TrdGoods filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'              => new sfWidgetFormFilterInput(),
      'code'              => new sfWidgetFormFilterInput(),
      'root_brand_id'     => new sfWidgetFormFilterInput(),
      'child_brand_id'    => new sfWidgetFormFilterInput(),
      'root_category_id'  => new sfWidgetFormFilterInput(),
      'child_category_id' => new sfWidgetFormFilterInput(),
      'type'              => new sfWidgetFormFilterInput(),
      'from_type'         => new sfWidgetFormFilterInput(),
      'from_id'           => new sfWidgetFormFilterInput(),
      'pic'               => new sfWidgetFormFilterInput(),
      'supplier_count'    => new sfWidgetFormFilterInput(),
      'status'            => new sfWidgetFormFilterInput(),
      'comment'           => new sfWidgetFormFilterInput(),
      'admin_id'          => new sfWidgetFormFilterInput(),
      'is_delete'         => new sfWidgetFormFilterInput(),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'name'              => new sfValidatorPass(array('required' => false)),
      'code'              => new sfValidatorPass(array('required' => false)),
      'root_brand_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'child_brand_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'root_category_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'child_category_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'              => new sfValidatorPass(array('required' => false)),
      'from_type'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'from_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pic'               => new sfValidatorPass(array('required' => false)),
      'supplier_count'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comment'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'admin_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_delete'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoods';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'name'              => 'Text',
      'code'              => 'Text',
      'root_brand_id'     => 'Number',
      'child_brand_id'    => 'Number',
      'root_category_id'  => 'Number',
      'child_category_id' => 'Number',
      'type'              => 'Text',
      'from_type'         => 'Number',
      'from_id'           => 'Number',
      'pic'               => 'Text',
      'supplier_count'    => 'Number',
      'status'            => 'Number',
      'comment'           => 'Number',
      'admin_id'          => 'Number',
      'is_delete'         => 'Number',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
    );
  }
}
