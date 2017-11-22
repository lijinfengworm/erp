<?php

/**
 * KllErpSkuPrice filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllErpSkuPriceFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'goods_id'       => new sfWidgetFormFilterInput(),
      'sku_id'         => new sfWidgetFormFilterInput(),
      'code_num'       => new sfWidgetFormFilterInput(),
      'product_code'   => new sfWidgetFormFilterInput(),
      'goods_title'    => new sfWidgetFormFilterInput(),
      'channel'        => new sfWidgetFormFilterInput(),
      'depot'          => new sfWidgetFormFilterInput(),
      'standard_price' => new sfWidgetFormFilterInput(),
      'cost_price'     => new sfWidgetFormFilterInput(),
      'push_price'     => new sfWidgetFormFilterInput(),
      'add_user'       => new sfWidgetFormFilterInput(),
      'audit_user'     => new sfWidgetFormFilterInput(),
      'audit_status'   => new sfWidgetFormFilterInput(),
      'audit_time'     => new sfWidgetFormFilterInput(),
      'create_time'    => new sfWidgetFormFilterInput(),
      'update_time'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'goods_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sku_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'code_num'       => new sfValidatorPass(array('required' => false)),
      'product_code'   => new sfValidatorPass(array('required' => false)),
      'goods_title'    => new sfValidatorPass(array('required' => false)),
      'channel'        => new sfValidatorPass(array('required' => false)),
      'depot'          => new sfValidatorPass(array('required' => false)),
      'standard_price' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'cost_price'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'push_price'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'add_user'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'audit_user'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'audit_status'   => new sfValidatorPass(array('required' => false)),
      'audit_time'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'create_time'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'update_time'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_erp_sku_price_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllErpSkuPrice';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Text',
      'goods_id'       => 'Number',
      'sku_id'         => 'Number',
      'code_num'       => 'Text',
      'product_code'   => 'Text',
      'goods_title'    => 'Text',
      'channel'        => 'Text',
      'depot'          => 'Text',
      'standard_price' => 'Number',
      'cost_price'     => 'Number',
      'push_price'     => 'Number',
      'add_user'       => 'Number',
      'audit_user'     => 'Number',
      'audit_status'   => 'Text',
      'audit_time'     => 'Number',
      'create_time'    => 'Number',
      'update_time'    => 'Number',
    );
  }
}
