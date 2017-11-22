<?php

/**
 * voiceMessagePublisher filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceMessagePublisherFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'editor_name'   => new sfWidgetFormFilterInput(),
      'reporter_name' => new sfWidgetFormFilterInput(),
      'weibo_account' => new sfWidgetFormFilterInput(),
      'attr'          => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'editor_name'   => new sfValidatorPass(array('required' => false)),
      'reporter_name' => new sfValidatorPass(array('required' => false)),
      'weibo_account' => new sfValidatorPass(array('required' => false)),
      'attr'          => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('voice_message_publisher_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceMessagePublisher';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'user_id'       => 'Number',
      'editor_name'   => 'Text',
      'reporter_name' => 'Text',
      'weibo_account' => 'Text',
      'attr'          => 'Text',
    );
  }
}
