<?php

/**
 * KllSendCouponVip filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllSendCouponVipFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'opt_uid'   => new sfWidgetFormFilterInput(),
      'title'     => new sfWidgetFormFilterInput(),
      'position'  => new sfWidgetFormFilterInput(),
      'record_id' => new sfWidgetFormFilterInput(),
      'state'     => new sfWidgetFormFilterInput(),
      's_time'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'e_time'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'opt_uid'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'     => new sfValidatorPass(array('required' => false)),
      'position'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'record_id' => new sfValidatorPass(array('required' => false)),
      'state'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      's_time'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'e_time'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_send_coupon_vip_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllSendCouponVip';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'opt_uid'   => 'Number',
      'title'     => 'Text',
      'position'  => 'Number',
      'record_id' => 'Text',
      'state'     => 'Number',
      's_time'    => 'Date',
      'e_time'    => 'Date',
    );
  }
}
