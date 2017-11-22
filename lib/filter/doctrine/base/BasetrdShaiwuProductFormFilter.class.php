<?php

/**
 * trdShaiwuProduct filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasetrdShaiwuProductFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'         => new sfWidgetFormFilterInput(),
      'intro'         => new sfWidgetFormFilterInput(),
      'author_id'     => new sfWidgetFormFilterInput(),
      'author_name'   => new sfWidgetFormFilterInput(),
      'is_star'       => new sfWidgetFormFilterInput(),
      'type'          => new sfWidgetFormFilterInput(),
      'root_id'       => new sfWidgetFormFilterInput(),
      'children_id'   => new sfWidgetFormFilterInput(),
      'is_hot'        => new sfWidgetFormFilterInput(),
      'brand_id'      => new sfWidgetFormFilterInput(),
      'model'         => new sfWidgetFormFilterInput(),
      'gold'          => new sfWidgetFormFilterInput(),
      'tag_ids'       => new sfWidgetFormFilterInput(),
      'support'       => new sfWidgetFormFilterInput(),
      'agaist'        => new sfWidgetFormFilterInput(),
      'comment_count' => new sfWidgetFormFilterInput(),
      'front_pic'     => new sfWidgetFormFilterInput(),
      'status'        => new sfWidgetFormFilterInput(),
      'status_reason' => new sfWidgetFormFilterInput(),
      'hits'          => new sfWidgetFormFilterInput(),
      'activity_id'   => new sfWidgetFormFilterInput(),
      'publish_time'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'source'        => new sfWidgetFormFilterInput(),
      'rank'          => new sfWidgetFormFilterInput(),
      'img_attr'      => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'title'         => new sfValidatorPass(array('required' => false)),
      'intro'         => new sfValidatorPass(array('required' => false)),
      'author_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'author_name'   => new sfValidatorPass(array('required' => false)),
      'is_star'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'root_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'children_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_hot'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'brand_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'model'         => new sfValidatorPass(array('required' => false)),
      'gold'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tag_ids'       => new sfValidatorPass(array('required' => false)),
      'support'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'agaist'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comment_count' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'front_pic'     => new sfValidatorPass(array('required' => false)),
      'status'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status_reason' => new sfValidatorPass(array('required' => false)),
      'hits'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'activity_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'publish_time'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'source'        => new sfValidatorPass(array('required' => false)),
      'rank'          => new sfValidatorPass(array('required' => false)),
      'img_attr'      => new sfValidatorPass(array('required' => false)),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_shaiwu_product_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'trdShaiwuProduct';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'title'         => 'Text',
      'intro'         => 'Text',
      'author_id'     => 'Number',
      'author_name'   => 'Text',
      'is_star'       => 'Number',
      'type'          => 'Number',
      'root_id'       => 'Number',
      'children_id'   => 'Number',
      'is_hot'        => 'Number',
      'brand_id'      => 'Number',
      'model'         => 'Text',
      'gold'          => 'Number',
      'tag_ids'       => 'Text',
      'support'       => 'Number',
      'agaist'        => 'Number',
      'comment_count' => 'Number',
      'front_pic'     => 'Text',
      'status'        => 'Number',
      'status_reason' => 'Text',
      'hits'          => 'Number',
      'activity_id'   => 'Number',
      'publish_time'  => 'Date',
      'source'        => 'Text',
      'rank'          => 'Text',
      'img_attr'      => 'Text',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
