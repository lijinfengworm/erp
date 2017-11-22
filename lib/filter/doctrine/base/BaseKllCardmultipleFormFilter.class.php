<?php

/**
 * KllCardmultiple filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllCardmultipleFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'code'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'title'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'hupu_uid'      => new sfWidgetFormFilterInput(),
      'hupu_username' => new sfWidgetFormFilterInput(),
      'status'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'cardware_code' => new sfWidgetFormFilterInput(),
      'is_success'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'alert_num'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_alert'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'phone'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'card_number'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'read_number'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'code'          => new sfValidatorPass(array('required' => false)),
      'title'         => new sfValidatorPass(array('required' => false)),
      'hupu_uid'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username' => new sfValidatorPass(array('required' => false)),
      'status'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'cardware_code' => new sfValidatorPass(array('required' => false)),
      'is_success'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'alert_num'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_alert'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'phone'         => new sfValidatorPass(array('required' => false)),
      'card_number'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'read_number'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_cardmultiple_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllCardmultiple';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'code'          => 'Text',
      'title'         => 'Text',
      'hupu_uid'      => 'Number',
      'hupu_username' => 'Text',
      'status'        => 'Number',
      'cardware_code' => 'Text',
      'is_success'    => 'Number',
      'alert_num'     => 'Number',
      'is_alert'      => 'Number',
      'phone'         => 'Text',
      'card_number'   => 'Number',
      'read_number'   => 'Number',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
