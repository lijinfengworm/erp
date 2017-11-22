<?php

/**
 * TrdSeoNews filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdSeoNewsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'           => new sfWidgetFormFilterInput(),
      'orginal_url'     => new sfWidgetFormFilterInput(),
      'orginal_type'    => new sfWidgetFormFilterInput(),
      'product_id'      => new sfWidgetFormFilterInput(),
      'publish_date'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'price'           => new sfWidgetFormFilterInput(),
      'is_delete'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'hits'            => new sfWidgetFormFilterInput(),
      'reply_count'     => new sfWidgetFormFilterInput(),
      'light_count'     => new sfWidgetFormFilterInput(),
      'last_reply_date' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'img_link'        => new sfWidgetFormFilterInput(),
      'img_path'        => new sfWidgetFormFilterInput(),
      'author_id'       => new sfWidgetFormFilterInput(),
      'editor_id'       => new sfWidgetFormFilterInput(),
      'subtitle'        => new sfWidgetFormFilterInput(),
      'spreadtitle'     => new sfWidgetFormFilterInput(),
      'support'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'against'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'praise'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'root_type'       => new sfWidgetFormFilterInput(),
      'root_id'         => new sfWidgetFormFilterInput(),
      'children_id'     => new sfWidgetFormFilterInput(),
      'type'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'store_id'        => new sfWidgetFormFilterInput(),
      'brand_id'        => new sfWidgetFormFilterInput(),
      'tags_attr'       => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'title'           => new sfValidatorPass(array('required' => false)),
      'orginal_url'     => new sfValidatorPass(array('required' => false)),
      'orginal_type'    => new sfValidatorPass(array('required' => false)),
      'product_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'publish_date'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'price'           => new sfValidatorPass(array('required' => false)),
      'is_delete'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'hits'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reply_count'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'light_count'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'last_reply_date' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'img_link'        => new sfValidatorPass(array('required' => false)),
      'img_path'        => new sfValidatorPass(array('required' => false)),
      'author_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'editor_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'subtitle'        => new sfValidatorPass(array('required' => false)),
      'spreadtitle'     => new sfValidatorPass(array('required' => false)),
      'support'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'against'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'praise'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'root_type'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'root_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'children_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'store_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'brand_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tags_attr'       => new sfValidatorPass(array('required' => false)),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_seo_news_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdSeoNews';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'title'           => 'Text',
      'orginal_url'     => 'Text',
      'orginal_type'    => 'Text',
      'product_id'      => 'Number',
      'publish_date'    => 'Date',
      'price'           => 'Text',
      'is_delete'       => 'Boolean',
      'hits'            => 'Number',
      'reply_count'     => 'Number',
      'light_count'     => 'Number',
      'last_reply_date' => 'Date',
      'img_link'        => 'Text',
      'img_path'        => 'Text',
      'author_id'       => 'Number',
      'editor_id'       => 'Number',
      'subtitle'        => 'Text',
      'spreadtitle'     => 'Text',
      'support'         => 'Number',
      'against'         => 'Number',
      'praise'          => 'Number',
      'root_type'       => 'Number',
      'root_id'         => 'Number',
      'children_id'     => 'Number',
      'type'            => 'Number',
      'store_id'        => 'Number',
      'brand_id'        => 'Number',
      'tags_attr'       => 'Text',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
