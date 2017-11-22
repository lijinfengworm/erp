<?php

/**
 * TrdHomepageCheap form base class.
 *
 * @method TrdHomepageCheap getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdHomepageCheapForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'category_all_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'add_empty' => true)),
      'title'           => new sfWidgetFormInputText(),
      'logo'            => new sfWidgetFormTextarea(),
      'link'            => new sfWidgetFormTextarea(),
      'old_price'       => new sfWidgetFormInputText(),
      'new_price'       => new sfWidgetFormInputText(),
      'is_default'      => new sfWidgetFormInputCheckbox(),
      'status'          => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'category_all_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'required' => false)),
      'title'           => new sfValidatorString(array('max_length' => 50)),
      'logo'            => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'link'            => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'old_price'       => new sfValidatorNumber(array('required' => false)),
      'new_price'       => new sfValidatorNumber(array('required' => false)),
      'is_default'      => new sfValidatorBoolean(array('required' => false)),
      'status'          => new sfValidatorInteger(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_homepage_cheap[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdHomepageCheap';
  }

}
