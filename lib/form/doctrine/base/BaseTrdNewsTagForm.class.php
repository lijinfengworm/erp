<?php

/**
 * TrdNewsTag form base class.
 *
 * @method TrdNewsTag getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdNewsTagForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'trd_product_tag_id' => new sfWidgetFormInputHidden(),
      'trd_news_id'        => new sfWidgetFormInputHidden(),
      'is_default'         => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'trd_product_tag_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('trd_product_tag_id')), 'empty_value' => $this->getObject()->get('trd_product_tag_id'), 'required' => false)),
      'trd_news_id'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('trd_news_id')), 'empty_value' => $this->getObject()->get('trd_news_id'), 'required' => false)),
      'is_default'         => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_news_tag[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdNewsTag';
  }

}
