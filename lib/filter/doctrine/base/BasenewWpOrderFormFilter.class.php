<?php

/**
 * newWpOrder filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasenewWpOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'status'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_id'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_name'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'role_user_id'   => new sfWidgetFormFilterInput(),
      'role_user_name' => new sfWidgetFormFilterInput(),
      'wpgame_name'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'wpserver_name'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'wppayment_name' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'wppayment_id'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'order_no'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'amount'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ip'             => new sfWidgetFormFilterInput(),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'status'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_name'      => new sfValidatorPass(array('required' => false)),
      'role_user_id'   => new sfValidatorPass(array('required' => false)),
      'role_user_name' => new sfValidatorPass(array('required' => false)),
      'wpgame_name'    => new sfValidatorPass(array('required' => false)),
      'wpserver_name'  => new sfValidatorPass(array('required' => false)),
      'wppayment_name' => new sfValidatorPass(array('required' => false)),
      'wppayment_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'order_no'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'amount'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'ip'             => new sfValidatorPass(array('required' => false)),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('new_wp_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'newWpOrder';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'status'         => 'Number',
      'user_id'        => 'Number',
      'user_name'      => 'Text',
      'role_user_id'   => 'Text',
      'role_user_name' => 'Text',
      'wpgame_name'    => 'Text',
      'wpserver_name'  => 'Text',
      'wppayment_name' => 'Text',
      'wppayment_id'   => 'Number',
      'order_no'       => 'Number',
      'amount'         => 'Number',
      'ip'             => 'Text',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
