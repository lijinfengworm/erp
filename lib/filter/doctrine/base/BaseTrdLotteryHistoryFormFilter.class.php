<?php

/**
 * TrdLotteryHistory filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdLotteryHistoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'lottery_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_id'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'phone'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'prize_id'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'prize_name' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_virtual' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'card'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ip'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'source'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'status'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_send'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'address'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'lottery_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'phone'      => new sfValidatorPass(array('required' => false)),
      'prize_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'prize_name' => new sfValidatorPass(array('required' => false)),
      'is_virtual' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'card'       => new sfValidatorPass(array('required' => false)),
      'ip'         => new sfValidatorPass(array('required' => false)),
      'source'     => new sfValidatorPass(array('required' => false)),
      'status'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_send'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'address'    => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_lottery_history_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdLotteryHistory';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'lottery_id' => 'Number',
      'user_id'    => 'Number',
      'phone'      => 'Text',
      'prize_id'   => 'Number',
      'prize_name' => 'Text',
      'is_virtual' => 'Number',
      'card'       => 'Text',
      'ip'         => 'Text',
      'source'     => 'Text',
      'status'     => 'Number',
      'is_send'    => 'Number',
      'address'    => 'Text',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
