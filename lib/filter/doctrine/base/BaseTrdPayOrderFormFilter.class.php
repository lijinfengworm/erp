<?php

/**
 * TrdPayOrder filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdPayOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'sign'               => new sfWidgetFormFilterInput(),
      'pay_uid'            => new sfWidgetFormFilterInput(),
      'title'              => new sfWidgetFormFilterInput(),
      'desc'               => new sfWidgetFormFilterInput(),
      'amount'             => new sfWidgetFormFilterInput(),
      'callback_url'       => new sfWidgetFormFilterInput(),
      'notify_url'         => new sfWidgetFormFilterInput(),
      'yzm'                => new sfWidgetFormFilterInput(),
      'order_no'           => new sfWidgetFormFilterInput(),
      'pay_type'           => new sfWidgetFormFilterInput(),
      'is_pay'             => new sfWidgetFormFilterInput(),
      'callback_error_num' => new sfWidgetFormFilterInput(),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'sign'               => new sfValidatorPass(array('required' => false)),
      'pay_uid'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'              => new sfValidatorPass(array('required' => false)),
      'desc'               => new sfValidatorPass(array('required' => false)),
      'amount'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'callback_url'       => new sfValidatorPass(array('required' => false)),
      'notify_url'         => new sfValidatorPass(array('required' => false)),
      'yzm'                => new sfValidatorPass(array('required' => false)),
      'order_no'           => new sfValidatorPass(array('required' => false)),
      'pay_type'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_pay'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'callback_error_num' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_pay_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdPayOrder';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'sign'               => 'Text',
      'pay_uid'            => 'Number',
      'title'              => 'Text',
      'desc'               => 'Text',
      'amount'             => 'Number',
      'callback_url'       => 'Text',
      'notify_url'         => 'Text',
      'yzm'                => 'Text',
      'order_no'           => 'Text',
      'pay_type'           => 'Number',
      'is_pay'             => 'Number',
      'callback_error_num' => 'Number',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
    );
  }
}
