<?php

/**
 * TrdCabbage form base class.
 *
 * @method TrdCabbage getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdCabbageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'title'      => new sfWidgetFormInputText(),
      'price'      => new sfWidgetFormInputText(),
      'intro'      => new sfWidgetFormTextarea(),
      'link_url'   => new sfWidgetFormInputText(),
      'img_path'   => new sfWidgetFormInputText(),
      'is_delete'  => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'      => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'price'      => new sfValidatorNumber(array('required' => false)),
      'intro'      => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'link_url'   => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'img_path'   => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'is_delete'  => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_cabbage[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdCabbage';
  }

}
