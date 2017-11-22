<?php

/**
 * voiceObject filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceObjectFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'intro'                 => new sfWidgetFormFilterInput(),
      'slug'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'head_url'              => new sfWidgetFormFilterInput(),
      'voice_tag_num'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'last_update_time'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'attr'                  => new sfWidgetFormFilterInput(),
      'type'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'voice_front_page_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'voiceFrontPage')),
      'voice_tag_list'        => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'voiceTag')),
    ));

    $this->setValidators(array(
      'name'                  => new sfValidatorPass(array('required' => false)),
      'intro'                 => new sfValidatorPass(array('required' => false)),
      'slug'                  => new sfValidatorPass(array('required' => false)),
      'head_url'              => new sfValidatorPass(array('required' => false)),
      'voice_tag_num'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'last_update_time'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'attr'                  => new sfValidatorPass(array('required' => false)),
      'type'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'voice_front_page_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'voiceFrontPage', 'required' => false)),
      'voice_tag_list'        => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'voiceTag', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('voice_object_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addVoiceFrontPageListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('voiceObjectFrontPage.voice_front_page_id', $values)
    ;
  }

  public function addVoiceTagListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.voiceObjectTag voiceObjectTag')
      ->andWhereIn('voiceObjectTag.voice_tag_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'voiceObject';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'name'                  => 'Text',
      'intro'                 => 'Text',
      'slug'                  => 'Text',
      'head_url'              => 'Text',
      'voice_tag_num'         => 'Number',
      'last_update_time'      => 'Date',
      'attr'                  => 'Text',
      'type'                  => 'Number',
      'created_at'            => 'Date',
      'updated_at'            => 'Date',
      'voice_front_page_list' => 'ManyKey',
      'voice_tag_list'        => 'ManyKey',
    );
  }
}
