<?php

/**
 * omActivityTime filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseomActivityTimeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'sun'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'mon'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'tues' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'wed'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'thu'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'fri'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sat'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'date' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'sun'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'mon'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tues' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'wed'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'thu'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fri'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sat'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'date' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('om_activity_time_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'omActivityTime';
  }

  public function getFields()
  {
    return array(
      'id'   => 'Number',
      'sun'  => 'Number',
      'mon'  => 'Number',
      'tues' => 'Number',
      'wed'  => 'Number',
      'thu'  => 'Number',
      'fri'  => 'Number',
      'sat'  => 'Number',
      'date' => 'Number',
    );
  }
}
