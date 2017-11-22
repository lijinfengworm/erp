<?php

/**
 * trdFind filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasetrdFindFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'        => new sfWidgetFormFilterInput(),
      'text'         => new sfWidgetFormFilterInput(),
      'price'        => new sfWidgetFormFilterInput(),
      'root_id'      => new sfWidgetFormFilterInput(),
      'children_id'  => new sfWidgetFormFilterInput(),
      'orginal_url'  => new sfWidgetFormFilterInput(),
      'orginal_type' => new sfWidgetFormFilterInput(),
      'store_id'     => new sfWidgetFormFilterInput(),
      'imgs_attr'    => new sfWidgetFormFilterInput(),
      'tags_attr'    => new sfWidgetFormFilterInput(),
      'reply_count'  => new sfWidgetFormFilterInput(),
      'like_count'   => new sfWidgetFormFilterInput(),
      'status'       => new sfWidgetFormFilterInput(),
      'hits'         => new sfWidgetFormFilterInput(),
      'author_id'    => new sfWidgetFormFilterInput(),
      'audit_id'     => new sfWidgetFormFilterInput(),
      'rank'         => new sfWidgetFormFilterInput(),
      'publish_date' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'title'        => new sfValidatorPass(array('required' => false)),
      'text'         => new sfValidatorPass(array('required' => false)),
      'price'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'root_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'children_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'orginal_url'  => new sfValidatorPass(array('required' => false)),
      'orginal_type' => new sfValidatorPass(array('required' => false)),
      'store_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'imgs_attr'    => new sfValidatorPass(array('required' => false)),
      'tags_attr'    => new sfValidatorPass(array('required' => false)),
      'reply_count'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'like_count'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hits'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'author_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'audit_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rank'         => new sfValidatorPass(array('required' => false)),
      'publish_date' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_find_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'trdFind';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'title'        => 'Text',
      'text'         => 'Text',
      'price'        => 'Number',
      'root_id'      => 'Number',
      'children_id'  => 'Number',
      'orginal_url'  => 'Text',
      'orginal_type' => 'Text',
      'store_id'     => 'Number',
      'imgs_attr'    => 'Text',
      'tags_attr'    => 'Text',
      'reply_count'  => 'Number',
      'like_count'   => 'Number',
      'status'       => 'Number',
      'hits'         => 'Number',
      'author_id'    => 'Number',
      'audit_id'     => 'Number',
      'rank'         => 'Text',
      'publish_date' => 'Date',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
