<?php

/**
 * TrdAppActivity filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdAppActivityFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'img_path'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'price'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'original_price' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'quantity'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'unit'           => new sfWidgetFormFilterInput(),
      'limit'          => new sfWidgetFormFilterInput(),
      'received'       => new sfWidgetFormFilterInput(),
      'start_time'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'end_time'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'description'    => new sfWidgetFormFilterInput(),
      'go_url'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'business'       => new sfWidgetFormFilterInput(),
      'business_url'   => new sfWidgetFormFilterInput(),
      'is_delete'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'grant_uid'      => new sfWidgetFormFilterInput(),
      'grant_username' => new sfWidgetFormFilterInput(),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'title'          => new sfValidatorPass(array('required' => false)),
      'img_path'       => new sfValidatorPass(array('required' => false)),
      'price'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'original_price' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'quantity'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'unit'           => new sfValidatorPass(array('required' => false)),
      'limit'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'received'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'start_time'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'end_time'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'description'    => new sfValidatorPass(array('required' => false)),
      'go_url'         => new sfValidatorPass(array('required' => false)),
      'business'       => new sfValidatorPass(array('required' => false)),
      'business_url'   => new sfValidatorPass(array('required' => false)),
      'is_delete'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'grant_uid'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'grant_username' => new sfValidatorPass(array('required' => false)),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_app_activity_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAppActivity';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'title'          => 'Text',
      'img_path'       => 'Text',
      'price'          => 'Number',
      'original_price' => 'Number',
      'quantity'       => 'Number',
      'unit'           => 'Text',
      'limit'          => 'Number',
      'received'       => 'Number',
      'start_time'     => 'Date',
      'end_time'       => 'Date',
      'description'    => 'Text',
      'go_url'         => 'Text',
      'business'       => 'Text',
      'business_url'   => 'Text',
      'is_delete'      => 'Boolean',
      'grant_uid'      => 'Number',
      'grant_username' => 'Text',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
