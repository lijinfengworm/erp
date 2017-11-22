<?php

/**
 * TrdGoClick form base class.
 *
 * @method TrdGoClick getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdGoClickForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'refer_id'  => new sfWidgetFormInputText(),
      'go_id'     => new sfWidgetFormInputText(),
      'uid'       => new sfWidgetFormInputText(),
      'vid'       => new sfWidgetFormInputText(),
      'vst'       => new sfWidgetFormInputText(),
      'clicktime' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'refer_id'  => new sfValidatorInteger(),
      'go_id'     => new sfValidatorInteger(),
      'uid'       => new sfValidatorInteger(array('required' => false)),
      'vid'       => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'vst'       => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'clicktime' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_go_click[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoClick';
  }

}
