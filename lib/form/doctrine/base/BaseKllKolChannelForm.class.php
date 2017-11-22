<?php

/**
 * KllKolChannel form base class.
 *
 * @method KllKolChannel getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllKolChannelForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'title'        => new sfWidgetFormInputText(),
      'abstract'     => new sfWidgetFormInputText(),
      'channel_code' => new sfWidgetFormInputText(),
      'times'        => new sfWidgetFormInputText(),
      'range'        => new sfWidgetFormInputText(),
      'discount'     => new sfWidgetFormInputText(),
      'toplimit'     => new sfWidgetFormInputText(),
      'start_time'   => new sfWidgetFormInputText(),
      'end_time'     => new sfWidgetFormInputText(),
      'commision'    => new sfWidgetFormInputText(),
      'status'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'abstract'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'channel_code' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'times'        => new sfValidatorInteger(array('required' => false)),
      'range'        => new sfValidatorPass(array('required' => false)),
      'discount'     => new sfValidatorPass(array('required' => false)),
      'toplimit'     => new sfValidatorNumber(array('required' => false)),
      'start_time'   => new sfValidatorInteger(array('required' => false)),
      'end_time'     => new sfValidatorInteger(array('required' => false)),
      'commision'    => new sfValidatorPass(array('required' => false)),
      'status'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_kol_channel[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllKolChannel';
  }

}
