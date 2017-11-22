<?php

/**
 * KaluliOptLog filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKaluliOptLogFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'opt_id'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'opt_uid'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'opt_uri'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'opt_json'      => new sfWidgetFormFilterInput(),
      'opt_time'      => new sfWidgetFormFilterInput(),
      'last_login_ip' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'opt_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'opt_uid'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'opt_uri'       => new sfValidatorPass(array('required' => false)),
      'opt_json'      => new sfValidatorPass(array('required' => false)),
      'opt_time'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'last_login_ip' => new sfValidatorPass(array('required' => false)),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kaluli_opt_log_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliOptLog';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'opt_id'        => 'Number',
      'opt_uid'       => 'Number',
      'opt_uri'       => 'Text',
      'opt_json'      => 'Text',
      'opt_time'      => 'Number',
      'last_login_ip' => 'Text',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
