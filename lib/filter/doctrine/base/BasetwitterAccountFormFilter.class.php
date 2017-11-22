<?php

/**
 * twitterAccount filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasetwitterAccountFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'twitter_user_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('twitterUser'), 'add_empty' => true)),
      'orginal_user_id' => new sfWidgetFormFilterInput(),
      'orginal_name'    => new sfWidgetFormFilterInput(),
      'type'            => new sfWidgetFormChoice(array('choices' => array('' => '', 'FACEBOOK' => 'FACEBOOK', 'TWITTER' => 'TWITTER', 'SINA' => 'SINA', 'QQ' => 'QQ', 'SOHU' => 'SOHU'))),
      'url'             => new sfWidgetFormFilterInput(),
      'need_translate'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'last_update'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'is_paused'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'hits'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'twitter_user_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('twitterUser'), 'column' => 'id')),
      'orginal_user_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'orginal_name'    => new sfValidatorPass(array('required' => false)),
      'type'            => new sfValidatorChoice(array('required' => false, 'choices' => array('FACEBOOK' => 'FACEBOOK', 'TWITTER' => 'TWITTER', 'SINA' => 'SINA', 'QQ' => 'QQ', 'SOHU' => 'SOHU'))),
      'url'             => new sfValidatorPass(array('required' => false)),
      'need_translate'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'last_update'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'is_paused'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'hits'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('twitter_account_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'twitterAccount';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'twitter_user_id' => 'ForeignKey',
      'orginal_user_id' => 'Number',
      'orginal_name'    => 'Text',
      'type'            => 'Enum',
      'url'             => 'Text',
      'need_translate'  => 'Boolean',
      'last_update'     => 'Date',
      'is_paused'       => 'Boolean',
      'hits'            => 'Number',
    );
  }
}
