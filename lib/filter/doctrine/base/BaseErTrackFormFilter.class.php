<?php

/**
 * ErTrack filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseErTrackFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hupu_uid'      => new sfWidgetFormFilterInput(),
      'hupu_username' => new sfWidgetFormFilterInput(),
      'role_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Role'), 'add_empty' => true)),
      'object_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Object'), 'add_empty' => true)),
      'message_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Message'), 'add_empty' => true)),
      'action'        => new sfWidgetFormFilterInput(),
      'url'           => new sfWidgetFormFilterInput(),
      'time'          => new sfWidgetFormFilterInput(),
      'status'        => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'hupu_uid'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username' => new sfValidatorPass(array('required' => false)),
      'role_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Role'), 'column' => 'id')),
      'object_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Object'), 'column' => 'id')),
      'message_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Message'), 'column' => 'id')),
      'action'        => new sfValidatorPass(array('required' => false)),
      'url'           => new sfValidatorPass(array('required' => false)),
      'time'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('er_track_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ErTrack';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'hupu_uid'      => 'Number',
      'hupu_username' => 'Text',
      'role_id'       => 'ForeignKey',
      'object_id'     => 'ForeignKey',
      'message_id'    => 'ForeignKey',
      'action'        => 'Text',
      'url'           => 'Text',
      'time'          => 'Number',
      'status'        => 'Number',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
