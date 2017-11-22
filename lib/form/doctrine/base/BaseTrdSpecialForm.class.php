<?php

/**
 * TrdSpecial form base class.
 *
 * @method TrdSpecial getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdSpecialForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'type'            => new sfWidgetFormInputText(),
      'name'            => new sfWidgetFormInputText(),
      'm_title'         => new sfWidgetFormInputText(),
      'journal_title'   => new sfWidgetFormInputText(),
      'journal_desc'    => new sfWidgetFormTextarea(),
      'journal_img'     => new sfWidgetFormInputText(),
      'journal_type_id' => new sfWidgetFormInputText(),
      'show_journal'    => new sfWidgetFormInputText(),
      'cateid'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TrdSpecialCate'), 'add_empty' => true)),
      'remarks'         => new sfWidgetFormInputText(),
      'is_theme'        => new sfWidgetFormInputText(),
      'theme_id'        => new sfWidgetFormInputText(),
      'template'        => new sfWidgetFormInputText(),
      'info'            => new sfWidgetFormInputText(),
      'support'         => new sfWidgetFormInputText(),
      'agaist'          => new sfWidgetFormInputText(),
      'comment_count'   => new sfWidgetFormInputText(),
      'click_count'     => new sfWidgetFormInputText(),
      'special_status'  => new sfWidgetFormInputText(),
      'timing_interval' => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'deleted_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'type'            => new sfValidatorInteger(array('required' => false)),
      'name'            => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'm_title'         => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'journal_title'   => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'journal_desc'    => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'journal_img'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'journal_type_id' => new sfValidatorInteger(array('required' => false)),
      'show_journal'    => new sfValidatorInteger(array('required' => false)),
      'cateid'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TrdSpecialCate'), 'required' => false)),
      'remarks'         => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'is_theme'        => new sfValidatorInteger(array('required' => false)),
      'theme_id'        => new sfValidatorInteger(array('required' => false)),
      'template'        => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'info'            => new sfValidatorPass(array('required' => false)),
      'support'         => new sfValidatorInteger(array('required' => false)),
      'agaist'          => new sfValidatorInteger(array('required' => false)),
      'comment_count'   => new sfValidatorInteger(array('required' => false)),
      'click_count'     => new sfValidatorInteger(array('required' => false)),
      'special_status'  => new sfValidatorInteger(array('required' => false)),
      'timing_interval' => new sfValidatorInteger(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
      'deleted_at'      => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_special[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdSpecial';
  }

}
