<?php

/**
 * TrdCommentCluster form base class.
 *
 * @method TrdCommentCluster getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdCommentClusterForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'product_id'  => new sfWidgetFormInputText(),
      'comment_id'  => new sfWidgetFormInputText(),
      'reply_id'    => new sfWidgetFormInputText(),
      'user_id'     => new sfWidgetFormInputText(),
      'to_userid'   => new sfWidgetFormInputText(),
      'user_name'   => new sfWidgetFormInputText(),
      'to_username' => new sfWidgetFormInputText(),
      'content'     => new sfWidgetFormTextarea(),
      'imgs_attr'   => new sfWidgetFormTextarea(),
      'ip'          => new sfWidgetFormInputText(),
      'status'      => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'product_id'  => new sfValidatorInteger(array('required' => false)),
      'comment_id'  => new sfValidatorInteger(array('required' => false)),
      'reply_id'    => new sfValidatorInteger(array('required' => false)),
      'user_id'     => new sfValidatorInteger(array('required' => false)),
      'to_userid'   => new sfValidatorInteger(array('required' => false)),
      'user_name'   => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'to_username' => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'content'     => new sfValidatorString(array('max_length' => 10000, 'required' => false)),
      'imgs_attr'   => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'ip'          => new sfValidatorInteger(array('required' => false)),
      'status'      => new sfValidatorInteger(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_comment_cluster[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdCommentCluster';
  }

}
