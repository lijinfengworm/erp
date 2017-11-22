<?php

/**
 * TrdNewsZhidingLog filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdNewsZhidingLogFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'newsid'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'zhiding_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'newsid'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'zhiding_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_news_zhiding_log_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdNewsZhidingLog';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'newsid'     => 'Number',
      'type'       => 'Number',
      'zhiding_at' => 'Date',
    );
  }
}
