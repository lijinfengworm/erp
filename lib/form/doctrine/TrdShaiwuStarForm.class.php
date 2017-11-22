<?php

/**
 * TrdShaiwuStar form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdShaiwuStarForm extends BaseTrdShaiwuStarForm
{
  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);
      # 用户名
      $this->setWidget('username', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 100)));
      $this->setValidator('username', new sfValidatorString(array('required' => true, ), array('required' => '名称必填',)));
      # 精华晒物
      $this->setWidget('shaiwu_hot_num', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 100)));
      # 普通晒物
      $this->setWidget('shaiwu_num', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 100)));


      # 回调
      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );
  }

    public function myCallback($validator, $values)
    {
        $user = TrdAccountTable::getInstance()->createQuery()->where('hupu_username = ?',$values['username'])->fetchOne();
        if(empty($user) || empty($user->hupu_uid))
        {
            throw new sfException('用户不存在');
        }
        $values['uid'] = $user->hupu_uid;

        return $values;
    }

}
