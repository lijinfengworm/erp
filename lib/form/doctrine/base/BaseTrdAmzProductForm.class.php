<?php

/**
 * TrdAmzProduct form base class.
 *
 * @method TrdAmzProduct getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdAmzProductForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'shid'       => new sfWidgetFormInputText(),
      'amzid'      => new sfWidgetFormInputText(),
      'imgurl'     => new sfWidgetFormInputText(),
      'enname'     => new sfWidgetFormInputText(),
      'cnname'     => new sfWidgetFormInputText(),
      'category'   => new sfWidgetFormInputText(),
      'brand'      => new sfWidgetFormInputText(),
      'price'      => new sfWidgetFormInputText(),
      'comment'    => new sfWidgetFormInputText(),
      'hdtype'     => new sfWidgetFormInputText(),
      'oldhdtype'  => new sfWidgetFormInputText(),
      'shtype'     => new sfWidgetFormInputText(),
      'page'       => new sfWidgetFormInputText(),
      'status'     => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'shid'       => new sfValidatorInteger(array('required' => false)),
      'amzid'      => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'imgurl'     => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'enname'     => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'cnname'     => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'category'   => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'brand'      => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'price'      => new sfValidatorNumber(array('required' => false)),
      'comment'    => new sfValidatorInteger(array('required' => false)),
      'hdtype'     => new sfValidatorInteger(array('required' => false)),
      'oldhdtype'  => new sfValidatorInteger(array('required' => false)),
      'shtype'     => new sfValidatorInteger(array('required' => false)),
      'page'       => new sfValidatorInteger(array('required' => false)),
      'status'     => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_amz_product[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAmzProduct';
  }

}
