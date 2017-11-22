<?php

/**
 * LlMatch filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseLlMatchFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'link_to'        => new sfWidgetFormFilterInput(),
      'start_time'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'match_time'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'link_to_report' => new sfWidgetFormFilterInput(),
      'match_category' => new sfWidgetFormFilterInput(),
      'match_id'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'           => new sfValidatorPass(array('required' => false)),
      'link_to'        => new sfValidatorPass(array('required' => false)),
      'start_time'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'match_time'     => new sfValidatorPass(array('required' => false)),
      'link_to_report' => new sfValidatorPass(array('required' => false)),
      'match_category' => new sfValidatorPass(array('required' => false)),
      'match_id'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ll_match_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'LlMatch';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'name'           => 'Text',
      'link_to'        => 'Text',
      'start_time'     => 'Date',
      'match_time'     => 'Text',
      'link_to_report' => 'Text',
      'match_category' => 'Text',
      'match_id'       => 'Text',
    );
  }
}
