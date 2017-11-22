<?php

/**
 * KllOperateSpecial filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllOperateSpecialFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'    => new sfWidgetFormFilterInput(),
      'url'      => new sfWidgetFormFilterInput(),
      'position' => new sfWidgetFormFilterInput(),
      'order'    => new sfWidgetFormFilterInput(),
      'opt_uid'  => new sfWidgetFormFilterInput(),
      'add_time' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'title'    => new sfValidatorPass(array('required' => false)),
      'url'      => new sfValidatorPass(array('required' => false)),
      'position' => new sfValidatorPass(array('required' => false)),
      'order'    => new sfValidatorPass(array('required' => false)),
      'opt_uid'  => new sfValidatorPass(array('required' => false)),
      'add_time' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_operate_special_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllOperateSpecial';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'title'    => 'Text',
      'url'      => 'Text',
      'position' => 'Text',
      'order'    => 'Text',
      'opt_uid'  => 'Text',
      'add_time' => 'Text',
    );
  }
}
