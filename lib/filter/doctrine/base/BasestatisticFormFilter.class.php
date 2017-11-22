<?php

/**
 * statistic filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasestatisticFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'app'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'date_time' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'result'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'app'       => new sfValidatorPass(array('required' => false)),
      'name'      => new sfValidatorPass(array('required' => false)),
      'date_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'result'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('statistic_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'statistic';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'app'       => 'Text',
      'name'      => 'Text',
      'date_time' => 'Number',
      'result'    => 'Number',
    );
  }
}
