<?php

/**
 * KllPurchaserAuth filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllPurchaserAuthFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'uid'         => new sfWidgetFormFilterInput(),
      'purchaser'   => new sfWidgetFormFilterInput(),
      'card_number' => new sfWidgetFormFilterInput(),
      'current_use' => new sfWidgetFormFilterInput(),
      'create_time' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'uid'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'purchaser'   => new sfValidatorPass(array('required' => false)),
      'card_number' => new sfValidatorPass(array('required' => false)),
      'current_use' => new sfValidatorPass(array('required' => false)),
      'create_time' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_purchaser_auth_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllPurchaserAuth';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'uid'         => 'Number',
      'purchaser'   => 'Text',
      'card_number' => 'Text',
      'current_use' => 'Text',
      'create_time' => 'Text',
    );
  }
}
