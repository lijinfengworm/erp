<?php

/**
 * TrdGoOuterCodeInfoTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdGoOuterCodeInfoTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdGoOuterCodeInfoTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdGoOuterCodeInfo');
    }
    
    public static function formatdata(TrdGoClickInfo $trd_go_click_info,$taobao_item_info,$save = false){
       $info = new TrdGoOuterCodeInfo();
       $info->setClickId($trd_go_click_info->getId());
       if($trd_go_click_info->getCooickId()){
           $info->setCooickId($trd_go_click_info->getCooickId());
       }
       if($trd_go_click_info->getUid()){
           $info->setUid($trd_go_click_info->getUid());
       }
       if($trd_go_click_info->getUsername()){
            $info->setUsername($trd_go_click_info->getUsername());           
       }
       if($trd_go_click_info->getReferer()){
            $info->setReferer($trd_go_click_info->getReferer());           
       }
       if(self::getHostByUrl($trd_go_click_info->getReferer())){
            $info->setRefererHost(self::getHostByUrl($trd_go_click_info->getReferer()));
       }
       if(self::getIdByUrl($trd_go_click_info->getReferer())){
            $info->setRefererId(self::getIdByUrl($trd_go_click_info->getReferer()));           
       }
       if($trd_go_click_info->getDestination()){
            $info->setDestination($trd_go_click_info->getDestination());
       }
       if(self::getHostByUrl($trd_go_click_info->getDestination())){
            $info->setDestinationHost(self::getHostByUrl($trd_go_click_info->getDestination()));           
       }
       $info->setClickTime($trd_go_click_info->getCreatedAt());
       if(isset($taobao_item_info->item_num)){
            $info->setItemNum($taobao_item_info->item_num);           
       }
       if(isset($taobao_item_info->num_iid)){
           $info->setItemId($taobao_item_info->num_iid);
       }
       if(isset($taobao_item_info->item_title)){
           $info->setItemName($taobao_item_info->item_title);
       }
       if(isset($taobao_item_info->real_pay_fee)){
           $info->setItemPrice($taobao_item_info->real_pay_fee);
       }
       if(isset($taobao_item_info->seller_nick)){
           $info->setShopNick($taobao_item_info->seller_nick);
       }
       if(isset($taobao_item_info->category_name)){
           $info->setItemType($taobao_item_info->category_name);
       }
       if(isset($taobao_item_info->pay_time)){
           $info->setTradeTime($taobao_item_info->pay_time);
       }
       if(isset($taobao_item_info->commission)){
           $info->setTradeCommission($taobao_item_info->commission);
       }
       if($save)
       {
           $info->save();
       }
       return $info;
    }
    public static function getIdByUrl($url){
        preg_match('/(\d+)/', $url, $matches);
        if(!empty($matches[0])){
            return $matches[0];
        }
        return 0;
    }
    public static function getHostByUrl($url){
        $info = parse_url($url);
        if(!empty($info['host'])){
            return $info['host'];
        }else{
            return '';
        }

    }
}