<?php

/**
 * KllMainOrderAttr form base class.
 *
 * @method KllMainOrderAttr getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllMainOrderAttrForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'order_number'    => new sfWidgetFormInputText(),
      'province'        => new sfWidgetFormInputText(),
      'city'            => new sfWidgetFormInputText(),
      'area'            => new sfWidgetFormInputText(),
      'address'         => new sfWidgetFormInputText(),
      'real_name'       => new sfWidgetFormInputText(),
      'account'         => new sfWidgetFormInputText(),
      'receiver'        => new sfWidgetFormInputText(),
      'mobile'          => new sfWidgetFormInputText(),
      'logistic_number' => new sfWidgetFormInputText(),
      'postal_code'     => new sfWidgetFormInputText(),
      'card_type'       => new sfWidgetFormInputText(),
      'card_code'       => new sfWidgetFormInputText(),
      'creat_time'      => new sfWidgetFormInputText(),
      'update_time'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number'    => new sfValidatorPass(array('required' => false)),
      'province'        => new sfValidatorPass(array('required' => false)),
      'city'            => new sfValidatorPass(array('required' => false)),
      'area'            => new sfValidatorPass(array('required' => false)),
      'address'         => new sfValidatorPass(array('required' => false)),
      'real_name'       => new sfValidatorPass(array('required' => false)),
      'account'         => new sfValidatorPass(array('required' => false)),
      'receiver'        => new sfValidatorPass(array('required' => false)),
      'mobile'          => new sfValidatorPass(array('required' => false)),
      'logistic_number' => new sfValidatorPass(array('required' => false)),
      'postal_code'     => new sfValidatorPass(array('required' => false)),
      'card_type'       => new sfValidatorPass(array('required' => false)),
      'card_code'       => new sfValidatorPass(array('required' => false)),
      'creat_time'      => new sfValidatorPass(array('required' => false)),
      'update_time'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_main_order_attr[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllMainOrderAttr';
  }

}
