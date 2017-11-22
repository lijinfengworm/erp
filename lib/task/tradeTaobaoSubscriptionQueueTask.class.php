<?php

class tradeTaobaoSubscriptionQueueTask extends sfBaseTask
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
      // add your own options here
    ));

    $this->namespace        = 'trade';
    $this->name             = 'SubscriptionQueue';
    $this->briefDescription = '淘宝信息更新队列';
    $this->detailedDescription = <<<EOF
The [trade:TaobaoSubscriptionQueue|INFO] task does things.
Call it with:
    处理
  [php symfony trade:TaobaoSubscriptionQueue|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        sfContext::createInstance($this->configuration);    
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        //$connection = $databaseManager->getDatabase($options['connection'])->getConnection();
//        $taobao = new TaobaoUtil();
//        $taobao->getIncrementSubscription();
        $taskConfig = sfConfig::get('app_tasks');
        //需要增加订阅的id列表
        $taobao = new TaobaoUtil();
        for($i = 0;$i<50;$i++){
           $itemIds = TaobaoItemUpdateUtil::getInstance(TRUE)->getAddSubscriptionPop(20);
           if(empty($itemIds)){
               break;
           }
           $res = $taobao->addIncrementSubscription($itemIds);
           $this->log('处理需要增加订阅的itemid列表-------订阅数:'.$res->total_results);
        }
        //需要删除订阅的id列表
        for($i = 0;$i<50;$i++){
           $itemIds = TaobaoItemUpdateUtil::getInstance(TRUE)->getDeleteSubscriptionPop(20);
           if(empty($itemIds)){
               break;
           }
           $res = $taobao->deleteIncrementSubscription($itemIds);
           
           $this->log('处理需要删除订阅的itemid列表-------订阅数:'.$res->total_results);
        }
        
        $itemIds = TaobaoItemUpdateUtil::getInstance(TRUE)->getUpdatedPop(30);
//        foreach ($itemIds as $key=>$itemId){
//            $this->log('处理商品Id:'.$itemId);               
//        }
        
        $this->log($itemIds);
        if(!empty($itemIds)){
            $itemAlls = TrdItemAllTable::getInstance()->createQuery('til')->leftJoin('til.TrdItem ti')->whereIn('til.item_id',$itemIds)->execute();
            foreach ($itemAlls as $item){
                $request = new TaobaoItemGetSoldCountRequest($item->getItemId());
                $this->log('处理商品Id:'.$item->getItemId());  
                $soldCount = $request->send();
                $taobaoInfo = $taobao->getItemInfo($item->getItemId(),TRUE); 
                
                if($item->getTrdItem()){
                     $info = $item->getTrdItem();           
                }else{
                     $info = $item;
                }
                if($taobaoInfo)
                {
                    if($taobaoInfo['freight_payer'] == 'seller')
                    {
                        $freight_buyer = 1;
                        $this->log('卖家包邮');  
                    }else{
                        $freight_buyer = 0;
                        $this->log('买家包邮');  
                    }
                    
                    $info->setFreightPayer($freight_buyer); 
                    if($taobaoInfo['approve_status'] == 'instock')
                    {
                        $this->log('卖完了');
                        $info->setIsSoldout(1);
                    }
                    if($taobaoInfo['title']){
                        $info->setTitle($taobaoInfo['title']);
                        $this->log( $item->getId().'  title:'.$taobaoInfo['title'].'');
                    }
                    if($taobaoInfo['price'] && !$item->getOriginalPrice()){
                        $info->setPrice($taobaoInfo['price']);
                        $this->log( $item->getId().'  price:'.$taobaoInfo['price'].'');
                    }else{
                        $this->log( $item->getId().'  price: can\'t update ');
                    }
                }
                if ($soldCount != 0)
                {
                    $this->log('卖出了'.$soldCount.'件');
                    $info->setSoldCount($soldCount);
                }
                $info->save();
                
            }
        }
        $this->log('success');
  }
}
