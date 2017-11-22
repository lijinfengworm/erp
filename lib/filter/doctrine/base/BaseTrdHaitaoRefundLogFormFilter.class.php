<?php

/**
 * TrdHaitaoRefundLog filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdHaitaoRefundLogFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'order_number_attr' => new sfWidgetFormFilterInput(),
      'callback_attr'     => new sfWidgetFormFilterInput(),
      'grant_uid'         => new sfWidgetFormFilterInput(),
      'grant_username'    => new sfWidgetFormFilterInput(),
      'status'            => new sfWidgetFormFilterInput(),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'title'             => new sfValidatorPass(array('required' => false)),
      'order_number_attr' => new sfValidatorPass(array('required' => false)),
      'callback_attr'     => new sfValidatorPass(array('required' => false)),
      'grant_uid'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'grant_username'    => new sfValidatorPass(array('required' => false)),
      'status'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_haitao_refund_log_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdHaitaoRefundLog';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'title'             => 'Text',
      'order_number_attr' => 'Text',
      'callback_attr'     => 'Text',
      'grant_uid'         => 'Number',
      'grant_username'    => 'Text',
      'status'            => 'Number',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
    );
  }
}
