<?php

/**
 * KllCouponsDetail filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllCouponsDetailFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'account'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'stime'       => new sfWidgetFormFilterInput(),
      'etime'       => new sfWidgetFormFilterInput(),
      'status'      => new sfWidgetFormFilterInput(),
      'activity_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'account'     => new sfValidatorPass(array('required' => false)),
      'stime'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'etime'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'activity_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_coupons_detail_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllCouponsDetail';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'account'     => 'Text',
      'stime'       => 'Number',
      'etime'       => 'Number',
      'status'      => 'Number',
      'activity_id' => 'Number',
    );
  }
}
