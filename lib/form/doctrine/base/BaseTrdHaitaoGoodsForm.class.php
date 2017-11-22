<?php

/**
 * TrdHaitaoGoods form base class.
 *
 * @method TrdHaitaoGoods getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdHaitaoGoodsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'product_id' => new sfWidgetFormInputText(),
      'title'      => new sfWidgetFormInputText(),
      'goods_id'   => new sfWidgetFormInputText(),
      'attr'       => new sfWidgetFormTextarea(),
      'code'       => new sfWidgetFormInputText(),
      'total_num'  => new sfWidgetFormInputText(),
      'lock_num'   => new sfWidgetFormInputText(),
      'status'     => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'product_id' => new sfValidatorInteger(array('required' => false)),
      'title'      => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'goods_id'   => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'attr'       => new sfValidatorString(array('max_length' => 20000, 'required' => false)),
      'code'       => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'total_num'  => new sfValidatorInteger(array('required' => false)),
      'lock_num'   => new sfValidatorInteger(array('required' => false)),
      'status'     => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'TrdHaitaoGoods', 'column' => array('goods_id'))),
        new sfValidatorDoctrineUnique(array('model' => 'TrdHaitaoGoods', 'column' => array('code'))),
      ))
    );

    $this->widgetSchema->setNameFormat('trd_haitao_goods[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdHaitaoGoods';
  }

}
