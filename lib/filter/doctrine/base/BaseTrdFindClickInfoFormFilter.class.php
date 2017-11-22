<?php

/**
 * TrdFindClickInfo filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdFindClickInfoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'item_id'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'vid'       => new sfWidgetFormFilterInput(),
      'clicktime' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'item_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'vid'       => new sfValidatorPass(array('required' => false)),
      'clicktime' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_find_click_info_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdFindClickInfo';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Text',
      'item_id'   => 'Number',
      'vid'       => 'Text',
      'clicktime' => 'Date',
    );
  }
}
