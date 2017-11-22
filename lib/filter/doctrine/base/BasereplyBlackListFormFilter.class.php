<?php

/**
 * replyBlackList filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasereplyBlackListFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_name'   => new sfWidgetFormFilterInput(),
      'banned_day'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'expire_time' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'extra_str'   => new sfWidgetFormFilterInput(),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'user_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_name'   => new sfValidatorPass(array('required' => false)),
      'banned_day'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'expire_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'extra_str'   => new sfValidatorPass(array('required' => false)),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('reply_black_list_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'replyBlackList';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'user_id'     => 'Number',
      'user_name'   => 'Text',
      'banned_day'  => 'Number',
      'expire_time' => 'Number',
      'extra_str'   => 'Text',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
