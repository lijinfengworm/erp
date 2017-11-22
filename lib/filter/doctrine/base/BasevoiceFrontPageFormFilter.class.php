<?php

/**
 * voiceFrontPage filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceFrontPageFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'link'               => new sfWidgetFormFilterInput(),
      'short_link'         => new sfWidgetFormFilterInput(),
      'type'               => new sfWidgetFormFilterInput(),
      'type_id'            => new sfWidgetFormFilterInput(),
      'publisher_uid'      => new sfWidgetFormFilterInput(),
      'publisher_name'     => new sfWidgetFormFilterInput(),
      'attributes'         => new sfWidgetFormFilterInput(),
      'support'            => new sfWidgetFormFilterInput(),
      'agaist'             => new sfWidgetFormFilterInput(),
      'reply_count'        => new sfWidgetFormFilterInput(),
      'light_count'        => new sfWidgetFormFilterInput(),
      'rank'               => new sfWidgetFormFilterInput(),
      'content'            => new sfWidgetFormFilterInput(),
      'hits'               => new sfWidgetFormFilterInput(),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'deleted_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'voice_objects_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'voiceObject')),
    ));

    $this->setValidators(array(
      'title'              => new sfValidatorPass(array('required' => false)),
      'link'               => new sfValidatorPass(array('required' => false)),
      'short_link'         => new sfValidatorPass(array('required' => false)),
      'type'               => new sfValidatorPass(array('required' => false)),
      'type_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'publisher_uid'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'publisher_name'     => new sfValidatorPass(array('required' => false)),
      'attributes'         => new sfValidatorPass(array('required' => false)),
      'support'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'agaist'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reply_count'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'light_count'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rank'               => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'content'            => new sfValidatorPass(array('required' => false)),
      'hits'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'deleted_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'voice_objects_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'voiceObject', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('voice_front_page_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addVoiceObjectsListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.voiceObjectFrontPage voiceObjectFrontPage')
      ->andWhereIn('voiceObjectFrontPage.voice_object_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'voiceFrontPage';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'title'              => 'Text',
      'link'               => 'Text',
      'short_link'         => 'Text',
      'type'               => 'Text',
      'type_id'            => 'Number',
      'publisher_uid'      => 'Number',
      'publisher_name'     => 'Text',
      'attributes'         => 'Text',
      'support'            => 'Number',
      'agaist'             => 'Number',
      'reply_count'        => 'Number',
      'light_count'        => 'Number',
      'rank'               => 'Number',
      'content'            => 'Text',
      'hits'               => 'Number',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
      'deleted_at'         => 'Date',
      'voice_objects_list' => 'ManyKey',
    );
  }
}
