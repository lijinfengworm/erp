<?php

/**
 * kllOrderBirdex filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasekllOrderBirdexFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'zt'               => new sfWidgetFormFilterInput(),
      'send_birdex'      => new sfWidgetFormFilterInput(),
      'ibilling_number'  => new sfWidgetFormFilterInput(),
      'order_number'     => new sfWidgetFormFilterInput(),
      'update_time'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'create_time'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'send_birdex_date' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'zt'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'send_birdex'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ibilling_number'  => new sfValidatorPass(array('required' => false)),
      'order_number'     => new sfValidatorPass(array('required' => false)),
      'update_time'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'create_time'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'send_birdex_date' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_order_birdex_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'kllOrderBirdex';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'zt'               => 'Number',
      'send_birdex'      => 'Number',
      'ibilling_number'  => 'Text',
      'order_number'     => 'Text',
      'update_time'      => 'Date',
      'create_time'      => 'Date',
      'send_birdex_date' => 'Date',
    );
  }
}
