<?php

/**
 * trdShaiwuProductContent form base class.
 *
 * @method trdShaiwuProductContent getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasetrdShaiwuProductContentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'product_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('trdShaiwuProduct'), 'add_empty' => true)),
      'content'    => new sfWidgetFormInputText(),
      'urls'       => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'product_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('trdShaiwuProduct'), 'required' => false)),
      'content'    => new sfValidatorPass(array('required' => false)),
      'urls'       => new sfValidatorString(array('max_length' => 700, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_shaiwu_product_content[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'trdShaiwuProductContent';
  }

}
