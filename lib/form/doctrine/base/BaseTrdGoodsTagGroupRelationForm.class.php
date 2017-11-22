<?php

/**
 * TrdGoodsTagGroupRelation form base class.
 *
 * @method TrdGoodsTagGroupRelation getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsTagGroupRelationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'group_id'    => new sfWidgetFormInputText(),
      'category_id' => new sfWidgetFormInputText(),
      'value'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'group_id'    => new sfValidatorInteger(array('required' => false)),
      'category_id' => new sfValidatorInteger(array('required' => false)),
      'value'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_tag_group_relation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsTagGroupRelation';
  }

}
