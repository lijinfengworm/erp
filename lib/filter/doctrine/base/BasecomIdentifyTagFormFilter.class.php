<?php

/**
 * comIdentifyTag filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasecomIdentifyTagFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'com_identify_thread_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('comIdentifyThread'), 'add_empty' => true)),
      'user_id'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'message'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'click_numbers'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'com_identify_thread_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('comIdentifyThread'), 'column' => 'id')),
      'user_id'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'message'                => new sfValidatorPass(array('required' => false)),
      'click_numbers'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('com_identify_tag_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'comIdentifyTag';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'com_identify_thread_id' => 'ForeignKey',
      'user_id'                => 'Number',
      'message'                => 'Text',
      'click_numbers'          => 'Number',
    );
  }
}
