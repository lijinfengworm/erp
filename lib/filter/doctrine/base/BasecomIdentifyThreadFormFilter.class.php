<?php

/**
 * comIdentifyThread filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasecomIdentifyThreadFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'sites_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('site'), 'add_empty' => true)),
      'object_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'sites_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('site'), 'column' => 'id')),
      'object_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('com_identify_thread_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'comIdentifyThread';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'sites_id'  => 'ForeignKey',
      'object_id' => 'Number',
    );
  }
}
