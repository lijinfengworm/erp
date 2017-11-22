<?php

/**
 * TrdMarketingActivity form base class.
 *
 * @method TrdMarketingActivity getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdMarketingActivityForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'title'           => new sfWidgetFormInputText(),
      'type'            => new sfWidgetFormInputText(),
      'mode'            => new sfWidgetFormInputText(),
      'order_note'      => new sfWidgetFormInputText(),
      'scope'           => new sfWidgetFormInputText(),
      'attr1'           => new sfWidgetFormTextarea(),
      'attr2'           => new sfWidgetFormTextarea(),
      'status'          => new sfWidgetFormInputText(),
      'use_type'        => new sfWidgetFormInputText(),
      'use_note'        => new sfWidgetFormInputText(),
      'stime'           => new sfWidgetFormInputText(),
      'etime'           => new sfWidgetFormInputText(),
      'short_name'      => new sfWidgetFormInputText(),
      'intro'           => new sfWidgetFormInputText(),
      'group_id'        => new sfWidgetFormInputText(),
      'new_version'     => new sfWidgetFormInputText(),
      'current_version' => new sfWidgetFormInputText(),
      'ing_version'     => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'           => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'type'            => new sfValidatorInteger(array('required' => false)),
      'mode'            => new sfValidatorInteger(array('required' => false)),
      'order_note'      => new sfValidatorInteger(array('required' => false)),
      'scope'           => new sfValidatorInteger(array('required' => false)),
      'attr1'           => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'attr2'           => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'status'          => new sfValidatorInteger(array('required' => false)),
      'use_type'        => new sfValidatorInteger(array('required' => false)),
      'use_note'        => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'stime'           => new sfValidatorInteger(array('required' => false)),
      'etime'           => new sfValidatorInteger(array('required' => false)),
      'short_name'      => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'intro'           => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'group_id'        => new sfValidatorInteger(array('required' => false)),
      'new_version'     => new sfValidatorInteger(array('required' => false)),
      'current_version' => new sfValidatorInteger(array('required' => false)),
      'ing_version'     => new sfValidatorInteger(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_marketing_activity[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdMarketingActivity';
  }

}
