<?php

/**
 * wpServer filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasewpServerFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'wpgame_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('wpgame'), 'add_empty' => true)),
      'name'      => new sfWidgetFormFilterInput(),
      'status'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'reason'    => new sfWidgetFormFilterInput(),
      'server_no' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'wpgame_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('wpgame'), 'column' => 'id')),
      'name'      => new sfValidatorPass(array('required' => false)),
      'status'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reason'    => new sfValidatorPass(array('required' => false)),
      'server_no' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('wp_server_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'wpServer';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'wpgame_id' => 'ForeignKey',
      'name'      => 'Text',
      'status'    => 'Number',
      'reason'    => 'Text',
      'server_no' => 'Text',
    );
  }
}
