<?php

/**
 * OperationLog filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseOperationLogFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'team_id'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_id'     => new sfWidgetFormFilterInput(),
      'ip'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'app'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'model'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'before_data' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'after_data'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'team_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ip'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'app'         => new sfValidatorPass(array('required' => false)),
      'model'       => new sfValidatorPass(array('required' => false)),
      'before_data' => new sfValidatorPass(array('required' => false)),
      'after_data'  => new sfValidatorPass(array('required' => false)),
      'created_at'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('operation_log_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'OperationLog';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'team_id'     => 'Number',
      'user_id'     => 'Number',
      'ip'          => 'Number',
      'app'         => 'Text',
      'model'       => 'Text',
      'before_data' => 'Text',
      'after_data'  => 'Text',
      'created_at'  => 'Number',
    );
  }
}
