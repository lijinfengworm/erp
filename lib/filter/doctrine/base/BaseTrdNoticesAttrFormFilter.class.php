<?php

/**
 * TrdNoticesAttr filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdNoticesAttrFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'notice_id'  => new sfWidgetFormFilterInput(),
      'content'    => new sfWidgetFormFilterInput(),
      'comment_id' => new sfWidgetFormFilterInput(),
      'reply_id'   => new sfWidgetFormFilterInput(),
      'extra'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'notice_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'content'    => new sfValidatorPass(array('required' => false)),
      'comment_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reply_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'extra'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_notices_attr_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdNoticesAttr';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'notice_id'  => 'Number',
      'content'    => 'Text',
      'comment_id' => 'Number',
      'reply_id'   => 'Number',
      'extra'      => 'Text',
    );
  }
}
