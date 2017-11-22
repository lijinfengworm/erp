<?php

/**
 * KllXbuyItemLog filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllXbuyItemLogFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'uid'         => new sfWidgetFormFilterInput(),
      'activity_id' => new sfWidgetFormFilterInput(),
      'item_id'     => new sfWidgetFormFilterInput(),
      'number'      => new sfWidgetFormFilterInput(),
      'ct_time'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'uid'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'activity_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'item_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'number'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ct_time'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_xbuy_item_log_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllXbuyItemLog';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'uid'         => 'Number',
      'activity_id' => 'Number',
      'item_id'     => 'Number',
      'number'      => 'Number',
      'ct_time'     => 'Date',
    );
  }
}
