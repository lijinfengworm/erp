<?php

/**
 * KllBBProcess filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllBBProcessFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number' => new sfWidgetFormFilterInput(),
      'content'      => new sfWidgetFormFilterInput(),
      'sequence'     => new sfWidgetFormFilterInput(),
      'status'       => new sfWidgetFormFilterInput(),
      'update_time'  => new sfWidgetFormFilterInput(),
      'create_time'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'order_number' => new sfValidatorPass(array('required' => false)),
      'content'      => new sfValidatorPass(array('required' => false)),
      'sequence'     => new sfValidatorPass(array('required' => false)),
      'status'       => new sfValidatorPass(array('required' => false)),
      'update_time'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'create_time'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_bb_process_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllBBProcess';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Text',
      'order_number' => 'Text',
      'content'      => 'Text',
      'sequence'     => 'Text',
      'status'       => 'Text',
      'update_time'  => 'Number',
      'create_time'  => 'Number',
    );
  }
}
