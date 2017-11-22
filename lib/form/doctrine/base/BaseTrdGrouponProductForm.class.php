<?php

/**
 * TrdGrouponProduct form base class.
 *
 * @method TrdGrouponProduct getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdGrouponProductForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'brand_id'     => new sfWidgetFormInputText(),
      'title'        => new sfWidgetFormInputText(),
      'attend_count' => new sfWidgetFormInputText(),
      'discount'     => new sfWidgetFormInputText(),
      'category_id'  => new sfWidgetFormInputText(),
      'start_time'   => new sfWidgetFormInputText(),
      'end_time'     => new sfWidgetFormInputText(),
      'attr_collect' => new sfWidgetFormTextarea(),
      'rank'         => new sfWidgetFormInputText(),
      'price'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'brand_id'     => new sfValidatorInteger(array('required' => false)),
      'title'        => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'attend_count' => new sfValidatorInteger(array('required' => false)),
      'discount'     => new sfValidatorNumber(array('required' => false)),
      'category_id'  => new sfValidatorInteger(array('required' => false)),
      'start_time'   => new sfValidatorPass(array('required' => false)),
      'end_time'     => new sfValidatorPass(array('required' => false)),
      'attr_collect' => new sfValidatorString(array('max_length' => 1500, 'required' => false)),
      'rank'         => new sfValidatorInteger(array('required' => false)),
      'price'        => new sfValidatorNumber(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_groupon_product[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGrouponProduct';
  }

}
