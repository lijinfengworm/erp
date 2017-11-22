<?php

/**
 * ErLog filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseErLogFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'site'        => new sfWidgetFormFilterInput(),
      'userfrom'    => new sfWidgetFormFilterInput(),
      'userid'      => new sfWidgetFormFilterInput(),
      'username'    => new sfWidgetFormFilterInput(),
      'object_name' => new sfWidgetFormFilterInput(),
      'action'      => new sfWidgetFormFilterInput(),
      'url'         => new sfWidgetFormFilterInput(),
      'time'        => new sfWidgetFormFilterInput(),
      'status'      => new sfWidgetFormFilterInput(),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'site'        => new sfValidatorPass(array('required' => false)),
      'userfrom'    => new sfValidatorPass(array('required' => false)),
      'userid'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'username'    => new sfValidatorPass(array('required' => false)),
      'object_name' => new sfValidatorPass(array('required' => false)),
      'action'      => new sfValidatorPass(array('required' => false)),
      'url'         => new sfValidatorPass(array('required' => false)),
      'time'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('er_log_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ErLog';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'site'        => 'Text',
      'userfrom'    => 'Text',
      'userid'      => 'Number',
      'username'    => 'Text',
      'object_name' => 'Text',
      'action'      => 'Text',
      'url'         => 'Text',
      'time'        => 'Number',
      'status'      => 'Number',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
