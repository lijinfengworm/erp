<?php

class tradeOrderCoverSwitchTask extends sfBaseTask
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
            //new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', '1'),
      // add your own options here
    ));

    $this->namespace        = 'trade';
    $this->name             = 'OrderCoverSwitch';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:OrderCoverSwitch|INFO] task does things.
Call it with:

  [php symfony trade:OrderCoverSwitch|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);    
        set_time_limit(0);
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        
        $haitaoOrderObj = TrdHaitaoOrderTable::getInstance()->createQuery()->select()->where('updated_at > ?',date('Y-m-d H:i:s',time()-12*3600))->execute();
        if(count($haitaoOrderObj)>0){
            foreach($haitaoOrderObj as $key=>$oldObj){
                echo $oldObj->getId()."\n";
            if ($oldObj && ($oldObj->getStatus() != 8 || ($oldObj->getStatus() == 8 && ($oldObj->getPayStatus() > 0 || $oldObj->getRefundType()>0)))){
                $mainObj = TrdMainOrderTable::getInstance()->findOneByOrderNumber($oldObj->getOrderNumber());
                if(!$mainObj) $mainObj = new TrdMainOrder();
                $mainObj->set('order_number',$oldObj->getOrderNumber());
                $mainObj->set('ibilling_number',$oldObj->getIbillingNumber());
                $mainObj->set('hupu_uid',$oldObj->getHupuUid());
                $mainObj->set('hupu_username',$oldObj->getHupuUsername());
                $mainObj->set('address',$oldObj->getAddress());
                $mainObj->set('express_fee',$oldObj->getIntlFreight()+$oldObj->getExpressFee());
                $mainObj->set('total_price',$oldObj->getTotalPrice());
                $mainObj->set('refund',$oldObj->getRefund());
                $mainObj->set('number',$oldObj->getNumber());
                $mainObj->set('remark',$oldObj->getRemark());
                $mainObj->set('order_time',$oldObj->getOrderTime());
                $mainObj->set('pay_time',$oldObj->getPayTime());
                $mainObj->set('source',$oldObj->getSource());
                $mainObj->set('status',$this->getMainStatus($oldObj->getStatus()));
                $mainObj->save();
                $orderObj = TrdOrderTable::getInstance()->createQuery()->where('order_number = ?',$oldObj->getOrderNumber())->execute();
                if (count($orderObj) > 0){
                    foreach($orderObj as $k=>$v){
                        $v->set('order_number',$oldObj->getOrderNumber());
                        $v->set('ibilling_number',$oldObj->getIbillingNumber());
                        $v->set('title',$oldObj->getTitle());
                        $v->set('product_id',$oldObj->getProductId());
                        $v->set('gid',$oldObj->getGid());
                        $v->set('goods_id',$oldObj->getGoodsId());
                        $v->set('mart_order_number',$oldObj->getMartOrderNumber());
                        //$v->set('mart_order_time',$oldObj->getMartOrderTime());
                        $v->set('domestic_express_type',$oldObj->getDomesticExpressType());
                        $v->set('domestic_order_number',$oldObj->getDomesticOrderNumber());
                        $v->set('domestic_express_time',$oldObj->getDomesticExpressTime());
                        $v->set('attr',$oldObj->getAttr());
                        $v->set('business','美国亚马逊');
                        $v->set('refund_remark',$oldObj->getRefundRemark());
                        $v->set('refund_time',$oldObj->getRefundTime());
                        $v->set('grant_uid',$oldObj->getGrantUid());
                        $v->set('grant_username',$oldObj->getGrantUsername());
                        $v->set('is_plugin_added',$oldObj->getIsPluginAdded());
                        
                        $v->set('hupu_uid',$oldObj->getHupuUid());
                        $v->set('hupu_username',$oldObj->getHupuUsername());
                        $v->set('express_fee',round((($oldObj->getIntlFreight()+$oldObj->getExpressFee())/$oldObj->getNumber())*100)/100);
                        $v->set('total_price',round(($oldObj->getTotalPrice()/$oldObj->getNumber())*100)/100);
                        $v->set('price',$oldObj->getPrice());
                        $v->set('refund',round(($oldObj->getRefund()/$oldObj->getNumber())*100)/100);
                        if ($oldObj->getRefund()){
                            $v->set('refund_price',round(($oldObj->getPrice()/$oldObj->getNumber())*100)/100);
                            $v->set('refund_express_fee',round((($oldObj->getIntlFreight()+$oldObj->getExpressFee())/$oldObj->getNumber())*100)/100);
                        }
                        $v->set('order_time',$oldObj->getOrderTime());
                        $v->set('pay_time',$oldObj->getPayTime());
                        $v->set('source',$oldObj->getSource());
                        $v->set('created_at',$oldObj->getCreatedAt());
                        $v->set('updated_at',$oldObj->getUpdatedAt());
                        $v->set('source',$oldObj->getSource());
                        $v->set('status',$this->getStatus($oldObj->getStatus(),$oldObj->getPayStatus(),$oldObj->getRefundType()));
                        $v->set('pay_status',$this->getPayStatus($oldObj->getStatus(),$oldObj->getPayStatus(),$oldObj->getRefundType()));
                        $v->save();
                    }
                } else {
                    for($j=0;$j<$oldObj->getNumber();$j++){
                        $orderObj = new TrdOrder();
                        $orderObj->set('order_number',$oldObj->getOrderNumber());
                        $orderObj->set('ibilling_number',$oldObj->getIbillingNumber());
                        $orderObj->set('title',$oldObj->getTitle());
                        $orderObj->set('product_id',$oldObj->getProductId());
                        $orderObj->set('gid',$oldObj->getGid());
                        $orderObj->set('goods_id',$oldObj->getGoodsId());
                        $orderObj->set('mart_order_number',$oldObj->getMartOrderNumber());
                        //$orderObj->set('mart_order_time',$oldObj->getMartOrderTime());
                        $orderObj->set('domestic_express_type',$oldObj->getDomesticExpressType());
                        $orderObj->set('domestic_order_number',$oldObj->getDomesticOrderNumber());
                        $orderObj->set('domestic_express_time',$oldObj->getDomesticExpressTime());
                        $orderObj->set('attr',$oldObj->getAttr());
                        $orderObj->set('business','美国亚马逊');
                        $orderObj->set('refund_remark',$oldObj->getRefundRemark());
                        $orderObj->set('refund_time',$oldObj->getRefundTime());
                        $orderObj->set('grant_uid',$oldObj->getGrantUid());
                        $orderObj->set('grant_username',$oldObj->getGrantUsername());
                        $orderObj->set('is_plugin_added',$oldObj->getIsPluginAdded());
                        
                        $orderObj->set('hupu_uid',$oldObj->getHupuUid());
                        $orderObj->set('hupu_username',$oldObj->getHupuUsername());
                        $orderObj->set('express_fee',round((($oldObj->getIntlFreight()+$oldObj->getExpressFee())/$oldObj->getNumber())*100)/100);
                        $orderObj->set('total_price',round(($oldObj->getTotalPrice()/$oldObj->getNumber())*100)/100);
                        $orderObj->set('price',$oldObj->getPrice());
                        $orderObj->set('refund',round(($oldObj->getRefund()/$oldObj->getNumber())*100)/100);
                        if ($oldObj->getRefund()){
                            $orderObj->set('refund_price',round(($oldObj->getPrice()/$oldObj->getNumber())*100)/100);
                            $orderObj->set('refund_express_fee',round((($oldObj->getIntlFreight()+$oldObj->getExpressFee())/$oldObj->getNumber())*100)/100);
                        }
                        $orderObj->set('order_time',$oldObj->getOrderTime());
                        $orderObj->set('pay_time',$oldObj->getPayTime());
                        $orderObj->set('source',$oldObj->getSource());
                        $orderObj->set('created_at',$oldObj->getCreatedAt());
                        $orderObj->set('updated_at',$oldObj->getUpdatedAt());
                        $orderObj->set('status',$this->getStatus($oldObj->getStatus(),$oldObj->getPayStatus(),$oldObj->getRefundType()));
                        $orderObj->set('pay_status',$this->getPayStatus($oldObj->getStatus(),$oldObj->getPayStatus(),$oldObj->getRefundType()));
                        $orderObj->save();
                    }
                }
            }
            }
        }
        
        exit;
  }
  
 private function getMainStatus($status){
            switch ($status):
                case 0 :
                    return 0;
                    break;
                case 1 :
                    return 2;
                    break;
                case 2 :
                    return 2;
                    break;
                case 3 :
                    return 2;
                    break;
                case 4 :
                    return 2;
                    break;
                case 5 :
                    return 2;
                    break;
                case 6 :
                    return 2;
                    break;
                case 7 :
                    return 3;
                    break;
                case 8 :
                    return 4;
                    break;
                case 9 :
                    return 4;
                    break;
                case 10 :
                    return 5;
                    break;
                case 11 :
                    return 2;
                    break;
                case 12 :
                    return 2;
                    break;
                default :
                    return 0;
                    break;
            endswitch;
        }
        
        private function getStatus($status,$paystatus,$refundtype){
            if ($status == 0 || $status == 1) return 0;
            if ($status == 7)
            {
                return 2;
            }
            if ($status == 1 || $status == 2 || $status == 3 || $status == 4 || $status == 5 || $status == 6 || $status == 11 || $status == 12) return 1;
            if ($status == 8 || $status == 9) return 7;
            if ($status == 10) return 7; 
        }
        private function getPayStatus($status,$paystatus,$refundtype){
            if ($paystatus) return $paystatus;
            if ($status == 0) return 0;
            if ($status == 2 || $status == 3 || $status == 4 || $status == 5 || $status == 6 ||  $status == 7 || $status == 11 || $status == 12) return 1;
            if ($status == 8 || $status == 9){
                return 4;
            } 
            if ($status == 10 && $refundtype>0) return 4;
        }
}
