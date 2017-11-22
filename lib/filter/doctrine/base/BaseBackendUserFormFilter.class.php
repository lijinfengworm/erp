<?php

/**
 * BackendUser filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseBackendUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'uid'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'username'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'password'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'email'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'mobile'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'acl'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'last_login_time' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'wp_game_list'    => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'wpGame')),
    ));

    $this->setValidators(array(
      'uid'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'username'        => new sfValidatorPass(array('required' => false)),
      'password'        => new sfValidatorPass(array('required' => false)),
      'email'           => new sfValidatorPass(array('required' => false)),
      'mobile'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'acl'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'last_login_time' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'wp_game_list'    => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'wpGame', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('backend_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addWpGameListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.BackendUserWpGame BackendUserWpGame')
      ->andWhereIn('BackendUserWpGame.wp_game_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'BackendUser';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'uid'             => 'Number',
      'username'        => 'Text',
      'password'        => 'Text',
      'email'           => 'Text',
      'mobile'          => 'Number',
      'acl'             => 'Number',
      'last_login_time' => 'Date',
      'wp_game_list'    => 'ManyKey',
    );
  }
}
