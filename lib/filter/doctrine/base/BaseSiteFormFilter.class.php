<?php

/**
 * Site filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSiteFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'url'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'name' => new sfValidatorPass(array('required' => false)),
      'url'  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('site_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Site';
  }

  public function getFields()
  {
    return array(
      'id'   => 'Number',
      'name' => 'Text',
      'url'  => 'Text',
    );
  }
}
