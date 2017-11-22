<?php

/**
 * TrdWeibo form base class.
 *
 * @method TrdWeibo getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdWeiboForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'content'      => new sfWidgetFormTextarea(),
      'link_url'     => new sfWidgetFormInputText(),
      'img_path'     => new sfWidgetFormInputText(),
      'advance_date' => new sfWidgetFormInputText(),
      'publish_date' => new sfWidgetFormInputText(),
      'is_delete'    => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'content'      => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'link_url'     => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'img_path'     => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'advance_date' => new sfValidatorPass(array('required' => false)),
      'publish_date' => new sfValidatorPass(array('required' => false)),
      'is_delete'    => new sfValidatorInteger(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_weibo[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdWeibo';
  }

}
