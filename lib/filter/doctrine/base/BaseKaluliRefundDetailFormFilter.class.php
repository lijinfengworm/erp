<?php

/**
 * KaluliRefundDetail filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKaluliRefundDetailFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number'    => new sfWidgetFormFilterInput(),
      'order_id'        => new sfWidgetFormFilterInput(),
      'ibilling_number' => new sfWidgetFormFilterInput(),
      'refund'          => new sfWidgetFormFilterInput(),
      'refund_remark'   => new sfWidgetFormFilterInput(),
      'type'            => new sfWidgetFormFilterInput(),
      'pay_type'        => new sfWidgetFormFilterInput(),
      'status'          => new sfWidgetFormFilterInput(),
      'grant_uid'       => new sfWidgetFormFilterInput(),
      'grant_username'  => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'order_number'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'order_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ibilling_number' => new sfValidatorPass(array('required' => false)),
      'refund'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'refund_remark'   => new sfValidatorPass(array('required' => false)),
      'type'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pay_type'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'grant_uid'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'grant_username'  => new sfValidatorPass(array('required' => false)),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kaluli_refund_detail_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliRefundDetail';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'order_number'    => 'Number',
      'order_id'        => 'Number',
      'ibilling_number' => 'Text',
      'refund'          => 'Number',
      'refund_remark'   => 'Text',
      'type'            => 'Number',
      'pay_type'        => 'Number',
      'status'          => 'Number',
      'grant_uid'       => 'Number',
      'grant_username'  => 'Text',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
