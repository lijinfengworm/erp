<?php

/**
 * voiceAppNotificList filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceAppNotificListFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'project'    => new sfWidgetFormFilterInput(),
      'tag_ids'    => new sfWidgetFormFilterInput(),
      'title'      => new sfWidgetFormFilterInput(),
      'url'        => new sfWidgetFormFilterInput(),
      'intro'      => new sfWidgetFormFilterInput(),
      'type'       => new sfWidgetFormFilterInput(),
      'type_id'    => new sfWidgetFormFilterInput(),
      'is_send'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'project'    => new sfValidatorPass(array('required' => false)),
      'tag_ids'    => new sfValidatorPass(array('required' => false)),
      'title'      => new sfValidatorPass(array('required' => false)),
      'url'        => new sfValidatorPass(array('required' => false)),
      'intro'      => new sfValidatorPass(array('required' => false)),
      'type'       => new sfValidatorPass(array('required' => false)),
      'type_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_send'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('voice_app_notific_list_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceAppNotificList';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'project'    => 'Text',
      'tag_ids'    => 'Text',
      'title'      => 'Text',
      'url'        => 'Text',
      'intro'      => 'Text',
      'type'       => 'Text',
      'type_id'    => 'Number',
      'is_send'    => 'Number',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
