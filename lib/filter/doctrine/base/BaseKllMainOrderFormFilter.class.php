<?php

/**
 * KllMainOrder filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllMainOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number'        => new sfWidgetFormFilterInput(),
      'origin_order_number' => new sfWidgetFormFilterInput(),
      'total_price'         => new sfWidgetFormFilterInput(),
      'push_price'          => new sfWidgetFormFilterInput(),
      'real_price'          => new sfWidgetFormFilterInput(),
      'express_fee'         => new sfWidgetFormFilterInput(),
      'duty_fee'            => new sfWidgetFormFilterInput(),
      'coupon_fee'          => new sfWidgetFormFilterInput(),
      'pay_status'          => new sfWidgetFormFilterInput(),
      'pay_type'            => new sfWidgetFormFilterInput(),
      'pay_time'            => new sfWidgetFormFilterInput(),
      'uid'                 => new sfWidgetFormFilterInput(),
      'status'              => new sfWidgetFormFilterInput(),
      'logistic_type'       => new sfWidgetFormFilterInput(),
      'logistic_number'     => new sfWidgetFormFilterInput(),
      'flow_number'         => new sfWidgetFormFilterInput(),
      'payer'               => new sfWidgetFormFilterInput(),
      'source'              => new sfWidgetFormFilterInput(),
      'count'               => new sfWidgetFormFilterInput(),
      'audit_time'          => new sfWidgetFormFilterInput(),
      'creat_time'          => new sfWidgetFormFilterInput(),
      'update_time'         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'order_number'        => new sfValidatorPass(array('required' => false)),
      'origin_order_number' => new sfValidatorPass(array('required' => false)),
      'total_price'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'push_price'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'real_price'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'express_fee'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'duty_fee'            => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'coupon_fee'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'pay_status'          => new sfValidatorPass(array('required' => false)),
      'pay_type'            => new sfValidatorPass(array('required' => false)),
      'pay_time'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'uid'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'              => new sfValidatorPass(array('required' => false)),
      'logistic_type'       => new sfValidatorPass(array('required' => false)),
      'logistic_number'     => new sfValidatorPass(array('required' => false)),
      'flow_number'         => new sfValidatorPass(array('required' => false)),
      'payer'               => new sfValidatorPass(array('required' => false)),
      'source'              => new sfValidatorPass(array('required' => false)),
      'count'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'audit_time'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'creat_time'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'update_time'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_main_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllMainOrder';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'order_number'        => 'Text',
      'origin_order_number' => 'Text',
      'total_price'         => 'Number',
      'push_price'          => 'Number',
      'real_price'          => 'Number',
      'express_fee'         => 'Number',
      'duty_fee'            => 'Number',
      'coupon_fee'          => 'Number',
      'pay_status'          => 'Text',
      'pay_type'            => 'Text',
      'pay_time'            => 'Number',
      'uid'                 => 'Number',
      'status'              => 'Text',
      'logistic_type'       => 'Text',
      'logistic_number'     => 'Text',
      'flow_number'         => 'Text',
      'payer'               => 'Text',
      'source'              => 'Text',
      'count'               => 'Number',
      'audit_time'          => 'Number',
      'creat_time'          => 'Number',
      'update_time'         => 'Number',
    );
  }
}
