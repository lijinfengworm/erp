<?php

/**
 * ghPosition filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseghPositionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'english_name'    => new sfWidgetFormFilterInput(),
      'om_players_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'omPlayer')),
    ));

    $this->setValidators(array(
      'name'            => new sfValidatorPass(array('required' => false)),
      'english_name'    => new sfValidatorPass(array('required' => false)),
      'om_players_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'omPlayer', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gh_position_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addOmPlayersListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.omPlayerPosition omPlayerPosition')
      ->andWhereIn('omPlayerPosition.om_player_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'ghPosition';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'name'            => 'Text',
      'english_name'    => 'Text',
      'om_players_list' => 'ManyKey',
    );
  }
}
