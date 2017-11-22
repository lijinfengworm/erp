<?php

/**
 * voiceColumnAuthor filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceColumnAuthorFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'intro'      => new sfWidgetFormFilterInput(),
      'avatar'     => new sfWidgetFormFilterInput(),
      'author_id'  => new sfWidgetFormFilterInput(),
      'slug'       => new sfWidgetFormFilterInput(),
      'categories' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'       => new sfValidatorPass(array('required' => false)),
      'intro'      => new sfValidatorPass(array('required' => false)),
      'avatar'     => new sfValidatorPass(array('required' => false)),
      'author_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'slug'       => new sfValidatorPass(array('required' => false)),
      'categories' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('voice_column_author_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceColumnAuthor';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'name'       => 'Text',
      'intro'      => 'Text',
      'avatar'     => 'Text',
      'author_id'  => 'Number',
      'slug'       => 'Text',
      'categories' => 'Text',
    );
  }
}
