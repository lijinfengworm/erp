<?php

/**
 * TrdGrouponAd form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdGrouponAdForm extends BaseTrdGrouponAdForm
{

    public static $statusAll = array(
        '6'=>'团购未通过',
        '5'=>'待审核',//团购审核通过以后广告待审核
        '4'=>'已取消 已退款',
        '3'=>'审核通过',
        '2'=>'审核不通过',
        '1'=>'待审核',//已付款
        '0'=>'待付款',
    );
    public static $status = array(
        '3'=>'通过',
        '2'=>'未通过',
    );

  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['group_id']);
      unset($this['order_id']);
      unset($this['stime']);
      unset($this['etime']);
      unset($this['is_cancel']);
      unset($this['pay_date']);
      unset($this['pay_type']);


      # 标题
      $this->setWidget('title', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 40)));
      $this->setValidator('title', new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 40), array('required' => '标题必填',  'max_length' => '标题不大于40个字', 'min_length' => '标题不少于8个字')));



      # 状态
      $this->setWidget('status', new sfWidgetFormChoice(array('choices'=>self::$status)));
      $this->setValidator('status', new sfValidatorChoice(array('choices'=>array_keys(self::$status),'required' => true)));//验证

      # 回退理由
      $this->setWidget('reason', new sfWidgetFormInput(array(), array('size' => 40, 'maxlength' => 40)));

      # 回调
      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );


  }


    public function myCallback($validator, $values)
    {
        if($values['status'] == 3 && empty($values['title']))
        {
            throw new sfValidatorError($validator, '请填写标题');
        }


        if($values['status'] != 2 && !empty($values['reason']))
        {
            throw new sfValidatorError($validator, '退款理由只能未通过状态下填写');
        }

        # 审核未通过则退款
        if($values['id'] && $values['status'] == 2)
        {
            $sign = 'tuangou_groupon_adAdmin_'.$values['id'];
            $order = TrdPayOrderTable::getInstance()->findOneBy('sign',$sign);

            if(empty($order) || empty($order->pay_uid) )
            {
                throw new sfValidatorError($validator, '付款订单不存在');
            }
            $param = array(
                'uid'=>$order->pay_uid,
                'sign'=>$sign,
            );

            $json_info = tradeCommon::requestUrl('http://www.shihuo.cn/pay/closePurchaseOrder','POST',http_build_query($param),NULL,5);
            $arr_info = json_decode($json_info,true);

            if(empty($arr_info['orderNo']))
            {
                $message = array(
                    'message' => '用户团购广告系统取消失败',
                    'param' => $param,
                    'res' => $arr_info,
                );
                tradeLog::info('groupon_ad', $message);
                throw new sfValidatorError($validator, $arr_info['errorMsg']);
            }
            else
            {
                $values['is_cancel'] = 1;
                $ad = TrdGrouponAdTable::getInstance()->find($values['id']);
                # 记录积分消费日志
                if($arr_info['payType'] == 1 && !empty($ad))
                {
                    $creditLog = new TrdBusinessCreditlog();
                    $creditLog->uid = $arr_info['payUid'];
                    //  $creditLog->admin_id = sfContext::getInstance()->getUser()->getTrdUserHuPuId();
                    $creditLog->type = 4;
                    $creditLog->num = $arr_info['amount']*100;
                    $creditLog->note = '团购ID:'.$ad->group_id.'系统退回';
                    $creditLog->date = time();
                    $creditLog->save();
                }
            }

        }

        return $values;
    }
}
