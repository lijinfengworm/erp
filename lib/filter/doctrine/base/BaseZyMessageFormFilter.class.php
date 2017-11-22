<?php

/**
 * ZyMessage filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseZyMessageFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hupu_uid'        => new sfWidgetFormFilterInput(),
      'role_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Role'), 'add_empty' => true)),
      'title'           => new sfWidgetFormFilterInput(),
      'tid'             => new sfWidgetFormFilterInput(),
      'content'         => new sfWidgetFormFilterInput(),
      'visit_count'     => new sfWidgetFormFilterInput(),
      'reply_count'     => new sfWidgetFormFilterInput(),
      'light_count'     => new sfWidgetFormFilterInput(),
      'rank'            => new sfWidgetFormFilterInput(),
      'last_reply_time' => new sfWidgetFormFilterInput(),
      'good_count'      => new sfWidgetFormFilterInput(),
      'status'          => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'hupu_uid'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'role_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Role'), 'column' => 'id')),
      'title'           => new sfValidatorPass(array('required' => false)),
      'tid'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'content'         => new sfValidatorPass(array('required' => false)),
      'visit_count'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reply_count'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'light_count'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rank'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'last_reply_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'good_count'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('zy_message_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZyMessage';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'hupu_uid'        => 'Number',
      'role_id'         => 'ForeignKey',
      'title'           => 'Text',
      'tid'             => 'Number',
      'content'         => 'Text',
      'visit_count'     => 'Number',
      'reply_count'     => 'Number',
      'light_count'     => 'Number',
      'rank'            => 'Number',
      'last_reply_time' => 'Number',
      'good_count'      => 'Number',
      'status'          => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
