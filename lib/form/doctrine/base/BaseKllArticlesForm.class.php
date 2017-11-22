<?php

/**
 * KllArticles form base class.
 *
 * @method KllArticles getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllArticlesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'cid'         => new sfWidgetFormInputText(),
      'seo_id'      => new sfWidgetFormInputText(),
      'h_id'        => new sfWidgetFormInputText(),
      'author'      => new sfWidgetFormInputText(),
      'public_time' => new sfWidgetFormInputText(),
      'relate_gid'  => new sfWidgetFormInputText(),
      'label'       => new sfWidgetFormInputText(),
      'title'       => new sfWidgetFormInputText(),
      'type'        => new sfWidgetFormInputText(),
      'abstract'    => new sfWidgetFormInputText(),
      'content'     => new sfWidgetFormInputText(),
      'add_time'    => new sfWidgetFormInputText(),
      'update_time' => new sfWidgetFormInputText(),
      'is_use'      => new sfWidgetFormInputText(),
      'audit_uid'   => new sfWidgetFormInputText(),
      'audit_time'  => new sfWidgetFormInputText(),
      'is_original' => new sfWidgetFormInputText(),
      'is_video'    => new sfWidgetFormInputText(),
      'order'       => new sfWidgetFormInputText(),
      'talent_id'   => new sfWidgetFormInputText(),
      'index_order' => new sfWidgetFormInputText(),
      'source'      => new sfWidgetFormInputText(),
      'platform'    => new sfWidgetFormInputText(),
      'cover'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'cid'         => new sfValidatorInteger(array('required' => false)),
      'seo_id'      => new sfValidatorInteger(array('required' => false)),
      'h_id'        => new sfValidatorInteger(array('required' => false)),
      'author'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'public_time' => new sfValidatorInteger(array('required' => false)),
      'relate_gid'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'label'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'title'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'type'        => new sfValidatorPass(array('required' => false)),
      'abstract'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'content'     => new sfValidatorPass(array('required' => false)),
      'add_time'    => new sfValidatorInteger(array('required' => false)),
      'update_time' => new sfValidatorInteger(array('required' => false)),
      'is_use'      => new sfValidatorPass(array('required' => false)),
      'audit_uid'   => new sfValidatorInteger(array('required' => false)),
      'audit_time'  => new sfValidatorInteger(array('required' => false)),
      'is_original' => new sfValidatorPass(array('required' => false)),
      'is_video'    => new sfValidatorPass(array('required' => false)),
      'order'       => new sfValidatorPass(array('required' => false)),
      'talent_id'   => new sfValidatorPass(array('required' => false)),
      'index_order' => new sfValidatorPass(array('required' => false)),
      'source'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'platform'    => new sfValidatorPass(array('required' => false)),
      'cover'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_articles[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllArticles';
  }

}
