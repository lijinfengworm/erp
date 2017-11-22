<?php

/**
 * KaluliItem filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKaluliItemFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'          => new sfWidgetFormFilterInput(),
      'pic'            => new sfWidgetFormFilterInput(),
      'brand_id'       => new sfWidgetFormFilterInput(),
      'sell_point'     => new sfWidgetFormFilterInput(),
      'intro'          => new sfWidgetFormFilterInput(),
      'price'          => new sfWidgetFormFilterInput(),
      'discount_price' => new sfWidgetFormFilterInput(),
      'hits'           => new sfWidgetFormFilterInput(),
      'status'         => new sfWidgetFormFilterInput(),
      'status_es'      => new sfWidgetFormFilterInput(),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'title'          => new sfValidatorPass(array('required' => false)),
      'pic'            => new sfValidatorPass(array('required' => false)),
      'brand_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sell_point'     => new sfValidatorPass(array('required' => false)),
      'intro'          => new sfValidatorPass(array('required' => false)),
      'price'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'discount_price' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'hits'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status_es'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kaluli_item_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliItem';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'title'          => 'Text',
      'pic'            => 'Text',
      'brand_id'       => 'Number',
      'sell_point'     => 'Text',
      'intro'          => 'Text',
      'price'          => 'Number',
      'discount_price' => 'Number',
      'hits'           => 'Number',
      'status'         => 'Number',
      'status_es'      => 'Number',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
