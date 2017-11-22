<?php

/**
 * TrdGrouponAd filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdGrouponAdFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'group_id'   => new sfWidgetFormFilterInput(),
      'title'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'order_id'   => new sfWidgetFormFilterInput(),
      'stime'      => new sfWidgetFormFilterInput(),
      'etime'      => new sfWidgetFormFilterInput(),
      'pay_type'   => new sfWidgetFormFilterInput(),
      'pay_date'   => new sfWidgetFormFilterInput(),
      'is_cancel'  => new sfWidgetFormFilterInput(),
      'reason'     => new sfWidgetFormFilterInput(),
      'status'     => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'group_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'      => new sfValidatorPass(array('required' => false)),
      'order_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stime'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'etime'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pay_type'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pay_date'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_cancel'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reason'     => new sfValidatorPass(array('required' => false)),
      'status'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_groupon_ad_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGrouponAd';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'group_id'   => 'Number',
      'title'      => 'Text',
      'order_id'   => 'Number',
      'stime'      => 'Number',
      'etime'      => 'Number',
      'pay_type'   => 'Number',
      'pay_date'   => 'Number',
      'is_cancel'  => 'Number',
      'reason'     => 'Text',
      'status'     => 'Number',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
