<?php

class tradeRefreshItemAllsoldcountTask extends sfBaseTask
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
    $this->name             = 'refresh-itemallinfo';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:refresh-itemallinfo|INFO] task does things.
Call it with:

  [php symfony trade:refresh-iteminfo|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    sfContext::createInstance($this->configuration);    
    
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $taskConfig = sfConfig::get('app_tasks');
    
    $ttserver = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
    $key = 'refreshitemallinfolastid';
    $lastId = $ttserver->get($key);

    if(!empty($lastId))
    {
        $items = TrdItemAllTable::getInstance()->getItemsNoShoeForRefresh(300, $lastId,0);
    }else{
        $items = TrdItemAllTable::getInstance()->getItemsNoShoeForRefresh(300, 0,1);
    }
    //$items = TrdItemTable::getInstance()->getItemsForRefresh($taskConfig['sold_count']['refresh_amount'], $taskConfig['sold_count']['refresh_interval']);
    $processedItemCount = 0;
    
    $taobao = new TaobaoUtil;
 
    
    
    
    // Change the update with bulk update for performance reason
    if(count($items) > 0)
    {
        foreach ($items as $item)
        {
            if(!$item->getShopId())
            {
                $this->log( $item->getId().'  continue');
                continue;
            }
            
            $request = new TaobaoItemGetSoldCountRequest($item->getItemId());
            $soldCount = $request->send();

            $taobaoInfo = $taobao->getItemInfo($item->getItemId());      
            if($taobaoInfo)
            {
                if($taobaoInfo['freight_payer'] == 'seller')
                {
                    $freight_buyer = 1;
                }else{
                    $freight_buyer = 0;
                }
                $item->setFreightPayer($freight_buyer); 

                if($taobaoInfo['approve_status'] == 'instock')
                {
                    $item->setIsSoldout(1);
                }
                if($taobaoInfo['title']){
                    $item->setTitle($taobaoInfo['title']);
                    $this->log( $item->getId().'  title:'.$taobaoInfo['title'].'');
                }
                if($taobaoInfo['price'] && !$item->getOriginalPrice()){
                    $item->setPrice($taobaoInfo['price']);
                    $this->log( $item->getId().'  price:'.$taobaoInfo['price'].'');
                }else{
                    $this->log( $item->getId().'  price: can\'t update ');
                }
            }



    //        $taobaokeInfo = $taobao->gettaobaokeinfo($item->getItemId());
    //        if($taobaokeInfo && $taobaokeInfo['commission'])
    //        {
    //            $give_money = $taobaokeInfo['commission'];
    //        }else{
    //            $give_money = 0;
    //        }
    //        $item->setGiveMoney($give_money);
            if ($soldCount != 0)
            {
                $item->setSoldCount($soldCount);
            }
            $processedItemCount++;


            $item->save();
            $this->log( $item->getId().'  freight_buyer:'.$freight_buyer.'');
            $last_message = $item->getId().'  freight_buyer:'.$freight_buyer.'';
            $ttserver->set($key,$item->getId(),0,86400);
        }
    }else{
        $ttserver->set($key,0);
    }
     
    sfContext::getInstance()->getLogger()->log($last_message,1);
    sfContext::getInstance()->getLogger()->log($processedItemCount.' item(s) processed.  lastid:'.$ttserver->get('refreshiteminfolastid'),1);
    $this->log($processedItemCount.' item(s) processed.  lastid:'.$ttserver->get('refreshiteminfolastid'));
    
  }
}
