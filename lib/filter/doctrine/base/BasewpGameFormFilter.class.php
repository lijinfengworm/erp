<?php

/**
 * wpGame filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasewpGameFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'game_order'               => new sfWidgetFormFilterInput(),
      'pay_success_callback_url' => new sfWidgetFormFilterInput(),
      'status'                   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'reason'                   => new sfWidgetFormFilterInput(),
      'currency'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'game_key'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'publish_key'              => new sfWidgetFormFilterInput(),
      'prorate_period_day'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'backend_users_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'BackendUser')),
    ));

    $this->setValidators(array(
      'name'                     => new sfValidatorPass(array('required' => false)),
      'game_order'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pay_success_callback_url' => new sfValidatorPass(array('required' => false)),
      'status'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reason'                   => new sfValidatorPass(array('required' => false)),
      'currency'                 => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'game_key'                 => new sfValidatorPass(array('required' => false)),
      'publish_key'              => new sfValidatorPass(array('required' => false)),
      'prorate_period_day'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'backend_users_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'BackendUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('wp_game_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addBackendUsersListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('BackendUserWpGame.backend_user_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'wpGame';
  }

  public function getFields()
  {
    return array(
      'id'                       => 'Number',
      'name'                     => 'Text',
      'game_order'               => 'Number',
      'pay_success_callback_url' => 'Text',
      'status'                   => 'Number',
      'reason'                   => 'Text',
      'currency'                 => 'Number',
      'game_key'                 => 'Text',
      'publish_key'              => 'Text',
      'prorate_period_day'       => 'Number',
      'backend_users_list'       => 'ManyKey',
    );
  }
}
