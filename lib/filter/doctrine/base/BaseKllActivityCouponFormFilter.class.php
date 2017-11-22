<?php

/**
 * KllActivityCoupon filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllActivityCouponFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'         => new sfWidgetFormFilterInput(),
      'open_id'         => new sfWidgetFormFilterInput(),
      'mobile'          => new sfWidgetFormFilterInput(),
      'activity_id'     => new sfWidgetFormFilterInput(),
      'user_status'     => new sfWidgetFormFilterInput(),
      'user_activation' => new sfWidgetFormFilterInput(),
      'is_new'          => new sfWidgetFormFilterInput(),
      'create_time'     => new sfWidgetFormFilterInput(),
      'inviter'         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'open_id'         => new sfValidatorPass(array('required' => false)),
      'mobile'          => new sfValidatorPass(array('required' => false)),
      'activity_id'     => new sfValidatorPass(array('required' => false)),
      'user_status'     => new sfValidatorPass(array('required' => false)),
      'user_activation' => new sfValidatorPass(array('required' => false)),
      'is_new'          => new sfValidatorPass(array('required' => false)),
      'create_time'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'inviter'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_activity_coupon_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllActivityCoupon';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'user_id'         => 'Number',
      'open_id'         => 'Text',
      'mobile'          => 'Text',
      'activity_id'     => 'Text',
      'user_status'     => 'Text',
      'user_activation' => 'Text',
      'is_new'          => 'Text',
      'create_time'     => 'Number',
      'inviter'         => 'Number',
    );
  }
}
