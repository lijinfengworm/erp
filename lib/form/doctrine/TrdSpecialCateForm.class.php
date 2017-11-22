<?php

/**
 * TrdSpecialCate form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdSpecialCateForm extends BaseTrdSpecialCateForm
{




  public function configure()
  {
      unset($this['created_at']);
      unset($this['updated_at']);
      unset($this['deleted_at']);





      $this->setValidator('name', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '名称必填！')));





      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );

  }




    /**
     * 回调验证
     */
    public function myCallback($validator, $values) {
        $name_flag = false;
        if ($this->isNew()) {
            $name_flag = TrdSpecialCateTable::specialHasField('name',$values['name'],null);
        } else {
            $name_flag = TrdSpecialCateTable::specialHasField('name',$values['name'],$values['id']);
        }
        if($name_flag) throw new sfValidatorError($validator, '名称已存在，请换一个！');
        return $values;
    }




























    }
