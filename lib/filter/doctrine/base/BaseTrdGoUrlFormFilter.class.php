<?php

/**
 * TrdGoUrl filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdGoUrlFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'trd_news_id' => new sfWidgetFormFilterInput(),
      'url'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'encrypt_url' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'title'       => new sfWidgetFormFilterInput(),
      'type'        => new sfWidgetFormFilterInput(),
      'shop'        => new sfWidgetFormFilterInput(),
      'addtime'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'trd_news_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'url'         => new sfValidatorPass(array('required' => false)),
      'encrypt_url' => new sfValidatorPass(array('required' => false)),
      'title'       => new sfValidatorPass(array('required' => false)),
      'type'        => new sfValidatorPass(array('required' => false)),
      'shop'        => new sfValidatorPass(array('required' => false)),
      'addtime'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_go_url_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoUrl';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'trd_news_id' => 'Number',
      'url'         => 'Text',
      'encrypt_url' => 'Text',
      'title'       => 'Text',
      'type'        => 'Text',
      'shop'        => 'Text',
      'addtime'     => 'Date',
    );
  }
}
