<?php

/**
 * KllOrder filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number'       => new sfWidgetFormFilterInput(),
      'child_order_number' => new sfWidgetFormFilterInput(),
      'name'               => new sfWidgetFormFilterInput(),
      'description'        => new sfWidgetFormFilterInput(),
      'receiver'           => new sfWidgetFormFilterInput(),
      'product_code'       => new sfWidgetFormFilterInput(),
      'product_id'         => new sfWidgetFormFilterInput(),
      'goods_id'           => new sfWidgetFormFilterInput(),
      'total_price'        => new sfWidgetFormFilterInput(),
      'pay_time'           => new sfWidgetFormFilterInput(),
      'pay_status'         => new sfWidgetFormFilterInput(),
      'price'              => new sfWidgetFormFilterInput(),
      'number'             => new sfWidgetFormFilterInput(),
      'update_time'        => new sfWidgetFormFilterInput(),
      'creat_time'         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'order_number'       => new sfValidatorPass(array('required' => false)),
      'child_order_number' => new sfValidatorPass(array('required' => false)),
      'name'               => new sfValidatorPass(array('required' => false)),
      'description'        => new sfValidatorPass(array('required' => false)),
      'receiver'           => new sfValidatorPass(array('required' => false)),
      'product_code'       => new sfValidatorPass(array('required' => false)),
      'product_id'         => new sfValidatorPass(array('required' => false)),
      'goods_id'           => new sfValidatorPass(array('required' => false)),
      'total_price'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'pay_time'           => new sfValidatorPass(array('required' => false)),
      'pay_status'         => new sfValidatorPass(array('required' => false)),
      'price'              => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'number'             => new sfValidatorPass(array('required' => false)),
      'update_time'        => new sfValidatorPass(array('required' => false)),
      'creat_time'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllOrder';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Text',
      'order_number'       => 'Text',
      'child_order_number' => 'Text',
      'name'               => 'Text',
      'description'        => 'Text',
      'receiver'           => 'Text',
      'product_code'       => 'Text',
      'product_id'         => 'Text',
      'goods_id'           => 'Text',
      'total_price'        => 'Number',
      'pay_time'           => 'Text',
      'pay_status'         => 'Text',
      'price'              => 'Number',
      'number'             => 'Text',
      'update_time'        => 'Text',
      'creat_time'         => 'Number',
    );
  }
}
