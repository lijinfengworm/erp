<?php

/**
 * trdFind form base class.
 *
 * @method trdFind getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasetrdFindForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'title'        => new sfWidgetFormInputText(),
      'text'         => new sfWidgetFormTextarea(),
      'price'        => new sfWidgetFormInputText(),
      'root_id'      => new sfWidgetFormInputText(),
      'children_id'  => new sfWidgetFormInputText(),
      'orginal_url'  => new sfWidgetFormInputText(),
      'orginal_type' => new sfWidgetFormInputText(),
      'store_id'     => new sfWidgetFormInputText(),
      'imgs_attr'    => new sfWidgetFormTextarea(),
      'tags_attr'    => new sfWidgetFormInputText(),
      'reply_count'  => new sfWidgetFormInputText(),
      'like_count'   => new sfWidgetFormInputText(),
      'status'       => new sfWidgetFormInputText(),
      'hits'         => new sfWidgetFormInputText(),
      'author_id'    => new sfWidgetFormInputText(),
      'audit_id'     => new sfWidgetFormInputText(),
      'rank'         => new sfWidgetFormInputText(),
      'publish_date' => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'        => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'text'         => new sfValidatorString(array('max_length' => 400, 'required' => false)),
      'price'        => new sfValidatorNumber(array('required' => false)),
      'root_id'      => new sfValidatorInteger(array('required' => false)),
      'children_id'  => new sfValidatorInteger(array('required' => false)),
      'orginal_url'  => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'orginal_type' => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'store_id'     => new sfValidatorInteger(array('required' => false)),
      'imgs_attr'    => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'tags_attr'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'reply_count'  => new sfValidatorInteger(array('required' => false)),
      'like_count'   => new sfValidatorInteger(array('required' => false)),
      'status'       => new sfValidatorInteger(array('required' => false)),
      'hits'         => new sfValidatorInteger(array('required' => false)),
      'author_id'    => new sfValidatorInteger(array('required' => false)),
      'audit_id'     => new sfValidatorInteger(array('required' => false)),
      'rank'         => new sfValidatorPass(array('required' => false)),
      'publish_date' => new sfValidatorPass(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_find[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'trdFind';
  }

}
