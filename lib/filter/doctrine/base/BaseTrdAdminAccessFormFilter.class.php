<?php

/**
 * TrdAdminAccess filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdAdminAccessFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'role_id'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'menu_id'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'controller'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'action_name' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'child_attr'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'role_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'menu_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'controller'  => new sfValidatorPass(array('required' => false)),
      'action_name' => new sfValidatorPass(array('required' => false)),
      'child_attr'  => new sfValidatorPass(array('required' => false)),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_admin_access_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAdminAccess';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'role_id'     => 'Number',
      'menu_id'     => 'Number',
      'controller'  => 'Text',
      'action_name' => 'Text',
      'child_attr'  => 'Text',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
