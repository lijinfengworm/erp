<?php

/**
 * TrdAdminUser filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdAdminUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hupu_uid'        => new sfWidgetFormFilterInput(),
      'username'        => new sfWidgetFormFilterInput(),
      'password'        => new sfWidgetFormFilterInput(),
      'qq'              => new sfWidgetFormFilterInput(),
      'email'           => new sfWidgetFormFilterInput(),
      'mobile'          => new sfWidgetFormFilterInput(),
      'verify'          => new sfWidgetFormFilterInput(),
      'channel'         => new sfWidgetFormFilterInput(),
      'role'            => new sfWidgetFormFilterInput(),
      'last_login_ip'   => new sfWidgetFormFilterInput(),
      'last_login_time' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'user_status'     => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'hupu_uid'        => new sfValidatorPass(array('required' => false)),
      'username'        => new sfValidatorPass(array('required' => false)),
      'password'        => new sfValidatorPass(array('required' => false)),
      'qq'              => new sfValidatorPass(array('required' => false)),
      'email'           => new sfValidatorPass(array('required' => false)),
      'mobile'          => new sfValidatorPass(array('required' => false)),
      'verify'          => new sfValidatorPass(array('required' => false)),
      'channel'         => new sfValidatorPass(array('required' => false)),
      'role'            => new sfValidatorPass(array('required' => false)),
      'last_login_ip'   => new sfValidatorPass(array('required' => false)),
      'last_login_time' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'user_status'     => new sfValidatorPass(array('required' => false)),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_admin_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAdminUser';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'hupu_uid'        => 'Text',
      'username'        => 'Text',
      'password'        => 'Text',
      'qq'              => 'Text',
      'email'           => 'Text',
      'mobile'          => 'Text',
      'verify'          => 'Text',
      'channel'         => 'Text',
      'role'            => 'Text',
      'last_login_ip'   => 'Text',
      'last_login_time' => 'Date',
      'user_status'     => 'Text',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
