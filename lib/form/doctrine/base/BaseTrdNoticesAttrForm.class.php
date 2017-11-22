<?php

/**
 * TrdNoticesAttr form base class.
 *
 * @method TrdNoticesAttr getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdNoticesAttrForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'notice_id'  => new sfWidgetFormInputText(),
      'content'    => new sfWidgetFormInputText(),
      'comment_id' => new sfWidgetFormInputText(),
      'reply_id'   => new sfWidgetFormInputText(),
      'extra'      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'notice_id'  => new sfValidatorInteger(array('required' => false)),
      'content'    => new sfValidatorPass(array('required' => false)),
      'comment_id' => new sfValidatorInteger(array('required' => false)),
      'reply_id'   => new sfValidatorInteger(array('required' => false)),
      'extra'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_notices_attr[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdNoticesAttr';
  }

}
