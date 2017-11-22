<?php

/**
 * TrdLotteryPrize form base class.
 *
 * @method TrdLotteryPrize getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdLotteryPrizeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'lottery_id'   => new sfWidgetFormInputText(),
      'prize_name'   => new sfWidgetFormInputText(),
      'prize_rand'   => new sfWidgetFormInputText(),
      'is_virtual'   => new sfWidgetFormInputText(),
      'virtual_type' => new sfWidgetFormInputText(),
      'prize_num'    => new sfWidgetFormInputText(),
      'prize_info'   => new sfWidgetFormInputText(),
      'listorder'    => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'lottery_id'   => new sfValidatorInteger(array('required' => false)),
      'prize_name'   => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'prize_rand'   => new sfValidatorInteger(array('required' => false)),
      'is_virtual'   => new sfValidatorInteger(array('required' => false)),
      'virtual_type' => new sfValidatorInteger(array('required' => false)),
      'prize_num'    => new sfValidatorInteger(array('required' => false)),
      'prize_info'   => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'listorder'    => new sfValidatorInteger(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_lottery_prize[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdLotteryPrize';
  }

}
