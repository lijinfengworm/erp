<?php

/**
 * KaluliItemSkuTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class KaluliItemSkuTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object KaluliItemSkuTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('KaluliItemSku');
    }

    public static function getSkusByItemId( $id = null )
    {
        if(empty($id)) return null;
        return self::getInstance()->createQuery()->where('item_id = ?',$id)->orderBy("sort asc")->fetchArray();
    }
    public static function getSkusByItemIds( $id = [] )
    {
        if(empty($id)) return null;
        return self::getInstance()->createQuery()->whereIn('item_id ',$id)->orderBy("sort asc")->groupBy('item_id')->fetchArray();
    }

    public static function getSkuById( $id = null )
    {
        if(empty($id)) return null;
        $res =  self::getInstance()->createQuery()
            ->select('*')
            ->whereIn('id',(array)$id)
            ->fetchArray();
        if($res)
            return is_array($id) ? $res : $res[0];
        else
            return array();
    }


    /**
     * @param null $id
     * @return array|null
     * 获取所有的ID
     */
    public static function getIds(){
        $res =  self::getInstance()->createQuery()
            ->select('id')
            ->fetchArray();
        if($res)
            return $res;
        else
            return null;
    }


    public static function getOne($_id,$is_toArray = false,$field = false) {
        $info = self::getInstance()
            ->createQuery()
            ->select('*')
            ->andWhere('id = ?',$_id)
            ->fetchOne();
        if(empty($info)) return false;
        if(!$is_toArray) return $info;
        $info = $info->toArray();
        if($field) {
            return $info[$field];
        } else {
            return $info;
        }
        return $info;
    }

    /**
     * 判断某个sku是否还有仓库
     */
    public static function  getStorehouseCount($s_id) {
        $query = self::getInstance()
            ->createQuery()
            ->select('count(id) as num')
            ->andWhere('storehouse_id = ?',$s_id)
            ->fetchArray();
        return $query[0]['num'];
    }


    /*
     *sku 库存增减
     *array(1=>'下单成功',2=>'付款成功'，3=>'退款',4=>'未付款取消',5=>'退款取消')
     **/
    public static function setSkuStockById($id,$num,$type){
        $return = array();
        try {
            kaluliFun::getLock('kaluli.product.sku.stock'.$id,5);//获取锁
            $sku =  self::getInstance()->find($id);
            if($sku){
                if($type == '1'){
                    $lockNum = (int)$sku->getLockNum() + $num;
                    $totalNum = (int)$sku->getTotalNum() - $num;
                }elseif($type == '2'){
                    $lockNum = (int)$sku->getLockNum() - $num;
                    $totalNum= (int)$sku->getTotalNum();
                }elseif($type == '3'){
                    $lockNum = (int)$sku->getLockNum();
                    $totalNum = (int)$sku->getTotalNum() + $num;
                }elseif($type == '4'){
                    $lockNum = (int)$sku->getLockNum() - $num;
                    $totalNum = (int)$sku->getTotalNum() + $num;
                }elseif($type == '5'){
                    $lockNum = (int)$sku->getLockNum();
                    $totalNum = (int)$sku->getTotalNum() - $num;
                }

                $sku->setLockNum($lockNum);
                $sku->setTotalNum($totalNum);
                $sku->save();
            }
            kaluliFun::releaseLock('kaluli.product.sku.stock'.$id);//释放锁

            $return['status'] = true;
            $return['num'] =  $sku->getTotalNum();
        }catch(Exception $e) {
            $return['status'] = false;
            $return['message'] = $e->getMessage();
        }

        return $return;

    }
    
    /**
     * 解析sku规格
     */
    public static  function parseAttr($str,$key='')
    {
        $attr=  unserialize($str);
        if($attr)
        {
            if(isset($attr['attr'][$key]))
            {
                return $attr['attr'][$key];
            }
            return false;
        }
        return false;
    }
}