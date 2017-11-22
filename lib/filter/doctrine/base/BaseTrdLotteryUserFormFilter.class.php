<?php

/**
 * TrdLotteryUser filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdLotteryUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'lottery_id'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'phone'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'verify'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lottery_num' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'attr_num'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'source'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'lottery_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'phone'       => new sfValidatorPass(array('required' => false)),
      'verify'      => new sfValidatorPass(array('required' => false)),
      'lottery_num' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'attr_num'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'source'      => new sfValidatorPass(array('required' => false)),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_lottery_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdLotteryUser';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'lottery_id'  => 'Number',
      'phone'       => 'Text',
      'verify'      => 'Text',
      'lottery_num' => 'Number',
      'attr_num'    => 'Number',
      'source'      => 'Text',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
