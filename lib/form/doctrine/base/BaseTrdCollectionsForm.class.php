<?php

/**
 * TrdCollections form base class.
 *
 * @method TrdCollections getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdCollectionsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'name'           => new sfWidgetFormInputText(),
      'name_color'     => new sfWidgetFormInputText(),
      'memo'           => new sfWidgetFormTextarea(),
      'memo_color'     => new sfWidgetFormInputText(),
      'logo'           => new sfWidgetFormTextarea(),
      'logo_url'       => new sfWidgetFormInputText(),
      'pad_logo'       => new sfWidgetFormInputText(),
      'pad_logo_url'   => new sfWidgetFormInputText(),
      'shortcut'       => new sfWidgetFormInputText(),
      'is_hide'        => new sfWidgetFormInputCheckbox(),
      'other_contents' => new sfWidgetFormTextarea(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'           => new sfValidatorString(array('max_length' => 100)),
      'name_color'     => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'memo'           => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'memo_color'     => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'logo'           => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'logo_url'       => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'pad_logo'       => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'pad_logo_url'   => new sfValidatorString(array('max_length' => 250, 'required' => false)),
      'shortcut'       => new sfValidatorString(array('max_length' => 30)),
      'is_hide'        => new sfValidatorBoolean(array('required' => false)),
      'other_contents' => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'TrdCollections', 'column' => array('name'))),
        new sfValidatorDoctrineUnique(array('model' => 'TrdCollections', 'column' => array('shortcut'))),
      ))
    );

    $this->widgetSchema->setNameFormat('trd_collections[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdCollections';
  }

}
