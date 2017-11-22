<?php

/**
 * TrdProduct filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdProductFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hupu_uid'      => new sfWidgetFormFilterInput(),
      'hupu_username' => new sfWidgetFormFilterInput(),
      'trd_brand_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Brand'), 'add_empty' => true)),
      'item_id'       => new sfWidgetFormFilterInput(),
      'name'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'url'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'price'         => new sfWidgetFormFilterInput(),
      'img_url'       => new sfWidgetFormFilterInput(),
      'item_no'       => new sfWidgetFormFilterInput(),
      'size_ids'      => new sfWidgetFormFilterInput(),
      'category_ids'  => new sfWidgetFormFilterInput(),
      'style_ids'     => new sfWidgetFormFilterInput(),
      'color_ids'     => new sfWidgetFormFilterInput(),
      'sold_count'    => new sfWidgetFormFilterInput(),
      'is_soldout'    => new sfWidgetFormFilterInput(),
      'is_hide'       => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'hupu_uid'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username' => new sfValidatorPass(array('required' => false)),
      'trd_brand_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Brand'), 'column' => 'id')),
      'item_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'          => new sfValidatorPass(array('required' => false)),
      'url'           => new sfValidatorPass(array('required' => false)),
      'price'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'img_url'       => new sfValidatorPass(array('required' => false)),
      'item_no'       => new sfValidatorPass(array('required' => false)),
      'size_ids'      => new sfValidatorPass(array('required' => false)),
      'category_ids'  => new sfValidatorPass(array('required' => false)),
      'style_ids'     => new sfValidatorPass(array('required' => false)),
      'color_ids'     => new sfValidatorPass(array('required' => false)),
      'sold_count'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_soldout'    => new sfValidatorPass(array('required' => false)),
      'is_hide'       => new sfValidatorPass(array('required' => false)),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_product_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdProduct';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'hupu_uid'      => 'Number',
      'hupu_username' => 'Text',
      'trd_brand_id'  => 'ForeignKey',
      'item_id'       => 'Number',
      'name'          => 'Text',
      'url'           => 'Text',
      'price'         => 'Number',
      'img_url'       => 'Text',
      'item_no'       => 'Text',
      'size_ids'      => 'Text',
      'category_ids'  => 'Text',
      'style_ids'     => 'Text',
      'color_ids'     => 'Text',
      'sold_count'    => 'Number',
      'is_soldout'    => 'Text',
      'is_hide'       => 'Text',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
