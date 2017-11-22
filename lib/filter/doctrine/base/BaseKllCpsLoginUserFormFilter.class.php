<?php

/**
 * KllCpsLoginUser filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllCpsLoginUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'union_id'   => new sfWidgetFormFilterInput(),
      'uid'        => new sfWidgetFormFilterInput(),
      'mid'        => new sfWidgetFormFilterInput(),
      'referer'    => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'union_id'   => new sfValidatorPass(array('required' => false)),
      'uid'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'mid'        => new sfValidatorPass(array('required' => false)),
      'referer'    => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_cps_login_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllCpsLoginUser';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'union_id'   => 'Text',
      'uid'        => 'Number',
      'mid'        => 'Text',
      'referer'    => 'Text',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
