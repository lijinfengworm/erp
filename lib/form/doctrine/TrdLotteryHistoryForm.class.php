<?php

/**
 * TrdLotteryHistory form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdLotteryHistoryForm extends BaseTrdLotteryHistoryForm
{
  public function configure()
  {

      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['lottery_id']);



      $this->widgetSchema->setLabels(array(
          'user_id' => '用户id',
          'phone' => '用户手机号',
          'prize_id' => '奖品id',
          'prize_name' => '奖品名字',
          'is_virtual' => '1虚拟 2实物',
          'card' => '卡密',
          'status' => '1中 2没中',
          'source' => '注册来源',
          'address' => '地址',
      ));

  }
}
