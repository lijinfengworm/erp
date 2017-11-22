<?php

/**
 * KllKolOrder form base class.
 *
 * @method KllKolOrder getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllKolOrderForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'order_number'     => new sfWidgetFormInputText(),
      'sub_order_id'     => new sfWidgetFormInputText(),
      'order_time'       => new sfWidgetFormInputText(),
      'total_price'      => new sfWidgetFormInputText(),
      'main_total_price' => new sfWidgetFormInputText(),
      'is_new_custom'    => new sfWidgetFormInputText(),
      'channel'          => new sfWidgetFormInputText(),
      'item_id'          => new sfWidgetFormInputText(),
      'item_title'       => new sfWidgetFormInputText(),
      'commision'        => new sfWidgetFormInputText(),
      'commision_rate'   => new sfWidgetFormInputText(),
      'kol_id'           => new sfWidgetFormInputText(),
      'user_id'          => new sfWidgetFormInputText(),
      'status'           => new sfWidgetFormInputText(),
      'flag'             => new sfWidgetFormInputText(),
      'user_name'        => new sfWidgetFormInputText(),
      'channel_id'       => new sfWidgetFormInputText(),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number'     => new sfValidatorPass(array('required' => false)),
      'sub_order_id'     => new sfValidatorInteger(array('required' => false)),
      'order_time'       => new sfValidatorInteger(array('required' => false)),
      'total_price'      => new sfValidatorNumber(array('required' => false)),
      'main_total_price' => new sfValidatorNumber(array('required' => false)),
      'is_new_custom'    => new sfValidatorPass(array('required' => false)),
      'channel'          => new sfValidatorPass(array('required' => false)),
      'item_id'          => new sfValidatorInteger(array('required' => false)),
      'item_title'       => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'commision'        => new sfValidatorNumber(array('required' => false)),
      'commision_rate'   => new sfValidatorPass(array('required' => false)),
      'kol_id'           => new sfValidatorInteger(array('required' => false)),
      'user_id'          => new sfValidatorInteger(array('required' => false)),
      'status'           => new sfValidatorPass(array('required' => false)),
      'flag'             => new sfValidatorPass(array('required' => false)),
      'user_name'        => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'channel_id'       => new sfValidatorInteger(array('required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kll_kol_order[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllKolOrder';
  }

}
