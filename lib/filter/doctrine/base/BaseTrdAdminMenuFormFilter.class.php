<?php

/**
 * TrdAdminMenu filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdAdminMenuFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'pid'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'controller'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'action_name' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_public'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_hide'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'child_attr'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'menu_group'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'menu_status' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'listorder'   => new sfWidgetFormFilterInput(),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'pid'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'        => new sfValidatorPass(array('required' => false)),
      'controller'  => new sfValidatorPass(array('required' => false)),
      'action_name' => new sfValidatorPass(array('required' => false)),
      'is_public'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_hide'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'child_attr'  => new sfValidatorPass(array('required' => false)),
      'menu_group'  => new sfValidatorPass(array('required' => false)),
      'menu_status' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'listorder'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_admin_menu_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAdminMenu';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'pid'         => 'Number',
      'name'        => 'Text',
      'controller'  => 'Text',
      'action_name' => 'Text',
      'is_public'   => 'Number',
      'is_hide'     => 'Number',
      'child_attr'  => 'Text',
      'menu_group'  => 'Text',
      'menu_status' => 'Number',
      'listorder'   => 'Number',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
