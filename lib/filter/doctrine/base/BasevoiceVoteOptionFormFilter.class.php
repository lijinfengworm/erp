<?php

/**
 * voiceVoteOption filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceVoteOptionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'voice_vote_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('voiceVote'), 'add_empty' => true)),
      'content'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'publish_uid'   => new sfWidgetFormFilterInput(),
      'vote_number'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'img_link'      => new sfWidgetFormFilterInput(),
      'img_path'      => new sfWidgetFormFilterInput(),
      'attributes'    => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'voice_vote_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('voiceVote'), 'column' => 'id')),
      'content'       => new sfValidatorPass(array('required' => false)),
      'publish_uid'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'vote_number'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'img_link'      => new sfValidatorPass(array('required' => false)),
      'img_path'      => new sfValidatorPass(array('required' => false)),
      'attributes'    => new sfValidatorPass(array('required' => false)),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('voice_vote_option_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceVoteOption';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'voice_vote_id' => 'ForeignKey',
      'content'       => 'Text',
      'publish_uid'   => 'Number',
      'vote_number'   => 'Number',
      'img_link'      => 'Text',
      'img_path'      => 'Text',
      'attributes'    => 'Text',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
