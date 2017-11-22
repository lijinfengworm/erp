<?php

/**
 * wpUser filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasewpUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'id_no'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'user_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'id_no'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('wp_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'wpUser';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'user_id' => 'Number',
      'id_no'   => 'Text',
    );
  }
}
