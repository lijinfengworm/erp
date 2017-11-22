<?php

/**
 * KllTalent filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllTalentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'     => new sfWidgetFormFilterInput(),
      'job'      => new sfWidgetFormFilterInput(),
      'sex'      => new sfWidgetFormFilterInput(),
      'h_id'     => new sfWidgetFormFilterInput(),
      'att_id'   => new sfWidgetFormFilterInput(),
      'interest' => new sfWidgetFormFilterInput(),
      'add_time' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'     => new sfValidatorPass(array('required' => false)),
      'job'      => new sfValidatorPass(array('required' => false)),
      'sex'      => new sfValidatorPass(array('required' => false)),
      'h_id'     => new sfValidatorPass(array('required' => false)),
      'att_id'   => new sfValidatorPass(array('required' => false)),
      'interest' => new sfValidatorPass(array('required' => false)),
      'add_time' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_talent_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllTalent';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'name'     => 'Text',
      'job'      => 'Text',
      'sex'      => 'Text',
      'h_id'     => 'Text',
      'att_id'   => 'Text',
      'interest' => 'Text',
      'add_time' => 'Text',
    );
  }
}
