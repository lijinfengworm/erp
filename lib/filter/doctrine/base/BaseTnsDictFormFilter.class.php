<?php

/**
 * TnsDict filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTnsDictFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'trans_from' => new sfWidgetFormFilterInput(),
      'trans_to'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'trans_from' => new sfValidatorPass(array('required' => false)),
      'trans_to'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('tns_dict_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TnsDict';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'trans_from' => 'Text',
      'trans_to'   => 'Text',
    );
  }
}
