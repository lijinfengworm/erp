<?php

/**
 * KllComment filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllCommentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'cid'        => new sfWidgetFormFilterInput(),
      'product_id' => new sfWidgetFormFilterInput(),
      'user_id'    => new sfWidgetFormFilterInput(),
      'user_name'  => new sfWidgetFormFilterInput(),
      'content'    => new sfWidgetFormFilterInput(),
      'imgs'       => new sfWidgetFormFilterInput(),
      'tags_attr'  => new sfWidgetFormFilterInput(),
      'attr'       => new sfWidgetFormFilterInput(),
      'status'     => new sfWidgetFormFilterInput(),
      'private'    => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'cid'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'product_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_name'  => new sfValidatorPass(array('required' => false)),
      'content'    => new sfValidatorPass(array('required' => false)),
      'imgs'       => new sfValidatorPass(array('required' => false)),
      'tags_attr'  => new sfValidatorPass(array('required' => false)),
      'attr'       => new sfValidatorPass(array('required' => false)),
      'status'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'private'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_comment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllComment';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'cid'        => 'Number',
      'product_id' => 'Number',
      'user_id'    => 'Number',
      'user_name'  => 'Text',
      'content'    => 'Text',
      'imgs'       => 'Text',
      'tags_attr'  => 'Text',
      'attr'       => 'Text',
      'status'     => 'Number',
      'private'    => 'Number',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
