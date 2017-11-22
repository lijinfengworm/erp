<?php

/**
 * KllErpOrder filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllErpOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number' => new sfWidgetFormFilterInput(),
      'audit_status' => new sfWidgetFormFilterInput(),
      'channel'      => new sfWidgetFormFilterInput(),
      'audit_user'   => new sfWidgetFormFilterInput(),
      'audit_time'   => new sfWidgetFormFilterInput(),
      'create_time'  => new sfWidgetFormFilterInput(),
      'update_time'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'order_number' => new sfValidatorPass(array('required' => false)),
      'audit_status' => new sfValidatorPass(array('required' => false)),
      'channel'      => new sfValidatorPass(array('required' => false)),
      'audit_user'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'audit_time'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'create_time'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'update_time'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_erp_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllErpOrder';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Text',
      'order_number' => 'Text',
      'audit_status' => 'Text',
      'channel'      => 'Text',
      'audit_user'   => 'Number',
      'audit_time'   => 'Number',
      'create_time'  => 'Number',
      'update_time'  => 'Number',
    );
  }
}
