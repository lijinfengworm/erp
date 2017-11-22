<?php

/**
 * KllActivity filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllActivityFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'root_type'      => new sfWidgetFormFilterInput(),
      'title'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'total'          => new sfWidgetFormFilterInput(),
      'list_id'        => new sfWidgetFormFilterInput(),
      'limits'         => new sfWidgetFormFilterInput(),
      'recevied'       => new sfWidgetFormFilterInput(),
      'start_date'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'expiry_date'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'img_path'       => new sfWidgetFormFilterInput(),
      'content'        => new sfWidgetFormFilterInput(),
      'grant_uid'      => new sfWidgetFormFilterInput(),
      'grant_username' => new sfWidgetFormFilterInput(),
      'mart'           => new sfWidgetFormFilterInput(),
      'receive_url'    => new sfWidgetFormFilterInput(),
      'is_delete'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'root_type'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'          => new sfValidatorPass(array('required' => false)),
      'total'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'list_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'limits'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'recevied'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'start_date'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'expiry_date'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'img_path'       => new sfValidatorPass(array('required' => false)),
      'content'        => new sfValidatorPass(array('required' => false)),
      'grant_uid'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'grant_username' => new sfValidatorPass(array('required' => false)),
      'mart'           => new sfValidatorPass(array('required' => false)),
      'receive_url'    => new sfValidatorPass(array('required' => false)),
      'is_delete'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_activity_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllActivity';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'root_type'      => 'Number',
      'title'          => 'Text',
      'total'          => 'Number',
      'list_id'        => 'Number',
      'limits'         => 'Number',
      'recevied'       => 'Number',
      'start_date'     => 'Date',
      'expiry_date'    => 'Date',
      'img_path'       => 'Text',
      'content'        => 'Text',
      'grant_uid'      => 'Number',
      'grant_username' => 'Text',
      'mart'           => 'Text',
      'receive_url'    => 'Text',
      'is_delete'      => 'Boolean',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
