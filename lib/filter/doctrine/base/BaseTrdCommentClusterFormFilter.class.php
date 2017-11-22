<?php

/**
 * TrdCommentCluster filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdCommentClusterFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'product_id'  => new sfWidgetFormFilterInput(),
      'comment_id'  => new sfWidgetFormFilterInput(),
      'reply_id'    => new sfWidgetFormFilterInput(),
      'user_id'     => new sfWidgetFormFilterInput(),
      'to_userid'   => new sfWidgetFormFilterInput(),
      'user_name'   => new sfWidgetFormFilterInput(),
      'to_username' => new sfWidgetFormFilterInput(),
      'content'     => new sfWidgetFormFilterInput(),
      'imgs_attr'   => new sfWidgetFormFilterInput(),
      'ip'          => new sfWidgetFormFilterInput(),
      'status'      => new sfWidgetFormFilterInput(),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'product_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comment_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reply_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'to_userid'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_name'   => new sfValidatorPass(array('required' => false)),
      'to_username' => new sfValidatorPass(array('required' => false)),
      'content'     => new sfValidatorPass(array('required' => false)),
      'imgs_attr'   => new sfValidatorPass(array('required' => false)),
      'ip'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_comment_cluster_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdCommentCluster';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'product_id'  => 'Number',
      'comment_id'  => 'Number',
      'reply_id'    => 'Number',
      'user_id'     => 'Number',
      'to_userid'   => 'Number',
      'user_name'   => 'Text',
      'to_username' => 'Text',
      'content'     => 'Text',
      'imgs_attr'   => 'Text',
      'ip'          => 'Number',
      'status'      => 'Number',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
