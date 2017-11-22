<?php

/**
 * omMessageInbox filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseomMessageInboxFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'om_messages_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMessage'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'user_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'om_messages_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMessage'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('om_message_inbox_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'omMessageInbox';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'user_id'        => 'Number',
      'om_messages_id' => 'ForeignKey',
    );
  }
}
