<?php

/**
 * adTextChain filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseadTextChainFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ad_text' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ad_url'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'ad_text' => new sfValidatorPass(array('required' => false)),
      'ad_url'  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ad_text_chain_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'adTextChain';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'ad_text' => 'Text',
      'ad_url'  => 'Text',
    );
  }
}
