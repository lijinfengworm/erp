<?php

/**
 * KllItemBrand filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllItemBrandFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'        => new sfWidgetFormFilterInput(),
      'weight'      => new sfWidgetFormFilterInput(),
      'logo'        => new sfWidgetFormFilterInput(),
      'banner'      => new sfWidgetFormFilterInput(),
      'place'       => new sfWidgetFormFilterInput(),
      'place_en'    => new sfWidgetFormFilterInput(),
      'place_flag'  => new sfWidgetFormFilterInput(),
      'description' => new sfWidgetFormFilterInput(),
      'status'      => new sfWidgetFormFilterInput(),
      'ct_time'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'name'        => new sfValidatorPass(array('required' => false)),
      'weight'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'logo'        => new sfValidatorPass(array('required' => false)),
      'banner'      => new sfValidatorPass(array('required' => false)),
      'place'       => new sfValidatorPass(array('required' => false)),
      'place_en'    => new sfValidatorPass(array('required' => false)),
      'place_flag'  => new sfValidatorPass(array('required' => false)),
      'description' => new sfValidatorPass(array('required' => false)),
      'status'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ct_time'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_item_brand_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllItemBrand';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'name'        => 'Text',
      'weight'      => 'Number',
      'logo'        => 'Text',
      'banner'      => 'Text',
      'place'       => 'Text',
      'place_en'    => 'Text',
      'place_flag'  => 'Text',
      'description' => 'Text',
      'status'      => 'Number',
      'ct_time'     => 'Date',
    );
  }
}
