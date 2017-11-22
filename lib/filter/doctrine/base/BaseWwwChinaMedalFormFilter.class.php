<?php

/**
 * WwwChinaMedal filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseWwwChinaMedalFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'date'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'match_count' => new sfWidgetFormFilterInput(),
      'golden'      => new sfWidgetFormFilterInput(),
      'silver'      => new sfWidgetFormFilterInput(),
      'bronze'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'date'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'match_count' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'golden'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'silver'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'bronze'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('www_china_medal_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'WwwChinaMedal';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'date'        => 'Date',
      'match_count' => 'Number',
      'golden'      => 'Number',
      'silver'      => 'Number',
      'bronze'      => 'Number',
    );
  }
}
