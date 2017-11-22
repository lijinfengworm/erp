<?php

/**
 * KllActivitySet form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllActivitySetForm extends BaseKllActivitySetForm {

  public function configure() {
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this->widgetSchema['key']);
      unset($this->widgetSchema['role']);
      unset($this->widgetSchema['version']);
      unset($this->widgetSchema['status']);
      unset($this->widgetSchema['note']);
      $this->setWidget('title', new sfWidgetFormInput(array(), array('placeholder'=>'','class'=>' w460','size' => 50, 'maxlength' => 64)));
      $this->setWidget('remarks', new sfWidgetFormTextarea(array(), array('placeholder'=>'这里写一些备注信息','class'=>' textarea',)));
      $this->setValidator('title',
          new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 64),
              array('required' => '名称必填',  'max_length' => '不大于64个字')));

      $this->widgetSchema->setLabels(array(
          'title' => '标题',
          'key' => 'redis_key',
          'note' => '加入集合的ID',
          'remarks' => '备注信息',
      ));

      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );
  }


    public function myCallback($validator, $values) {
       if(count($_POST['roles']) == 2) {
           if(empty($_POST['roles']['add_id']))
               throw new sfValidatorError($validator,"请至少填写一个集合ID呀！");
       }
        return $values;
    }




    public function processValues($values) {
        $values = parent::processValues($values);
        if($this->getOption('is_add') == 1) {
            $values['status'] = 0;
            $values['version'] = 1;
            $values['key'] = FunBase::genRandomString(9);
        } else if ($this->getOption('is_edit') == 1) {
            //修改更新状态为0
            $values['status'] = 0;
            $values['version'] = (int)($this->getObject()->getVersion()) + 1;
            $values['key'] = $this->getObject()->getKey();
        }
        return $values;
    }




}
