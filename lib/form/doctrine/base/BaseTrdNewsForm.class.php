<?php

/**
 * TrdNews form base class.
 *
 * @method TrdNews getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdNewsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'intro'                => new sfWidgetFormTextarea(),
      'title'                => new sfWidgetFormInputText(),
      'text'                 => new sfWidgetFormTextarea(),
      'orginal_url'          => new sfWidgetFormInputText(),
      'orginal_type'         => new sfWidgetFormInputText(),
      'product_id'           => new sfWidgetFormInputText(),
      'product_start_date'   => new sfWidgetFormInputText(),
      'product_end_date'     => new sfWidgetFormInputText(),
      'publish_date'         => new sfWidgetFormDateTime(),
      'price'                => new sfWidgetFormInputText(),
      'is_delete'            => new sfWidgetFormInputCheckbox(),
      'hits'                 => new sfWidgetFormInputText(),
      'reply_count'          => new sfWidgetFormInputText(),
      'light_count'          => new sfWidgetFormInputText(),
      'last_reply_date'      => new sfWidgetFormDateTime(),
      'img_attr'             => new sfWidgetFormTextarea(),
      'img_link'             => new sfWidgetFormInputText(),
      'img_path'             => new sfWidgetFormInputText(),
      'img_tail'             => new sfWidgetFormInputText(),
      'author_id'            => new sfWidgetFormInputText(),
      'editor_id'            => new sfWidgetFormInputText(),
      'show_intro'           => new sfWidgetFormInputCheckbox(),
      'subtitle'             => new sfWidgetFormInputText(),
      'spreadtitle'          => new sfWidgetFormInputText(),
      'direct_words'         => new sfWidgetFormInputText(),
      'goods_state'          => new sfWidgetFormInputText(),
      'support'              => new sfWidgetFormInputText(),
      'against'              => new sfWidgetFormInputText(),
      'praise'               => new sfWidgetFormInputText(),
      'shoe_id'              => new sfWidgetFormInputText(),
      'item_all_id'          => new sfWidgetFormInputText(),
      'baoliao_id'           => new sfWidgetFormInputText(),
      'root_type'            => new sfWidgetFormInputText(),
      'root_id'              => new sfWidgetFormInputText(),
      'children_id'          => new sfWidgetFormInputText(),
      'type'                 => new sfWidgetFormInputText(),
      'height'               => new sfWidgetFormInputText(),
      'width'                => new sfWidgetFormInputText(),
      'store_id'             => new sfWidgetFormInputText(),
      'brand_id'             => new sfWidgetFormInputText(),
      'audit_user'           => new sfWidgetFormInputText(),
      'audit_status'         => new sfWidgetFormInputText(),
      'audit_message'        => new sfWidgetFormInputText(),
      'audit_date'           => new sfWidgetFormDateTime(),
      'timing_interval'      => new sfWidgetFormInputText(),
      'is_display_index'     => new sfWidgetFormInputCheckbox(),
      'is_shopping'          => new sfWidgetFormInputCheckbox(),
      'attr'                 => new sfWidgetFormTextarea(),
      'is_show_comment'      => new sfWidgetFormInputText(),
      'is_show_buy_link'     => new sfWidgetFormInputText(),
      'rank'                 => new sfWidgetFormInputText(),
      'revel_start_date'     => new sfWidgetFormDateTime(),
      'revel_end_date'       => new sfWidgetFormDateTime(),
      'commodity'            => new sfWidgetFormTextarea(),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
      'trd_product_tag_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'TrdProductTag')),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'intro'                => new sfValidatorString(array('max_length' => 1000)),
      'title'                => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'text'                 => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'orginal_url'          => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'orginal_type'         => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'product_id'           => new sfValidatorInteger(array('required' => false)),
      'product_start_date'   => new sfValidatorInteger(array('required' => false)),
      'product_end_date'     => new sfValidatorInteger(array('required' => false)),
      'publish_date'         => new sfValidatorDateTime(),
      'price'                => new sfValidatorPass(array('required' => false)),
      'is_delete'            => new sfValidatorBoolean(array('required' => false)),
      'hits'                 => new sfValidatorInteger(array('required' => false)),
      'reply_count'          => new sfValidatorInteger(array('required' => false)),
      'light_count'          => new sfValidatorInteger(array('required' => false)),
      'last_reply_date'      => new sfValidatorDateTime(array('required' => false)),
      'img_attr'             => new sfValidatorString(array('max_length' => 1024, 'required' => false)),
      'img_link'             => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'img_path'             => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'img_tail'             => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'author_id'            => new sfValidatorInteger(array('required' => false)),
      'editor_id'            => new sfValidatorInteger(array('required' => false)),
      'show_intro'           => new sfValidatorBoolean(array('required' => false)),
      'subtitle'             => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'spreadtitle'          => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'direct_words'         => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'goods_state'          => new sfValidatorInteger(array('required' => false)),
      'support'              => new sfValidatorInteger(array('required' => false)),
      'against'              => new sfValidatorInteger(array('required' => false)),
      'praise'               => new sfValidatorInteger(array('required' => false)),
      'shoe_id'              => new sfValidatorPass(array('required' => false)),
      'item_all_id'          => new sfValidatorPass(array('required' => false)),
      'baoliao_id'           => new sfValidatorPass(array('required' => false)),
      'root_type'            => new sfValidatorInteger(array('required' => false)),
      'root_id'              => new sfValidatorInteger(array('required' => false)),
      'children_id'          => new sfValidatorInteger(array('required' => false)),
      'type'                 => new sfValidatorInteger(array('required' => false)),
      'height'               => new sfValidatorPass(array('required' => false)),
      'width'                => new sfValidatorPass(array('required' => false)),
      'store_id'             => new sfValidatorInteger(array('required' => false)),
      'brand_id'             => new sfValidatorInteger(array('required' => false)),
      'audit_user'           => new sfValidatorInteger(array('required' => false)),
      'audit_status'         => new sfValidatorInteger(array('required' => false)),
      'audit_message'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'audit_date'           => new sfValidatorDateTime(array('required' => false)),
      'timing_interval'      => new sfValidatorInteger(array('required' => false)),
      'is_display_index'     => new sfValidatorBoolean(array('required' => false)),
      'is_shopping'          => new sfValidatorBoolean(array('required' => false)),
      'attr'                 => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'is_show_comment'      => new sfValidatorInteger(array('required' => false)),
      'is_show_buy_link'     => new sfValidatorInteger(array('required' => false)),
      'rank'                 => new sfValidatorPass(array('required' => false)),
      'revel_start_date'     => new sfValidatorDateTime(array('required' => false)),
      'revel_end_date'       => new sfValidatorDateTime(array('required' => false)),
      'commodity'            => new sfValidatorString(array('max_length' => 511, 'required' => false)),
      'created_at'           => new sfValidatorDateTime(),
      'updated_at'           => new sfValidatorDateTime(),
      'trd_product_tag_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'TrdProductTag', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_news[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdNews';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['trd_product_tag_list']))
    {
      $this->setDefault('trd_product_tag_list', $this->object->trd_product_tag->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->savetrd_product_tagList($con);

    parent::doSave($con);
  }

  public function savetrd_product_tagList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['trd_product_tag_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->trd_product_tag->getPrimaryKeys();
    $values = $this->getValue('trd_product_tag_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('trd_product_tag', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('trd_product_tag', array_values($link));
    }
  }

}
