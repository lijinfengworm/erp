<?php

/**
 * KllBBOrderLog filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllBBOrderLogFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number'   => new sfWidgetFormFilterInput(),
      'type'           => new sfWidgetFormFilterInput(),
      'content'        => new sfWidgetFormFilterInput(),
      'creat_time'     => new sfWidgetFormFilterInput(),
      'update_time'    => new sfWidgetFormFilterInput(),
      'operation_user' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'order_number'   => new sfValidatorPass(array('required' => false)),
      'type'           => new sfValidatorPass(array('required' => false)),
      'content'        => new sfValidatorPass(array('required' => false)),
      'creat_time'     => new sfValidatorPass(array('required' => false)),
      'update_time'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'operation_user' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_bb_order_log_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllBBOrderLog';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Text',
      'order_number'   => 'Text',
      'type'           => 'Text',
      'content'        => 'Text',
      'creat_time'     => 'Text',
      'update_time'    => 'Number',
      'operation_user' => 'Text',
    );
  }
}
