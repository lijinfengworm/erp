<?php

/**
 * KllTrainingprogram filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllTrainingprogramFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'       => new sfWidgetFormFilterInput(),
      'author'      => new sfWidgetFormFilterInput(),
      'cover'       => new sfWidgetFormFilterInput(),
      'articles'    => new sfWidgetFormFilterInput(),
      'order'       => new sfWidgetFormFilterInput(),
      'h_id'        => new sfWidgetFormFilterInput(),
      'public_time' => new sfWidgetFormFilterInput(),
      'category'    => new sfWidgetFormFilterInput(),
      'abstract'    => new sfWidgetFormFilterInput(),
      'content'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'title'       => new sfValidatorPass(array('required' => false)),
      'author'      => new sfValidatorPass(array('required' => false)),
      'cover'       => new sfValidatorPass(array('required' => false)),
      'articles'    => new sfValidatorPass(array('required' => false)),
      'order'       => new sfValidatorPass(array('required' => false)),
      'h_id'        => new sfValidatorPass(array('required' => false)),
      'public_time' => new sfValidatorPass(array('required' => false)),
      'category'    => new sfValidatorPass(array('required' => false)),
      'abstract'    => new sfValidatorPass(array('required' => false)),
      'content'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_trainingprogram_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllTrainingprogram';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'title'       => 'Text',
      'author'      => 'Text',
      'cover'       => 'Text',
      'articles'    => 'Text',
      'order'       => 'Text',
      'h_id'        => 'Text',
      'public_time' => 'Text',
      'category'    => 'Text',
      'abstract'    => 'Text',
      'content'     => 'Text',
    );
  }
}
