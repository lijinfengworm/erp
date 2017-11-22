<?php

/**
 * KllMq filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllMqFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'msg_type'       => new sfWidgetFormFilterInput(),
      'msg_id'         => new sfWidgetFormFilterInput(),
      'msg_channel'    => new sfWidgetFormFilterInput(),
      'msg_body'       => new sfWidgetFormFilterInput(),
      'msg_status'     => new sfWidgetFormFilterInput(),
      'msg_audit'      => new sfWidgetFormFilterInput(),
      'order_number'   => new sfWidgetFormFilterInput(),
      'billing_number' => new sfWidgetFormFilterInput(),
      'zt'             => new sfWidgetFormFilterInput(),
      'msg_time'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'msg_response'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'msg_type'       => new sfValidatorPass(array('required' => false)),
      'msg_id'         => new sfValidatorPass(array('required' => false)),
      'msg_channel'    => new sfValidatorPass(array('required' => false)),
      'msg_body'       => new sfValidatorPass(array('required' => false)),
      'msg_status'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'msg_audit'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'order_number'   => new sfValidatorPass(array('required' => false)),
      'billing_number' => new sfValidatorPass(array('required' => false)),
      'zt'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'msg_time'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'msg_response'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_mq_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllMq';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'msg_type'       => 'Text',
      'msg_id'         => 'Text',
      'msg_channel'    => 'Text',
      'msg_body'       => 'Text',
      'msg_status'     => 'Number',
      'msg_audit'      => 'Number',
      'order_number'   => 'Text',
      'billing_number' => 'Text',
      'zt'             => 'Number',
      'msg_time'       => 'Date',
      'msg_response'   => 'Text',
    );
  }
}
