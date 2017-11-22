<?php

/**
 * ZyHotEvent filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseZyHotEventFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hupu_uid'    => new sfWidgetFormFilterInput(),
      'username'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'img_url'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'light_count' => new sfWidgetFormFilterInput(),
      'content'     => new sfWidgetFormFilterInput(),
      'status'      => new sfWidgetFormFilterInput(),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'hupu_uid'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'username'    => new sfValidatorPass(array('required' => false)),
      'img_url'     => new sfValidatorPass(array('required' => false)),
      'light_count' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'content'     => new sfValidatorPass(array('required' => false)),
      'status'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('zy_hot_event_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZyHotEvent';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'hupu_uid'    => 'Number',
      'username'    => 'Text',
      'img_url'     => 'Text',
      'light_count' => 'Number',
      'content'     => 'Text',
      'status'      => 'Number',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
