<?php

/**
 * TrdLottery filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdLotteryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'lottery_name'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lottery_desc'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'max_rand'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_must'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'fail_msg'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lottery_num_type' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_lottery_num' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'attr_lottery_num' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'start_time'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'end_time'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'bg_img'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'pointer_img'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'round_img'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'status'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'lottery_name'     => new sfValidatorPass(array('required' => false)),
      'lottery_desc'     => new sfValidatorPass(array('required' => false)),
      'max_rand'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_must'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fail_msg'         => new sfValidatorPass(array('required' => false)),
      'lottery_num_type' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_lottery_num' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'attr_lottery_num' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'start_time'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'end_time'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'bg_img'           => new sfValidatorPass(array('required' => false)),
      'pointer_img'      => new sfValidatorPass(array('required' => false)),
      'round_img'        => new sfValidatorPass(array('required' => false)),
      'status'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_lottery_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdLottery';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'lottery_name'     => 'Text',
      'lottery_desc'     => 'Text',
      'max_rand'         => 'Number',
      'is_must'          => 'Number',
      'fail_msg'         => 'Text',
      'lottery_num_type' => 'Number',
      'user_lottery_num' => 'Number',
      'attr_lottery_num' => 'Number',
      'start_time'       => 'Number',
      'end_time'         => 'Number',
      'bg_img'           => 'Text',
      'pointer_img'      => 'Text',
      'round_img'        => 'Text',
      'status'           => 'Number',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
    );
  }
}
