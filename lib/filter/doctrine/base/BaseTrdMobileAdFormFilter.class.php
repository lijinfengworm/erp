<?php

/**
 * TrdMobileAd filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdMobileAdFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'description'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'banner_img_path' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'r_content'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'r_content_color' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'r_url'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'r_color'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'c_content'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'c_content_color' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'c_color'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_cancel'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'grant_uid'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'grant_username'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'start_time'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'end_time'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'description'     => new sfValidatorPass(array('required' => false)),
      'banner_img_path' => new sfValidatorPass(array('required' => false)),
      'r_content'       => new sfValidatorPass(array('required' => false)),
      'r_content_color' => new sfValidatorPass(array('required' => false)),
      'r_url'           => new sfValidatorPass(array('required' => false)),
      'r_color'         => new sfValidatorPass(array('required' => false)),
      'c_content'       => new sfValidatorPass(array('required' => false)),
      'c_content_color' => new sfValidatorPass(array('required' => false)),
      'c_color'         => new sfValidatorPass(array('required' => false)),
      'is_cancel'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'grant_uid'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'grant_username'  => new sfValidatorPass(array('required' => false)),
      'start_time'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'end_time'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_mobile_ad_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdMobileAd';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'description'     => 'Text',
      'banner_img_path' => 'Text',
      'r_content'       => 'Text',
      'r_content_color' => 'Text',
      'r_url'           => 'Text',
      'r_color'         => 'Text',
      'c_content'       => 'Text',
      'c_content_color' => 'Text',
      'c_color'         => 'Text',
      'is_cancel'       => 'Boolean',
      'grant_uid'       => 'Number',
      'grant_username'  => 'Text',
      'start_time'      => 'Date',
      'end_time'        => 'Date',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
