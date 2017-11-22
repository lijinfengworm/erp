<?php

class tradeHaitaoOrderCancelTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', '1'),
      // add your own options here
    ));

    $this->namespace        = 'trade';
    $this->name             = 'HaitaoOrderCancel';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:HaitaoOrderCancelTask|INFO] task does things.
Call it with:

  [php symfony trade:HaitaoOrderCancelTask|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);    
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        if ($options['type'] == 1){
            $orderInfo = $this->getExpireOrder();
            if ($orderInfo){
                foreach ($orderInfo as $k=>$v){
                    $v->setStatus(4);
                    $v->save();
                    $couponObj = TrdOrderActivityDetailTable::getInstance()->createQuery()->select()->where('order_number=?',$v->getOrderNumber())->andWhere('type = ?',0)->fetchOne();
                    $serviceRequest = new tradeServiceClient();
                    if($couponObj){
                        $couponObj->set('refund_type',1);
                        $couponObj->save();
                        //礼品卡返回
                        $serviceRequest->setMethod('lipinka.rollback');
                        $serviceRequest->setVersion('1.0');
                        $serviceRequest->setApiParam('user_id', $v->getHupuUid());
                        $attr = json_decode($couponObj->getAttr(),true);
                        $serviceRequest->setApiParam('card', $attr['code']);
                        $response = $serviceRequest->execute();
                    }
                    $history = new TrdHaitaoOrderHistory();
                    $history->setType(57);
                    $history->setOrderNumber($v->getOrderNumber());
                    $history->setExplanation('超过2小时未付款，系统自动取消订单');
                    $history->save();
                    //循环保存子订单
                    $orderObj = TrdOrderTable::getInstance()->createQuery()->where('order_number = ?',$v->getOrderNumber())->execute();
                    foreach($orderObj as $k=>$v){
                        $v->setStatus(7);//用户取消
                        $v->save();
                        //减库存
                        if (substr($v->getGoodsId(), 0, 2) == 'cn'){
                            $serviceRequest->setMethod('daigouproduct.skuStock');
                            $serviceRequest->setVersion('1.0');
                            $serviceRequest->setApiParam('id', $v->getGid());
                            $serviceRequest->setApiParam('num', 1);
                            $serviceRequest->setApiParam('type', 4);//取消订单
                            $serviceRequest->execute();
                        }
                    }
                }
            }
        } else if($options['type'] == 2){
//            $orderInfo = $this->getReceiverOrder();
//            if ($orderInfo){
//                foreach ($orderInfo as $k=>$v){
//                    $v->setStatus(7);
//                    $v->save();
//                    $history = new TrdHaitaoOrderHistory();
//                    $history->setType(7);
//                    $history->setOrderNumber($v->getOrderNumber());
//                    $history->setExplanation('超过15天未收货，系统自动收货，完成订单流程');
//                    $history->save();
//                }
//            }
        } 
  }
  protected function getExpireOrder(){
      //$time = date('Y-m-d H:i:s',strtotime("-2 day"));
      $time = date('Y-m-d H:i:s',(time()-7200));//改为2小时
      $query = TrdMainOrderTable::getInstance()->createQuery('m')
                ->select('*')
                ->where('m.status = ?',0)
                ->andWhere('m.order_time < ?',$time)
                ->limit(30);
        return $query->execute();      
  }
  
  protected function getReceiverOrder(){
      $time = strtotime("-15 day");
      $query = TrdHaitaoOrderTable::getInstance()->createQuery('m')
                ->select('*')
                ->where('m.status = ?',6)
                ->andWhere('m.domestic_express_time < ?',$time)
                ->limit(30);
        return $query->execute();      
  }
}
