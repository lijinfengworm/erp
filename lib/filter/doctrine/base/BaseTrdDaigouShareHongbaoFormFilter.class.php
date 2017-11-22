<?php

/**
 * TrdDaigouShareHongbao filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdDaigouShareHongbaoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_no'       => new sfWidgetFormFilterInput(),
      'hupu_uid'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'hupu_username'  => new sfWidgetFormFilterInput(),
      'lipinka_amount' => new sfWidgetFormFilterInput(),
      'weixin_info'    => new sfWidgetFormFilterInput(),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'order_no'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_uid'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username'  => new sfValidatorPass(array('required' => false)),
      'lipinka_amount' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'weixin_info'    => new sfValidatorPass(array('required' => false)),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_daigou_share_hongbao_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdDaigouShareHongbao';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'order_no'       => 'Number',
      'hupu_uid'       => 'Number',
      'hupu_username'  => 'Text',
      'lipinka_amount' => 'Number',
      'weixin_info'    => 'Text',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
