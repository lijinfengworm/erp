<?php

/**
 * TrdCouponsDetail filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdCouponsDetailFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'activity_id' => new sfWidgetFormFilterInput(),
      'list_id'     => new sfWidgetFormFilterInput(),
      'account'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'pass'        => new sfWidgetFormFilterInput(),
      'stime'       => new sfWidgetFormFilterInput(),
      'etime'       => new sfWidgetFormFilterInput(),
      'status'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'activity_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'list_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'account'     => new sfValidatorPass(array('required' => false)),
      'pass'        => new sfValidatorPass(array('required' => false)),
      'stime'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'etime'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_coupons_detail_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdCouponsDetail';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'activity_id' => 'Number',
      'list_id'     => 'Number',
      'account'     => 'Text',
      'pass'        => 'Text',
      'stime'       => 'Number',
      'etime'       => 'Number',
      'status'      => 'Number',
    );
  }
}
