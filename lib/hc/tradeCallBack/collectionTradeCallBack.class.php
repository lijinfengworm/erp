<?php
/**
 * 收藏驱动类  配合服务
 */
class collectionTradeCallBack {

    //代购收藏回调函数
    public static function  daigouAddCallBack($_collection_id = '',$_type = '',$userId = '') {
        $daigouInfo = TrdProductAttrTable::getInstance()->find($_collection_id);
        if(empty($daigouInfo)) throw new Exception('商品不存在！',403);
        $num = $daigouInfo->getCollectCount() + 1;
        $daigouInfo->setCollectCount($num);
        $daigouInfo->save();
        return true;
    }

    //代购删除收藏回调
    public static function daigouDelCallBack($_collection_id = '',$_type = '',$userId = '') {
        $daigouInfo = TrdProductAttrTable::getInstance()->find($_collection_id);
        if(empty($daigouInfo)) throw new Exception('商品不存在！',403);
        $num = $daigouInfo->getCollectCount() - 1;
        $daigouInfo->setCollectCount($num);
        $daigouInfo->save();
        return true;
    }





}