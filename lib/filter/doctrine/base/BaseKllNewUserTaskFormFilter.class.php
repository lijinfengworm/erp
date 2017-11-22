<?php

/**
 * KllNewUserTask filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllNewUserTaskFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'      => new sfWidgetFormFilterInput(),
      'invitor'      => new sfWidgetFormFilterInput(),
      'section'      => new sfWidgetFormFilterInput(),
      'ct_time'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'content'      => new sfWidgetFormFilterInput(),
      'order_number' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'invitor'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'section'      => new sfValidatorPass(array('required' => false)),
      'ct_time'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'content'      => new sfValidatorPass(array('required' => false)),
      'order_number' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_new_user_task_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllNewUserTask';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'user_id'      => 'Number',
      'invitor'      => 'Number',
      'section'      => 'Text',
      'ct_time'      => 'Date',
      'content'      => 'Text',
      'order_number' => 'Text',
    );
  }
}
