<?php

/**
 * ZbProducts filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseZbProductsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'brand_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ZbBrand'), 'add_empty' => true)),
      'series_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ZbSeries'), 'add_empty' => true)),
      'categroy_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ZbCategroies'), 'add_empty' => true)),
      'name'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'number'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'img_src'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'buy_count'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'reply_count' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'color_ids'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'like_count'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'click_count' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'point'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sex'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'memo'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'price'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'realprice'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'attr'        => new sfWidgetFormFilterInput(),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'deleted_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'brand_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ZbBrand'), 'column' => 'id')),
      'series_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ZbSeries'), 'column' => 'id')),
      'categroy_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ZbCategroies'), 'column' => 'id')),
      'name'        => new sfValidatorPass(array('required' => false)),
      'number'      => new sfValidatorPass(array('required' => false)),
      'img_src'     => new sfValidatorPass(array('required' => false)),
      'buy_count'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reply_count' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'color_ids'   => new sfValidatorPass(array('required' => false)),
      'like_count'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'click_count' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'point'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'sex'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'memo'        => new sfValidatorPass(array('required' => false)),
      'price'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'realprice'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'attr'        => new sfValidatorPass(array('required' => false)),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'deleted_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('zb_products_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZbProducts';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'brand_id'    => 'ForeignKey',
      'series_id'   => 'ForeignKey',
      'categroy_id' => 'ForeignKey',
      'name'        => 'Text',
      'number'      => 'Text',
      'img_src'     => 'Text',
      'buy_count'   => 'Number',
      'reply_count' => 'Number',
      'color_ids'   => 'Text',
      'like_count'  => 'Number',
      'click_count' => 'Number',
      'point'       => 'Number',
      'sex'         => 'Number',
      'memo'        => 'Text',
      'price'       => 'Number',
      'realprice'   => 'Number',
      'attr'        => 'Text',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
      'deleted_at'  => 'Date',
    );
  }
}
