<?php

/**
 * BlackKeyword filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseBlackKeywordFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'keyword'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_enable' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'keyword'   => new sfValidatorPass(array('required' => false)),
      'is_enable' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('black_keyword_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'BlackKeyword';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'keyword'   => 'Text',
      'is_enable' => 'Boolean',
    );
  }
}
