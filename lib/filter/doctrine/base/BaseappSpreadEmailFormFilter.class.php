<?php

/**
 * appSpreadEmail filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseappSpreadEmailFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_name'  => new sfWidgetFormFilterInput(),
      'user_email' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_name'  => new sfValidatorPass(array('required' => false)),
      'user_email' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('app_spread_email_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'appSpreadEmail';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'user_name'  => 'Text',
      'user_email' => 'Text',
    );
  }
}
