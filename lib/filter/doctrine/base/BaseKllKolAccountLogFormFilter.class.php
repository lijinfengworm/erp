<?php

/**
 * KllKolAccountLog filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllKolAccountLogFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'kol_id'       => new sfWidgetFormFilterInput(),
      'log_channel'  => new sfWidgetFormFilterInput(),
      'type'         => new sfWidgetFormFilterInput(),
      'price'        => new sfWidgetFormFilterInput(),
      'ct_time'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'order_number' => new sfWidgetFormFilterInput(),
      'audit'        => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'kol_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'log_channel'  => new sfValidatorPass(array('required' => false)),
      'type'         => new sfValidatorPass(array('required' => false)),
      'price'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'ct_time'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'order_number' => new sfValidatorPass(array('required' => false)),
      'audit'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_kol_account_log_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllKolAccountLog';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'kol_id'       => 'Number',
      'log_channel'  => 'Text',
      'type'         => 'Text',
      'price'        => 'Number',
      'ct_time'      => 'Date',
      'order_number' => 'Text',
      'audit'        => 'Text',
    );
  }
}
