<?php

/**
 * KllBanner filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllBannerFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'att_id'   => new sfWidgetFormFilterInput(),
      'title'    => new sfWidgetFormFilterInput(),
      'abstract' => new sfWidgetFormFilterInput(),
      'add_time' => new sfWidgetFormFilterInput(),
      'url'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'att_id'   => new sfValidatorPass(array('required' => false)),
      'title'    => new sfValidatorPass(array('required' => false)),
      'abstract' => new sfValidatorPass(array('required' => false)),
      'add_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'url'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_banner_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllBanner';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'att_id'   => 'Text',
      'title'    => 'Text',
      'abstract' => 'Text',
      'add_time' => 'Number',
      'url'      => 'Text',
    );
  }
}
