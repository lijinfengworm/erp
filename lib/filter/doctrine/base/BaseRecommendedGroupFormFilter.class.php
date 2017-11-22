<?php

/**
 * RecommendedGroup filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseRecommendedGroupFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'hasNewContent' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'footer_html'   => new sfWidgetFormFilterInput(),
      'html_content'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'title'         => new sfValidatorPass(array('required' => false)),
      'hasNewContent' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'footer_html'   => new sfValidatorPass(array('required' => false)),
      'html_content'  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('recommended_group_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'RecommendedGroup';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'title'         => 'Text',
      'hasNewContent' => 'Boolean',
      'footer_html'   => 'Text',
      'html_content'  => 'Text',
    );
  }
}
