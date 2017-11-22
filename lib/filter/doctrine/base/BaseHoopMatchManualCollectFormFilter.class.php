<?php

/**
 * HoopMatchManualCollect filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseHoopMatchManualCollectFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'match_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('hoopMatch'), 'add_empty' => true)),
      'record_type' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'match_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('hoopMatch'), 'column' => 'id')),
      'record_type' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('hoop_match_manual_collect_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'HoopMatchManualCollect';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'match_id'    => 'ForeignKey',
      'record_type' => 'Text',
    );
  }
}
