<?php

/**
 * MobtSchedule filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseMobtScheduleFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'home_team_en_name' => new sfWidgetFormFilterInput(),
      'home_team_cn_name' => new sfWidgetFormFilterInput(),
      'home_nation_flag'  => new sfWidgetFormFilterInput(),
      'away_team_en_name' => new sfWidgetFormFilterInput(),
      'away_team_cn_name' => new sfWidgetFormFilterInput(),
      'away_nation_flag'  => new sfWidgetFormFilterInput(),
      'china_time'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'home_team_en_name' => new sfValidatorPass(array('required' => false)),
      'home_team_cn_name' => new sfValidatorPass(array('required' => false)),
      'home_nation_flag'  => new sfValidatorPass(array('required' => false)),
      'away_team_en_name' => new sfValidatorPass(array('required' => false)),
      'away_team_cn_name' => new sfValidatorPass(array('required' => false)),
      'away_nation_flag'  => new sfValidatorPass(array('required' => false)),
      'china_time'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('mobt_schedule_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MobtSchedule';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'home_team_en_name' => 'Text',
      'home_team_cn_name' => 'Text',
      'home_nation_flag'  => 'Text',
      'away_team_en_name' => 'Text',
      'away_team_cn_name' => 'Text',
      'away_nation_flag'  => 'Text',
      'china_time'        => 'Date',
    );
  }
}
