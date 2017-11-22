<?php

/**
 * TrdGoUrl form base class.
 *
 * @method TrdGoUrl getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdGoUrlForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'trd_news_id' => new sfWidgetFormInputText(),
      'url'         => new sfWidgetFormTextarea(),
      'encrypt_url' => new sfWidgetFormInputText(),
      'title'       => new sfWidgetFormInputText(),
      'type'        => new sfWidgetFormInputText(),
      'shop'        => new sfWidgetFormInputText(),
      'addtime'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'trd_news_id' => new sfValidatorInteger(array('required' => false)),
      'url'         => new sfValidatorString(array('max_length' => 1000)),
      'encrypt_url' => new sfValidatorString(array('max_length' => 8)),
      'title'       => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'type'        => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'shop'        => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'addtime'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_go_url[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoUrl';
  }

}
