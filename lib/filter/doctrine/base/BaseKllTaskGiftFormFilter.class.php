<?php

/**
 * KllTaskGift filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllTaskGiftFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id' => new sfWidgetFormFilterInput(),
      'type'    => new sfWidgetFormFilterInput(),
      'task'    => new sfWidgetFormFilterInput(),
      'ct_time' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'user_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'    => new sfValidatorPass(array('required' => false)),
      'task'    => new sfValidatorPass(array('required' => false)),
      'ct_time' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_task_gift_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllTaskGift';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'user_id' => 'Number',
      'type'    => 'Text',
      'task'    => 'Text',
      'ct_time' => 'Date',
    );
  }
}
