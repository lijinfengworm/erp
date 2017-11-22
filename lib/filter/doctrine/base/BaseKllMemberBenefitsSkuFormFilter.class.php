<?php

/**
 * KllMemberBenefitsSku filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllMemberBenefitsSkuFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'mb_id'  => new sfWidgetFormFilterInput(),
      'sku_id' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'mb_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sku_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_member_benefits_sku_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllMemberBenefitsSku';
  }

  public function getFields()
  {
    return array(
      'id'     => 'Number',
      'mb_id'  => 'Number',
      'sku_id' => 'Number',
    );
  }
}
