<?php

class grouponToProductAction extends sfAction
{

    public function execute($request)
    {

        set_time_limit(9999);
        $grouponTable = TrdGrouponTable::getInstance();
        $grouponAll = $grouponTable->createQuery()->andWhere('status = 6')->execute();

        $groupon_product_table =  TrdGrouponProductTable::getInstance();

        foreach($grouponAll as $k=>$groupon){
            $groupon_product = $groupon_product_table->getById($groupon->getId());
            if(!$groupon_product){
                $groupon_product = new TrdGrouponProduct();
                $groupon_product->setId($groupon->getId());
            }

            $groupon_product->setBrandId($groupon->getBrandId());
            $groupon_product->setAttendCount($groupon->getAttend_count());
            $groupon_product->setDiscount($groupon->getDiscount());
            $groupon_product->setCategoryId($groupon->getCategory_id());
            $groupon_product->setStartTime($groupon->getStartTime());
            $groupon_product->setEndTime($groupon->getEndTime());
            $groupon_product->setRank($groupon->getRank());
            $groupon_product->setPrice($groupon->getPrice());
            $groupon_product->save();
            usleep(1000);
            echo 1;
       }

        $api_url = sfConfig::get('app_javaapi');
        tradeCommon::requestUrl($api_url['url'].'/so/groupon/reindex','POST',NULL,NULL,3);  //索引重建
        exit;
    }
}