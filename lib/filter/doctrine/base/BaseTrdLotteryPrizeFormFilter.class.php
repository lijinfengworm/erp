<?php

/**
 * TrdLotteryPrize filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdLotteryPrizeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'lottery_id'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'prize_name'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'prize_rand'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_virtual'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'virtual_type' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'prize_num'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'prize_info'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'listorder'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'lottery_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'prize_name'   => new sfValidatorPass(array('required' => false)),
      'prize_rand'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_virtual'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'virtual_type' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'prize_num'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'prize_info'   => new sfValidatorPass(array('required' => false)),
      'listorder'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_lottery_prize_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdLotteryPrize';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'lottery_id'   => 'Number',
      'prize_name'   => 'Text',
      'prize_rand'   => 'Number',
      'is_virtual'   => 'Number',
      'virtual_type' => 'Number',
      'prize_num'    => 'Number',
      'prize_info'   => 'Text',
      'listorder'    => 'Number',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
