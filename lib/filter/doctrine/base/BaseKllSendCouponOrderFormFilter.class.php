<?php

/**
 * KllSendCouponOrder filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllSendCouponOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'opt_uid'    => new sfWidgetFormFilterInput(),
      'title'      => new sfWidgetFormFilterInput(),
      'detail'     => new sfWidgetFormFilterInput(),
      'position'   => new sfWidgetFormFilterInput(),
      'record_id'  => new sfWidgetFormFilterInput(),
      'state'      => new sfWidgetFormFilterInput(),
      's_time'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'e_time'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'channel_id' => new sfWidgetFormFilterInput(),
      'type'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'opt_uid'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'      => new sfValidatorPass(array('required' => false)),
      'detail'     => new sfValidatorPass(array('required' => false)),
      'position'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'record_id'  => new sfValidatorPass(array('required' => false)),
      'state'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      's_time'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'e_time'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'channel_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_send_coupon_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllSendCouponOrder';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'opt_uid'    => 'Number',
      'title'      => 'Text',
      'detail'     => 'Text',
      'position'   => 'Number',
      'record_id'  => 'Text',
      'state'      => 'Number',
      's_time'     => 'Date',
      'e_time'     => 'Date',
      'channel_id' => 'Number',
      'type'       => 'Text',
    );
  }
}
