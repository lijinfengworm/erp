<?php

/**
 * CpsClickUser filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCpsClickUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'cookie'     => new sfWidgetFormFilterInput(),
      'union_id'   => new sfWidgetFormFilterInput(),
      'mid'        => new sfWidgetFormFilterInput(),
      'euid'       => new sfWidgetFormFilterInput(),
      'referer'    => new sfWidgetFormFilterInput(),
      'to'         => new sfWidgetFormFilterInput(),
      'ip'         => new sfWidgetFormFilterInput(),
      'click_time' => new sfWidgetFormFilterInput(),
      'hupu_uid'   => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'cookie'     => new sfValidatorPass(array('required' => false)),
      'union_id'   => new sfValidatorPass(array('required' => false)),
      'mid'        => new sfValidatorPass(array('required' => false)),
      'euid'       => new sfValidatorPass(array('required' => false)),
      'referer'    => new sfValidatorPass(array('required' => false)),
      'to'         => new sfValidatorPass(array('required' => false)),
      'ip'         => new sfValidatorPass(array('required' => false)),
      'click_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_uid'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('cps_click_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CpsClickUser';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'cookie'     => 'Text',
      'union_id'   => 'Text',
      'mid'        => 'Text',
      'euid'       => 'Text',
      'referer'    => 'Text',
      'to'         => 'Text',
      'ip'         => 'Text',
      'click_time' => 'Number',
      'hupu_uid'   => 'Number',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
