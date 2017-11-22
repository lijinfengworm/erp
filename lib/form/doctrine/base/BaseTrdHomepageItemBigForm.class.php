<?php

/**
 * TrdHomepageItemBig form base class.
 *
 * @method TrdHomepageItemBig getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdHomepageItemBigForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'category_all_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'add_empty' => true)),
      'title'           => new sfWidgetFormInputText(),
      'price'           => new sfWidgetFormInputText(),
      'logo'            => new sfWidgetFormTextarea(),
      'link'            => new sfWidgetFormTextarea(),
      'status'          => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'category_all_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'required' => false)),
      'title'           => new sfValidatorString(array('max_length' => 50)),
      'price'           => new sfValidatorNumber(array('required' => false)),
      'logo'            => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'link'            => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'status'          => new sfValidatorInteger(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'TrdHomepageItemBig', 'column' => array('title')))
    );

    $this->widgetSchema->setNameFormat('trd_homepage_item_big[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdHomepageItemBig';
  }

}
