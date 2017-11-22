<?php

/**
 * TrdFindProduct form base class.
 *
 * @method TrdFindProduct getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdFindProductForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'title'         => new sfWidgetFormInputText(),
      'memo'          => new sfWidgetFormTextarea(),
      'price'         => new sfWidgetFormInputText(),
      'tag'           => new sfWidgetFormTextarea(),
      'root_id'       => new sfWidgetFormInputText(),
      'children_id'   => new sfWidgetFormInputText(),
      'root_name'     => new sfWidgetFormInputText(),
      'children_name' => new sfWidgetFormInputText(),
      'attr_collect'  => new sfWidgetFormTextarea(),
      'is_showsports' => new sfWidgetFormInputText(),
      'publish_date'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'         => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'memo'          => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'price'         => new sfValidatorNumber(array('required' => false)),
      'tag'           => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'root_id'       => new sfValidatorInteger(array('required' => false)),
      'children_id'   => new sfValidatorInteger(array('required' => false)),
      'root_name'     => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'children_name' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'attr_collect'  => new sfValidatorString(array('max_length' => 1500, 'required' => false)),
      'is_showsports' => new sfValidatorInteger(array('required' => false)),
      'publish_date'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_find_product[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdFindProduct';
  }

}
