<?php

/**
 * KllBBOrderFlow filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllBBOrderFlowFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'flow_number' => new sfWidgetFormFilterInput(),
      'body'        => new sfWidgetFormFilterInput(),
      'creat_time'  => new sfWidgetFormFilterInput(),
      'update_time' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'flow_number' => new sfValidatorPass(array('required' => false)),
      'body'        => new sfValidatorPass(array('required' => false)),
      'creat_time'  => new sfValidatorPass(array('required' => false)),
      'update_time' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_bb_order_flow_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllBBOrderFlow';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'flow_number' => 'Text',
      'body'        => 'Text',
      'creat_time'  => 'Text',
      'update_time' => 'Text',
    );
  }
}
