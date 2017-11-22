<?php

/**
 * comIdentifyTagFan filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasecomIdentifyTagFanFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'com_identify_tag_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('comIdentifyTag'), 'add_empty' => true)),
      'team_id'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'times'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'com_identify_tag_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('comIdentifyTag'), 'column' => 'id')),
      'team_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'times'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('com_identify_tag_fan_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'comIdentifyTagFan';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'com_identify_tag_id' => 'ForeignKey',
      'team_id'             => 'Number',
      'times'               => 'Number',
    );
  }
}
