<?php

/**
 * TrdUserRecommend filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdUserRecommendFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'           => new sfWidgetFormFilterInput(),
      'recommend_content' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'recommend_type'    => new sfWidgetFormFilterInput(),
      'recommend_id'      => new sfWidgetFormFilterInput(),
      'create_time'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'is_delete'         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'recommend_content' => new sfValidatorPass(array('required' => false)),
      'recommend_type'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'recommend_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'create_time'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'is_delete'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_user_recommend_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdUserRecommend';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'user_id'           => 'Number',
      'recommend_content' => 'Text',
      'recommend_type'    => 'Number',
      'recommend_id'      => 'Number',
      'create_time'       => 'Date',
      'is_delete'         => 'Number',
    );
  }
}
