<?php

/**
 * TrdSpecialVanclQuestion filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdSpecialVanclQuestionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'match_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TrdSpecialVanclMatch'), 'add_empty' => true)),
      'question'     => new sfWidgetFormFilterInput(),
      'answer'       => new sfWidgetFormFilterInput(),
      'question_key' => new sfWidgetFormFilterInput(),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'match_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('TrdSpecialVanclMatch'), 'column' => 'id')),
      'question'     => new sfValidatorPass(array('required' => false)),
      'answer'       => new sfValidatorPass(array('required' => false)),
      'question_key' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_special_vancl_question_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdSpecialVanclQuestion';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'match_id'     => 'ForeignKey',
      'question'     => 'Text',
      'answer'       => 'Text',
      'question_key' => 'Number',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}