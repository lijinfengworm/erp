<?php

/**
 * KaluliItemAttr form base class.
 *
 * @method KaluliItemAttr getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKaluliItemAttrForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'item_id'            => new sfWidgetFormInputText(),
      'content'            => new sfWidgetFormInputText(),
      'pic_detail'         => new sfWidgetFormInputText(),
      'comment_imgs_count' => new sfWidgetFormInputText(),
      'comment_count'      => new sfWidgetFormInputText(),
      'comment_tags_count' => new sfWidgetFormTextarea(),
      'attrs'              => new sfWidgetFormTextarea(),
      'review'             => new sfWidgetFormInputText(),
      'sales_count'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'item_id'            => new sfValidatorInteger(array('required' => false)),
      'content'            => new sfValidatorPass(array('required' => false)),
      'pic_detail'         => new sfValidatorPass(array('required' => false)),
      'comment_imgs_count' => new sfValidatorInteger(array('required' => false)),
      'comment_count'      => new sfValidatorInteger(array('required' => false)),
      'comment_tags_count' => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'attrs'              => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'review'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'sales_count'        => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kaluli_item_attr[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliItemAttr';
  }

}
