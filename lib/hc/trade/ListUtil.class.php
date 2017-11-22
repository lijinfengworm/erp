<?php
class ListUtil {
    public static function orderByShopId($items)
    {
        $newItems = array();
        while(1)
        {
            if(empty($items))
            {
                break;
            }
            $oldCount = count($newItems);
            
            $lastItem = end($newItems);
            $lastButOneItem = prev($newItems);
            
            foreach ($items as $key=>$val)
            {
                if( empty($val['shop_id'])  ||  ( $val['shop_id'] != $lastItem['shop_id'] && $val['shop_id'] != $lastButOneItem['shop_id'] ) )
                {
                    $newItems[] = $val;
                    unset($items[$key]);
                    break;
                }
            }
            
            if(count($newItems) == $oldCount)
            {
                foreach ($items as $val)
                {
                    $newItems[] = $val;
                }
                break;
            }
        }
        return $newItems;
    }
}