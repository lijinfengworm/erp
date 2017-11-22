<?php

/**
 * voiceVote filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceVoteFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'              => new sfWidgetFormFilterInput(),
      'intro'              => new sfWidgetFormFilterInput(),
      'type'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'vote_from'          => new sfWidgetFormFilterInput(),
      'user_select'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_select_number' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'vote_number'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'img_link'           => new sfWidgetFormFilterInput(),
      'img_path'           => new sfWidgetFormFilterInput(),
      'result_visible'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'end_date'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'attributes'         => new sfWidgetFormFilterInput(),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'title'              => new sfValidatorPass(array('required' => false)),
      'intro'              => new sfValidatorPass(array('required' => false)),
      'type'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'vote_from'          => new sfValidatorPass(array('required' => false)),
      'user_select'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_select_number' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'vote_number'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'img_link'           => new sfValidatorPass(array('required' => false)),
      'img_path'           => new sfValidatorPass(array('required' => false)),
      'result_visible'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'end_date'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'attributes'         => new sfValidatorPass(array('required' => false)),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('voice_vote_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceVote';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'title'              => 'Text',
      'intro'              => 'Text',
      'type'               => 'Number',
      'vote_from'          => 'Text',
      'user_select'        => 'Number',
      'user_select_number' => 'Number',
      'vote_number'        => 'Number',
      'img_link'           => 'Text',
      'img_path'           => 'Text',
      'result_visible'     => 'Number',
      'end_date'           => 'Date',
      'attributes'         => 'Text',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
    );
  }
}
