<?php

/**
 * TrdAppBigsale filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdAppBigsaleFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'banner_img_path'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'background_color' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'imgs'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'price'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'original_price'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'      => new sfWidgetFormFilterInput(),
      'go_url'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_delete'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'share_content'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'title'            => new sfValidatorPass(array('required' => false)),
      'banner_img_path'  => new sfValidatorPass(array('required' => false)),
      'background_color' => new sfValidatorPass(array('required' => false)),
      'imgs'             => new sfValidatorPass(array('required' => false)),
      'price'            => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'original_price'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'description'      => new sfValidatorPass(array('required' => false)),
      'go_url'           => new sfValidatorPass(array('required' => false)),
      'is_delete'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'share_content'    => new sfValidatorPass(array('required' => false)),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_app_bigsale_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAppBigsale';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'title'            => 'Text',
      'banner_img_path'  => 'Text',
      'background_color' => 'Text',
      'imgs'             => 'Text',
      'price'            => 'Number',
      'original_price'   => 'Number',
      'description'      => 'Text',
      'go_url'           => 'Text',
      'is_delete'        => 'Boolean',
      'share_content'    => 'Text',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
    );
  }
}
