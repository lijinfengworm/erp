<?php

/**
 * KllAd filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllAdFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'att_id'   => new sfWidgetFormFilterInput(),
      'position' => new sfWidgetFormFilterInput(),
      'opt_uid'  => new sfWidgetFormFilterInput(),
      'abstract' => new sfWidgetFormFilterInput(),
      'add_time' => new sfWidgetFormFilterInput(),
      'url'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'att_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'position' => new sfValidatorPass(array('required' => false)),
      'opt_uid'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'abstract' => new sfValidatorPass(array('required' => false)),
      'add_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'url'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_ad_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllAd';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'att_id'   => 'Number',
      'position' => 'Text',
      'opt_uid'  => 'Number',
      'abstract' => 'Text',
      'add_time' => 'Number',
      'url'      => 'Text',
    );
  }
}
