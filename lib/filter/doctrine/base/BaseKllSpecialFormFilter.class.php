<?php

/**
 * KllSpecial filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllSpecialFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'cid'         => new sfWidgetFormFilterInput(),
      'title'       => new sfWidgetFormFilterInput(),
      'description' => new sfWidgetFormFilterInput(),
      'opt_uid'     => new sfWidgetFormFilterInput(),
      'position'    => new sfWidgetFormFilterInput(),
      'direct'      => new sfWidgetFormFilterInput(),
      'is_use'      => new sfWidgetFormFilterInput(),
      'add_time'    => new sfWidgetFormFilterInput(),
      'update_time' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'cid'         => new sfValidatorPass(array('required' => false)),
      'title'       => new sfValidatorPass(array('required' => false)),
      'description' => new sfValidatorPass(array('required' => false)),
      'opt_uid'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'position'    => new sfValidatorPass(array('required' => false)),
      'direct'      => new sfValidatorPass(array('required' => false)),
      'is_use'      => new sfValidatorPass(array('required' => false)),
      'add_time'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'update_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_special_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllSpecial';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'cid'         => 'Text',
      'title'       => 'Text',
      'description' => 'Text',
      'opt_uid'     => 'Number',
      'position'    => 'Text',
      'direct'      => 'Text',
      'is_use'      => 'Text',
      'add_time'    => 'Number',
      'update_time' => 'Number',
    );
  }
}
