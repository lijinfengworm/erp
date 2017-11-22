<?php

/**
 * TrdUserPay filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdUserPayFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hupu_uid'         => new sfWidgetFormFilterInput(),
      'hupu_username'    => new sfWidgetFormFilterInput(),
      'pay_passwd'       => new sfWidgetFormFilterInput(),
      'pay_passwd_index' => new sfWidgetFormFilterInput(),
      'status'           => new sfWidgetFormFilterInput(),
      'phone'            => new sfWidgetFormFilterInput(),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'hupu_uid'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username'    => new sfValidatorPass(array('required' => false)),
      'pay_passwd'       => new sfValidatorPass(array('required' => false)),
      'pay_passwd_index' => new sfValidatorPass(array('required' => false)),
      'status'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'phone'            => new sfValidatorPass(array('required' => false)),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_user_pay_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdUserPay';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'hupu_uid'         => 'Number',
      'hupu_username'    => 'Text',
      'pay_passwd'       => 'Text',
      'pay_passwd_index' => 'Text',
      'status'           => 'Number',
      'phone'            => 'Text',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
    );
  }
}
