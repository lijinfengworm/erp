<?php

/**
 * KllBBOrderFile form base class.
 *
 * @method KllBBOrderFile getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllBBOrderFileForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'source'      => new sfWidgetFormInputText(),
      'uid'         => new sfWidgetFormInputText(),
      'file'        => new sfWidgetFormInputText(),
      'number'      => new sfWidgetFormInputText(),
      'status'      => new sfWidgetFormInputText(),
      'surplus'     => new sfWidgetFormInputText(),
      'batch'       => new sfWidgetFormInputText(),
      'creat_time'  => new sfWidgetFormInputText(),
      'update_time' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'source'      => new sfValidatorPass(array('required' => false)),
      'uid'         => new sfValidatorInteger(array('required' => false)),
      'file'        => new sfValidatorPass(array('required' => false)),
      'number'      => new sfValidatorInteger(array('required' => false)),
      'status'      => new sfValidatorPass(array('required' => false)),
      'surplus'     => new sfValidatorPass(array('required' => false)),
      'batch'       => new sfValidatorPass(array('required' => false)),
      'creat_time'  => new sfValidatorInteger(array('required' => false)),
      'update_time' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_bb_order_file[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllBBOrderFile';
  }

}
