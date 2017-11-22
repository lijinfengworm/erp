<?php

/**
 * ZyMention filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseZyMentionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'object_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Object'), 'add_empty' => true)),
      'role_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Role'), 'add_empty' => true)),
      'message_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Message'), 'add_empty' => true)),
      'reply_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Reply'), 'add_empty' => true)),
      'last_reply_time' => new sfWidgetFormFilterInput(),
      'is_mention'      => new sfWidgetFormFilterInput(),
      'is_mention_show' => new sfWidgetFormFilterInput(),
      'status'          => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'object_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Object'), 'column' => 'id')),
      'role_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Role'), 'column' => 'id')),
      'message_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Message'), 'column' => 'id')),
      'reply_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Reply'), 'column' => 'id')),
      'last_reply_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_mention'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_mention_show' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('zy_mention_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZyMention';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'object_id'       => 'ForeignKey',
      'role_id'         => 'ForeignKey',
      'message_id'      => 'ForeignKey',
      'reply_id'        => 'ForeignKey',
      'last_reply_time' => 'Number',
      'is_mention'      => 'Number',
      'is_mention_show' => 'Number',
      'status'          => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
