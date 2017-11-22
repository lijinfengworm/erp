<?php

/**
 * TrdCouponsRecevied filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdCouponsReceviedFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'root_type'     => new sfWidgetFormFilterInput(),
      'activity_id'   => new sfWidgetFormFilterInput(),
      'type'          => new sfWidgetFormFilterInput(),
      'list_id'       => new sfWidgetFormFilterInput(),
      'detail_id'     => new sfWidgetFormFilterInput(),
      'account'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'pass'          => new sfWidgetFormFilterInput(),
      'mobile'        => new sfWidgetFormFilterInput(),
      'stime'         => new sfWidgetFormFilterInput(),
      'etime'         => new sfWidgetFormFilterInput(),
      'hupu_uid'      => new sfWidgetFormFilterInput(),
      'hupu_username' => new sfWidgetFormFilterInput(),
      'recevied_date' => new sfWidgetFormFilterInput(),
      'status'        => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'root_type'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'activity_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'list_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'detail_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'account'       => new sfValidatorPass(array('required' => false)),
      'pass'          => new sfValidatorPass(array('required' => false)),
      'mobile'        => new sfValidatorPass(array('required' => false)),
      'stime'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'etime'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_uid'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username' => new sfValidatorPass(array('required' => false)),
      'recevied_date' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_coupons_recevied_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdCouponsRecevied';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'root_type'     => 'Number',
      'activity_id'   => 'Number',
      'type'          => 'Number',
      'list_id'       => 'Number',
      'detail_id'     => 'Number',
      'account'       => 'Text',
      'pass'          => 'Text',
      'mobile'        => 'Text',
      'stime'         => 'Number',
      'etime'         => 'Number',
      'hupu_uid'      => 'Number',
      'hupu_username' => 'Text',
      'recevied_date' => 'Number',
      'status'        => 'Number',
    );
  }
}
