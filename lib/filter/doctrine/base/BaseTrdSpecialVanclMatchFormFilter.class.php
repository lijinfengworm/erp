<?php

/**
 * TrdSpecialVanclMatch filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdSpecialVanclMatchFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'home_team'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'away_team'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'home_logo'    => new sfWidgetFormFilterInput(),
      'away_logo'    => new sfWidgetFormFilterInput(),
      'home_sustain' => new sfWidgetFormFilterInput(),
      'away_sustain' => new sfWidgetFormFilterInput(),
      'start_time'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'end_time'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'deleted_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'home_team'    => new sfValidatorPass(array('required' => false)),
      'away_team'    => new sfValidatorPass(array('required' => false)),
      'home_logo'    => new sfValidatorPass(array('required' => false)),
      'away_logo'    => new sfValidatorPass(array('required' => false)),
      'home_sustain' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'away_sustain' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'start_time'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'end_time'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'deleted_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_special_vancl_match_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdSpecialVanclMatch';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'home_team'    => 'Text',
      'away_team'    => 'Text',
      'home_logo'    => 'Text',
      'away_logo'    => 'Text',
      'home_sustain' => 'Number',
      'away_sustain' => 'Number',
      'start_time'   => 'Date',
      'end_time'     => 'Date',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
      'deleted_at'   => 'Date',
    );
  }
}
