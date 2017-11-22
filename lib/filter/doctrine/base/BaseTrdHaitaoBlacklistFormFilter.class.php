<?php

/**
 * TrdHaitaoBlacklist filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdHaitaoBlacklistFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'   => new sfWidgetFormFilterInput(),
      'number' => new sfWidgetFormFilterInput(),
      'note'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'   => new sfValidatorPass(array('required' => false)),
      'number' => new sfValidatorPass(array('required' => false)),
      'note'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_haitao_blacklist_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdHaitaoBlacklist';
  }

  public function getFields()
  {
    return array(
      'id'     => 'Number',
      'name'   => 'Text',
      'number' => 'Text',
      'note'   => 'Text',
    );
  }
}
