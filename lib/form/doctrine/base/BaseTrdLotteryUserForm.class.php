<?php

/**
 * TrdLotteryUser form base class.
 *
 * @method TrdLotteryUser getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdLotteryUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'lottery_id'  => new sfWidgetFormInputText(),
      'phone'       => new sfWidgetFormInputText(),
      'verify'      => new sfWidgetFormInputText(),
      'lottery_num' => new sfWidgetFormInputText(),
      'attr_num'    => new sfWidgetFormInputText(),
      'source'      => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'lottery_id'  => new sfValidatorInteger(array('required' => false)),
      'phone'       => new sfValidatorString(array('max_length' => 15, 'required' => false)),
      'verify'      => new sfValidatorPass(array('required' => false)),
      'lottery_num' => new sfValidatorInteger(array('required' => false)),
      'attr_num'    => new sfValidatorInteger(array('required' => false)),
      'source'      => new sfValidatorString(array('max_length' => 12, 'required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_lottery_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdLotteryUser';
  }

}
