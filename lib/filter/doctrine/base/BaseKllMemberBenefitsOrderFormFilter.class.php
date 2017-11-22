<?php

/**
 * KllMemberBenefitsOrder filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllMemberBenefitsOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'mb_id'        => new sfWidgetFormFilterInput(),
      'order_number' => new sfWidgetFormFilterInput(),
      'status'       => new sfWidgetFormFilterInput(),
      'ct_time'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'mb_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'order_number' => new sfValidatorPass(array('required' => false)),
      'status'       => new sfValidatorPass(array('required' => false)),
      'ct_time'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_member_benefits_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllMemberBenefitsOrder';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'mb_id'        => 'Number',
      'order_number' => 'Text',
      'status'       => 'Text',
      'ct_time'      => 'Number',
    );
  }
}
