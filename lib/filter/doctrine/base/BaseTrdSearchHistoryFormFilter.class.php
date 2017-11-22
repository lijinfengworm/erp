<?php

/**
 * TrdSearchHistory filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdSearchHistoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'word'        => new sfWidgetFormFilterInput(),
      'count'       => new sfWidgetFormFilterInput(),
      'type'        => new sfWidgetFormFilterInput(),
      'source'      => new sfWidgetFormFilterInput(),
      'time'        => new sfWidgetFormFilterInput(),
      'create_time' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'word'        => new sfValidatorPass(array('required' => false)),
      'count'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'source'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'time'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'create_time' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_search_history_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdSearchHistory';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'word'        => 'Text',
      'count'       => 'Number',
      'type'        => 'Number',
      'source'      => 'Number',
      'time'        => 'Number',
      'create_time' => 'Date',
    );
  }
}
