<?php

/**
 * ErMessage filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseErMessageFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'parent_id'       => new sfWidgetFormFilterInput(),
      'object_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Object'), 'add_empty' => true)),
      'role_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Role'), 'add_empty' => true)),
      'title'           => new sfWidgetFormFilterInput(),
      'tid'             => new sfWidgetFormFilterInput(),
      'content'         => new sfWidgetFormFilterInput(),
      'visit_count'     => new sfWidgetFormFilterInput(),
      'last_reply_time' => new sfWidgetFormFilterInput(),
      'is_show'         => new sfWidgetFormFilterInput(),
      'is_mention'      => new sfWidgetFormFilterInput(),
      'is_mention_show' => new sfWidgetFormFilterInput(),
      'status'          => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'parent_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'object_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Object'), 'column' => 'id')),
      'role_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Role'), 'column' => 'id')),
      'title'           => new sfValidatorPass(array('required' => false)),
      'tid'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'content'         => new sfValidatorPass(array('required' => false)),
      'visit_count'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'last_reply_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_show'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_mention'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_mention_show' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('er_message_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ErMessage';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'parent_id'       => 'Number',
      'object_id'       => 'ForeignKey',
      'role_id'         => 'ForeignKey',
      'title'           => 'Text',
      'tid'             => 'Number',
      'content'         => 'Text',
      'visit_count'     => 'Number',
      'last_reply_time' => 'Number',
      'is_show'         => 'Number',
      'is_mention'      => 'Number',
      'is_mention_show' => 'Number',
      'status'          => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}