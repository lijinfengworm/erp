<?php

/**
 * KllKolMonthAccountLog filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllKolMonthAccountLogFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'kol_id'            => new sfWidgetFormFilterInput(),
      'user_name'         => new sfWidgetFormFilterInput(),
      'account'           => new sfWidgetFormFilterInput(),
      'order_total_price' => new sfWidgetFormFilterInput(),
      'commision_price'   => new sfWidgetFormFilterInput(),
      'type'              => new sfWidgetFormFilterInput(),
      'month'             => new sfWidgetFormFilterInput(),
      'year'              => new sfWidgetFormFilterInput(),
      'ct_time'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'channel_id'        => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'kol_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_name'         => new sfValidatorPass(array('required' => false)),
      'account'           => new sfValidatorPass(array('required' => false)),
      'order_total_price' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'commision_price'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'type'              => new sfValidatorPass(array('required' => false)),
      'month'             => new sfValidatorPass(array('required' => false)),
      'year'              => new sfValidatorPass(array('required' => false)),
      'ct_time'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'channel_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_kol_month_account_log_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllKolMonthAccountLog';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'kol_id'            => 'Number',
      'user_name'         => 'Text',
      'account'           => 'Text',
      'order_total_price' => 'Number',
      'commision_price'   => 'Number',
      'type'              => 'Text',
      'month'             => 'Text',
      'year'              => 'Text',
      'ct_time'           => 'Date',
      'channel_id'        => 'Number',
    );
  }
}
