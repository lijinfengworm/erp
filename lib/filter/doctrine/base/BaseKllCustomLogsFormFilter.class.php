<?php

/**
 * KllCustomLogs filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllCustomLogsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'contents'     => new sfWidgetFormFilterInput(),
      'opt_uid'      => new sfWidgetFormFilterInput(),
      'order_number' => new sfWidgetFormFilterInput(),
      'stime'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'contents'     => new sfValidatorPass(array('required' => false)),
      'opt_uid'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'order_number' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stime'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_custom_logs_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllCustomLogs';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'contents'     => 'Text',
      'opt_uid'      => 'Number',
      'order_number' => 'Number',
      'stime'        => 'Date',
    );
  }
}
