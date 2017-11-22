<?php

/**
 * TrdGrouponProduct filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdGrouponProductFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'brand_id'     => new sfWidgetFormFilterInput(),
      'title'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'attend_count' => new sfWidgetFormFilterInput(),
      'discount'     => new sfWidgetFormFilterInput(),
      'category_id'  => new sfWidgetFormFilterInput(),
      'start_time'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'end_time'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'attr_collect' => new sfWidgetFormFilterInput(),
      'rank'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'price'        => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'brand_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'        => new sfValidatorPass(array('required' => false)),
      'attend_count' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'discount'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'category_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'start_time'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'end_time'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'attr_collect' => new sfValidatorPass(array('required' => false)),
      'rank'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'price'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_groupon_product_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGrouponProduct';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'brand_id'     => 'Number',
      'title'        => 'Text',
      'attend_count' => 'Number',
      'discount'     => 'Number',
      'category_id'  => 'Number',
      'start_time'   => 'Date',
      'end_time'     => 'Date',
      'attr_collect' => 'Text',
      'rank'         => 'Number',
      'price'        => 'Number',
    );
  }
}
