<?php

/**
 * TrdProductTag form base class.
 *
 * @method TrdProductTag getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdProductTagForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'name'          => new sfWidgetFormInputText(),
      'hits'          => new sfWidgetFormInputText(),
      'hot'           => new sfWidgetFormInputText(),
      'show_order'    => new sfWidgetFormInputText(),
      'hidden'        => new sfWidgetFormInputCheckbox(),
      'trd_news_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'TrdNews')),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'          => new sfValidatorString(array('max_length' => 45)),
      'hits'          => new sfValidatorInteger(array('required' => false)),
      'hot'           => new sfValidatorInteger(array('required' => false)),
      'show_order'    => new sfValidatorInteger(array('required' => false)),
      'hidden'        => new sfValidatorBoolean(array('required' => false)),
      'trd_news_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'TrdNews', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_product_tag[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdProductTag';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['trd_news_list']))
    {
      $this->setDefault('trd_news_list', $this->object->TrdNews->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveTrdNewsList($con);

    parent::doSave($con);
  }

  public function saveTrdNewsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['trd_news_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->TrdNews->getPrimaryKeys();
    $values = $this->getValue('trd_news_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('TrdNews', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('TrdNews', array_values($link));
    }
  }

}
