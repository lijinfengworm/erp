<?php
/*
 *用户活动送券
 **/
class tradeUserActivitySendCouponsTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', '1'),
      // add your own options here
    ));

    $this->namespace        = 'trade';
    $this->name             = 'UserActivitySendCoupons';
    $this->briefDescription = '用户活动送券';
    $this->detailedDescription = <<<EOF
The [trade:UpdateSitemapTask|INFO] task does things.
Call it with:

  [php symfony trade:UserActivitySendCoupons|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
      sfContext::createInstance($this->configuration);
      set_time_limit(0);

      $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
      $redis->select(5);

      $_lucky_set = $redis->smembers(trdUserActivity::$_lucky_redis_set);

      if($_lucky_set){
          foreach($_lucky_set as $_lucky_set_val_seri){
             $_lucky_set_val = unserialize($_lucky_set_val_seri);
             if($_lucky_set_val['create_time'] < (time() - 3600*24)){//一天后送券
                 //订单号是否有效
                 $serviceRequest = new tradeServiceClient();
                 $serviceRequest->setMethod('order.get.order.base.info');
                 $serviceRequest->setVersion('1.0');
                 $serviceRequest->setApiParam('order_number', $_lucky_set_val['order_no']);
                 $response = $serviceRequest->execute();

                 $order_info = $response->getData();

                 if(!empty($order_info['data']['pay_status']) &&  $order_info['data']['pay_status'] == '已支付'){
                     //绑定券
                     $serviceRequest = new tradeServiceClient();
                     $serviceRequest->setMethod('user.activity.card.get');
                     $serviceRequest->setApiParam('amount', $_lucky_set_val['amount']);
                     $serviceRequest->setApiParam('activity_id', $_lucky_set_val['activity_id']);
                     $serviceRequest->setApiParam('activity_name', $_lucky_set_val['activity_name']);
                     $serviceRequest->setApiParam('uid', $_lucky_set_val['uid']);
                     $serviceRequest->setVersion('1.0');
                     $response = $serviceRequest->execute();

                     $date = date('Y-m-d H:i:s');
                     if(!$response->getError()){
                         $redis->srem(trdUserActivity::$_lucky_redis_set, $_lucky_set_val_seri);
                         echo "{$date}: send {$_lucky_set_val['amount']} to uid({$_lucky_set_val['uid']}) success\n";
                     }else{
                         echo "{$date}: send {$_lucky_set_val['amount']} to uid({$_lucky_set_val['uid']}) fail({$response->getError()})\n";
                     }
                 }else{
                     $redis->srem(trdUserActivity::$_lucky_redis_set, $_lucky_set_val_seri);
                 }
             }
          }
      }
  }
}
