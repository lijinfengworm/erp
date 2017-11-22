<?php

/**
 * KllCpsUser form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllCpsUserForm extends BaseKllCpsUserForm {

    private $_status = array(
        '0'=>'正常',
        '1'=>'删除',
    );

    private $_type = array(
        '1'=>'合作联盟',
        '2'=>'主播',
        '3'=>'外推渠道',
    );


  public function configure() {
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['hupu_uid']);
      unset($this['hupu_username']);


      $this->setWidget('type', new sfWidgetFormChoice(array("choices" => $this->_type)));
      $this->setWidget('status', new sfWidgetFormChoice(array("choices" => $this->_status)));


      $this->setWidget('union_id', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator('union_id',
          new sfValidatorString(array('required' => true, 'trim' => true),
              array('required' => '唯一标识符必填！')));


      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );


  }




    /**
     * 回调验证
     */
    public function myCallback($validator, $values) {
        if ($this->isNew()) {
           $union_id  = KllCpsUserTable::getInstance()->isHasField('union_id',$values['union_id'],0);
        } else {
            $union_id = KllCpsUserTable::getInstance()->isHasField('union_id',$values['union_id'],$values['id']);
        }
        if($union_id) throw new sfValidatorError($validator, '唯一标识符已存在，请换一个！');
        return $values;
    }




}
