<?php

/**
 * TrdSeoNews form base class.
 *
 * @method TrdSeoNews getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdSeoNewsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'title'           => new sfWidgetFormInputText(),
      'orginal_url'     => new sfWidgetFormInputText(),
      'orginal_type'    => new sfWidgetFormInputText(),
      'product_id'      => new sfWidgetFormInputText(),
      'publish_date'    => new sfWidgetFormDateTime(),
      'price'           => new sfWidgetFormInputText(),
      'is_delete'       => new sfWidgetFormInputCheckbox(),
      'hits'            => new sfWidgetFormInputText(),
      'reply_count'     => new sfWidgetFormInputText(),
      'light_count'     => new sfWidgetFormInputText(),
      'last_reply_date' => new sfWidgetFormDateTime(),
      'img_link'        => new sfWidgetFormInputText(),
      'img_path'        => new sfWidgetFormInputText(),
      'author_id'       => new sfWidgetFormInputText(),
      'editor_id'       => new sfWidgetFormInputText(),
      'subtitle'        => new sfWidgetFormInputText(),
      'spreadtitle'     => new sfWidgetFormInputText(),
      'support'         => new sfWidgetFormInputText(),
      'against'         => new sfWidgetFormInputText(),
      'praise'          => new sfWidgetFormInputText(),
      'root_type'       => new sfWidgetFormInputText(),
      'root_id'         => new sfWidgetFormInputText(),
      'children_id'     => new sfWidgetFormInputText(),
      'type'            => new sfWidgetFormInputText(),
      'store_id'        => new sfWidgetFormInputText(),
      'brand_id'        => new sfWidgetFormInputText(),
      'tags_attr'       => new sfWidgetFormTextarea(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'           => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'orginal_url'     => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'orginal_type'    => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'product_id'      => new sfValidatorInteger(array('required' => false)),
      'publish_date'    => new sfValidatorDateTime(),
      'price'           => new sfValidatorPass(array('required' => false)),
      'is_delete'       => new sfValidatorBoolean(array('required' => false)),
      'hits'            => new sfValidatorInteger(array('required' => false)),
      'reply_count'     => new sfValidatorInteger(array('required' => false)),
      'light_count'     => new sfValidatorInteger(array('required' => false)),
      'last_reply_date' => new sfValidatorDateTime(array('required' => false)),
      'img_link'        => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'img_path'        => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'author_id'       => new sfValidatorInteger(array('required' => false)),
      'editor_id'       => new sfValidatorInteger(array('required' => false)),
      'subtitle'        => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'spreadtitle'     => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'support'         => new sfValidatorInteger(array('required' => false)),
      'against'         => new sfValidatorInteger(array('required' => false)),
      'praise'          => new sfValidatorInteger(array('required' => false)),
      'root_type'       => new sfValidatorInteger(array('required' => false)),
      'root_id'         => new sfValidatorInteger(array('required' => false)),
      'children_id'     => new sfValidatorInteger(array('required' => false)),
      'type'            => new sfValidatorInteger(array('required' => false)),
      'store_id'        => new sfValidatorInteger(array('required' => false)),
      'brand_id'        => new sfValidatorInteger(array('required' => false)),
      'tags_attr'       => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_seo_news[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdSeoNews';
  }

}
