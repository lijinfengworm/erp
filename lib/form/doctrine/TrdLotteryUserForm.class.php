<?php

/**
 * TrdLotteryUser form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdLotteryUserForm extends BaseTrdLotteryUserForm
{
  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['lottery_id']);


      $this->widgetSchema->setLabels(array(
          'phone' => '手机号',
          'verify' => '验证码',
          'lottery_num' => '抽奖次数',
          'attr_num' => '是否分享',
          'source' => '注册来源',
      ));



  }
}
