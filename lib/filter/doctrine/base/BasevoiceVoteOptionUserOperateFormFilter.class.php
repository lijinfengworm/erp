<?php

/**
 * voiceVoteOptionUserOperate filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceVoteOptionUserOperateFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'voice_vote_option_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('voiceVoteOption'), 'add_empty' => true)),
      'operate_uid'          => new sfWidgetFormFilterInput(),
      'operate_date'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'attributes'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'voice_vote_option_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('voiceVoteOption'), 'column' => 'id')),
      'operate_uid'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'operate_date'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'attributes'           => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('voice_vote_option_user_operate_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceVoteOptionUserOperate';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'voice_vote_option_id' => 'ForeignKey',
      'operate_uid'          => 'Number',
      'operate_date'         => 'Date',
      'attributes'           => 'Text',
    );
  }
}
