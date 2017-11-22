<?php

/**
 * KaluliItemSku filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKaluliItemSkuFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'item_id'        => new sfWidgetFormFilterInput(),
      'code'           => new sfWidgetFormFilterInput(),
      'goods_no'       => new sfWidgetFormFilterInput(),
      'attr'           => new sfWidgetFormFilterInput(),
      'ware_sku'       => new sfWidgetFormFilterInput(),
      'price'          => new sfWidgetFormFilterInput(),
      'discount_price' => new sfWidgetFormFilterInput(),
      'weight'         => new sfWidgetFormFilterInput(),
      'pic'            => new sfWidgetFormFilterInput(),
      'total_num'      => new sfWidgetFormFilterInput(),
      'lock_num'       => new sfWidgetFormFilterInput(),
      'storehouse_id'  => new sfWidgetFormFilterInput(),
      'status'         => new sfWidgetFormFilterInput(),
      'wupdate_time'   => new sfWidgetFormFilterInput(),
      'sort'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'item_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'code'           => new sfValidatorPass(array('required' => false)),
      'goods_no'       => new sfValidatorPass(array('required' => false)),
      'attr'           => new sfValidatorPass(array('required' => false)),
      'ware_sku'       => new sfValidatorPass(array('required' => false)),
      'price'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'discount_price' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'weight'         => new sfValidatorPass(array('required' => false)),
      'pic'            => new sfValidatorPass(array('required' => false)),
      'total_num'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lock_num'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'storehouse_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'wupdate_time'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sort'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kaluli_item_sku_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliItemSku';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'item_id'        => 'Number',
      'code'           => 'Text',
      'goods_no'       => 'Text',
      'attr'           => 'Text',
      'ware_sku'       => 'Text',
      'price'          => 'Number',
      'discount_price' => 'Number',
      'weight'         => 'Text',
      'pic'            => 'Text',
      'total_num'      => 'Number',
      'lock_num'       => 'Number',
      'storehouse_id'  => 'Number',
      'status'         => 'Number',
      'wupdate_time'   => 'Number',
      'sort'           => 'Number',
    );
  }
}
