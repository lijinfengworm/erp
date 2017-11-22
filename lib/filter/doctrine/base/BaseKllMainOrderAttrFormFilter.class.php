<?php

/**
 * KllMainOrderAttr filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllMainOrderAttrFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number'    => new sfWidgetFormFilterInput(),
      'province'        => new sfWidgetFormFilterInput(),
      'city'            => new sfWidgetFormFilterInput(),
      'area'            => new sfWidgetFormFilterInput(),
      'address'         => new sfWidgetFormFilterInput(),
      'real_name'       => new sfWidgetFormFilterInput(),
      'account'         => new sfWidgetFormFilterInput(),
      'receiver'        => new sfWidgetFormFilterInput(),
      'mobile'          => new sfWidgetFormFilterInput(),
      'logistic_number' => new sfWidgetFormFilterInput(),
      'postal_code'     => new sfWidgetFormFilterInput(),
      'card_type'       => new sfWidgetFormFilterInput(),
      'card_code'       => new sfWidgetFormFilterInput(),
      'creat_time'      => new sfWidgetFormFilterInput(),
      'update_time'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'order_number'    => new sfValidatorPass(array('required' => false)),
      'province'        => new sfValidatorPass(array('required' => false)),
      'city'            => new sfValidatorPass(array('required' => false)),
      'area'            => new sfValidatorPass(array('required' => false)),
      'address'         => new sfValidatorPass(array('required' => false)),
      'real_name'       => new sfValidatorPass(array('required' => false)),
      'account'         => new sfValidatorPass(array('required' => false)),
      'receiver'        => new sfValidatorPass(array('required' => false)),
      'mobile'          => new sfValidatorPass(array('required' => false)),
      'logistic_number' => new sfValidatorPass(array('required' => false)),
      'postal_code'     => new sfValidatorPass(array('required' => false)),
      'card_type'       => new sfValidatorPass(array('required' => false)),
      'card_code'       => new sfValidatorPass(array('required' => false)),
      'creat_time'      => new sfValidatorPass(array('required' => false)),
      'update_time'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_main_order_attr_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllMainOrderAttr';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Text',
      'order_number'    => 'Text',
      'province'        => 'Text',
      'city'            => 'Text',
      'area'            => 'Text',
      'address'         => 'Text',
      'real_name'       => 'Text',
      'account'         => 'Text',
      'receiver'        => 'Text',
      'mobile'          => 'Text',
      'logistic_number' => 'Text',
      'postal_code'     => 'Text',
      'card_type'       => 'Text',
      'card_code'       => 'Text',
      'creat_time'      => 'Text',
      'update_time'     => 'Text',
    );
  }
}
