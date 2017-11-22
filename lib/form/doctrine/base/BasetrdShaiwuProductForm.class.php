<?php

/**
 * trdShaiwuProduct form base class.
 *
 * @method trdShaiwuProduct getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasetrdShaiwuProductForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'title'         => new sfWidgetFormInputText(),
      'intro'         => new sfWidgetFormTextarea(),
      'author_id'     => new sfWidgetFormInputText(),
      'author_name'   => new sfWidgetFormInputText(),
      'is_star'       => new sfWidgetFormInputText(),
      'type'          => new sfWidgetFormInputText(),
      'root_id'       => new sfWidgetFormInputText(),
      'children_id'   => new sfWidgetFormInputText(),
      'is_hot'        => new sfWidgetFormInputText(),
      'brand_id'      => new sfWidgetFormInputText(),
      'model'         => new sfWidgetFormInputText(),
      'gold'          => new sfWidgetFormInputText(),
      'tag_ids'       => new sfWidgetFormInputText(),
      'support'       => new sfWidgetFormInputText(),
      'agaist'        => new sfWidgetFormInputText(),
      'comment_count' => new sfWidgetFormInputText(),
      'front_pic'     => new sfWidgetFormInputText(),
      'status'        => new sfWidgetFormInputText(),
      'status_reason' => new sfWidgetFormInputText(),
      'hits'          => new sfWidgetFormInputText(),
      'activity_id'   => new sfWidgetFormInputText(),
      'publish_time'  => new sfWidgetFormInputText(),
      'source'        => new sfWidgetFormInputText(),
      'rank'          => new sfWidgetFormInputText(),
      'img_attr'      => new sfWidgetFormTextarea(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'         => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'intro'         => new sfValidatorString(array('max_length' => 350, 'required' => false)),
      'author_id'     => new sfValidatorInteger(array('required' => false)),
      'author_name'   => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'is_star'       => new sfValidatorInteger(array('required' => false)),
      'type'          => new sfValidatorInteger(array('required' => false)),
      'root_id'       => new sfValidatorInteger(array('required' => false)),
      'children_id'   => new sfValidatorInteger(array('required' => false)),
      'is_hot'        => new sfValidatorInteger(array('required' => false)),
      'brand_id'      => new sfValidatorInteger(array('required' => false)),
      'model'         => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'gold'          => new sfValidatorInteger(array('required' => false)),
      'tag_ids'       => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'support'       => new sfValidatorInteger(array('required' => false)),
      'agaist'        => new sfValidatorInteger(array('required' => false)),
      'comment_count' => new sfValidatorInteger(array('required' => false)),
      'front_pic'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'status'        => new sfValidatorInteger(array('required' => false)),
      'status_reason' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'hits'          => new sfValidatorInteger(array('required' => false)),
      'activity_id'   => new sfValidatorInteger(array('required' => false)),
      'publish_time'  => new sfValidatorPass(array('required' => false)),
      'source'        => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'rank'          => new sfValidatorPass(array('required' => false)),
      'img_attr'      => new sfValidatorString(array('max_length' => 1024, 'required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_shaiwu_product[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'trdShaiwuProduct';
  }

}
