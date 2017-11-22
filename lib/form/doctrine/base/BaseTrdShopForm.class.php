<?php

/**
 * TrdShop form base class.
 *
 * @method TrdShop getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdShopForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'external_id'    => new sfWidgetFormInputText(),
      'name'           => new sfWidgetFormInputText(),
      'owner_name'     => new sfWidgetFormInputText(),
      'link'           => new sfWidgetFormTextarea(),
      'item_count'     => new sfWidgetFormInputText(),
      'src'            => new sfWidgetFormInputText(),
      'status'         => new sfWidgetFormInputText(),
      'ban_start_time' => new sfWidgetFormInputText(),
      'ban_end_time'   => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'external_id'    => new sfValidatorInteger(),
      'name'           => new sfValidatorString(array('max_length' => 255)),
      'owner_name'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'link'           => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'item_count'     => new sfValidatorInteger(array('required' => false)),
      'src'            => new sfValidatorPass(array('required' => false)),
      'status'         => new sfValidatorInteger(array('required' => false)),
      'ban_start_time' => new sfValidatorPass(array('required' => false)),
      'ban_end_time'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'TrdShop', 'column' => array('external_id'))),
        new sfValidatorDoctrineUnique(array('model' => 'TrdShop', 'column' => array('name'))),
      ))
    );

    $this->widgetSchema->setNameFormat('trd_shop[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdShop';
  }

}
