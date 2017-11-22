<?php

/*
 * 海淘商品接口服务
 */

class tradeHaitaoProductService {

    private static $redis = null;//redis对象 
    
    
    /*
     * 进行一些初始化工作
     */

    public function __construct()
    {
        $this->getRedis();
    }


    /*
     * 设置redis对象
     */

    public function getRedis()
    {
        if (!self::$redis)
        {
            self::$redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        }

        return self::$redis;
    }

    /**
     *
     * 获取代购商品的SKU
     * @param int $product_id 主商品id
     * @return array 
     */
    public function getProductSKU($product_id,$goods_id){
        if(!$product_id || !$goods_id) return array('errCode'=>2,'msg'=>'参数有误');
        $product_info = TrdProductAttrTable::getInstance()->find($product_id);
        if (!$product_info || !$product_info->getShowFlag()) return array('errCode'=>2,'msg'=>'商品不存在');
        //判断活动时间
        /*$now_time = time();
        if ($product_info->getStartDate() && $product_info->getStartDate() > $now_time) return array('errCode'=>3,'msg'=>'亲，该商品代购还未开始，敬请关注');
        if ($product_info->getEndDate() && $product_info->getEndDate() < $now_time) return array('errCode'=>4,'msg'=>'亲，该商品代购已经结束啦，下次早点来吧~');*/
        $goods_info = TrdHaitaoGoodsTable::getInstance()->createQuery('m')->select('*')->where('m.product_id = ?',$product_id)->andWhere('m.status = 0')->execute();
        
        if(count($goods_info)<1) return array('errCode'=>1,'msg'=>'商品不存在');
        $detail['limit'] = $product_info->getLimits();
        $detail['pid'] = $product_id;

        $goods_arr = array();
        $rate = '';
        foreach($goods_info as $k=>$v){//获取图片
            $pictures = array();
            $goods_attr = json_decode($v->getAttr(),1);
            if (empty($rate)) {
                if ($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'JPY') {
                    $rate = TrdHaitaoCurrencyExchangeTable::getRate('jpy');
                    $type = 'jpy';
                } elseif($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'USD') {
                    $rate = TrdHaitaoCurrencyExchangeTable::getRate();
                    $type = 'usd';
                } else {
                    $rate = 1;
                    $type = 'cny';
                }
            }

            foreach ($goods_attr['ImageSets']['ImageSet'] as $kk=>$vv){
                if (preg_match('/images-amazon.com/',$vv['LargeImage']['URL'])){
                    $pictures[$kk]['large'] = 'http://shihuoproxy.hupucdn.com/' . $this->url_base64_encode($vv['LargeImage']['URL'].'_SS500_.jpg').'?imageView2/1/w/400/h/400';
                    $pictures[$kk]['small'] = 'http://shihuoproxy.hupucdn.com/' . $this->url_base64_encode($vv['LargeImage']['URL'].'_SS500_.jpg').'?imageView2/1/w/70/h/70';
                } else {
                    $pictures[$kk]['large'] = 'http://shihuoproxy.hupucdn.com/' . $this->url_base64_encode($vv['LargeImage']['URL']).'?imageView2/1/w/400/h/400';
                    $pictures[$kk]['small'] = 'http://shihuoproxy.hupucdn.com/' . $this->url_base64_encode($vv['LargeImage']['URL']).'?imageView2/1/w/70/h/70';
                }

                //获取价格
                $exchange = $goods_attr['Offers']['Offer']['OfferListing']['Price']['FormattedPrice'];//外币假
                if ($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'JPY') {
                    $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate * 100) / 100;
                } elseif($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'USD') {
                    $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate) / 100;
                } else {
                    $price = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'];
                }

                $name = $goods_attr['ASIN'];
                $goods_arr[$v->getId()]['pictures'] = $pictures; 
                $goods_arr[$v->getId()]['exchange'] = $exchange; 
                $goods_arr[$v->getId()]['price'] = $price; 
                $goods_arr[$v->getId()]['name'] = $name; 
                $goods_arr[$v->getId()]['pid'] = $product_id; 
                $goods_arr[$v->getId()]['gid'] = $v->getId(); 
            }
        }

        //获取属性集合
        $content = json_decode($product_info->getContent(),true);
        //拼接属性
        $attr = $this->getFormatData($content,$product_id,$rate,$type);
        return array('errCode'=>0,'msg'=>'','data'=>array('detail'=>$detail,'attr'=>$attr,'goods'=>$goods_arr));
    }

    //获取某个商品的详细属性
    public function getGoodsInfo($pid, $gid)
    {
        if(!$pid || !$gid) return array('errCode' => 1,'msg' => '参数有误');
        $product_info = TrdProductAttrTable::getInstance()->find($pid);
        if (!$product_info || !$product_info->getShowFlag()) {
            return array('errCode' => 1,'msg' => '参数有误');
        }
        $goodsInfo = TrdHaitaoGoodsTable::getInstance()->createQuery('m')
            ->where('m.product_id = ?', $pid)
            ->andWhere('m.id = ?', $gid)
            ->andWhere('m.status = 0')
            ->fetchOne();
        if (!$goodsInfo) {
            return array('errCode' => 1,'msg' => '参数有误');
        }

        $goods_attr = json_decode($goodsInfo->getAttr(), true);
        if ($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'JPY') {
            $rate = TrdHaitaoCurrencyExchangeTable::getRate('jpy');
            $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate * 100) / 100;
        } elseif($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'USD') {
            $rate = TrdHaitaoCurrencyExchangeTable::getRate();
            $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate) / 100;
        } else {
            $price = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'];
        }

        $new_attr = array();
        $new_attr['title'] = $product_info->getTitle();
        if (isset($goods_attr['VariationAttributes']['VariationAttribute']) && !empty($goods_attr['VariationAttributes']['VariationAttribute'])){
            foreach($goods_attr['VariationAttributes']['VariationAttribute'] as $k=>$v){
                $new_attr['attr'][$v['Name']] = $v['Value'];
            }
        }
        $new_attr['price'] = $price;
        if (preg_match('/images-amazon.com/',$goods_attr['LargeImage']['URL'])){
            $new_attr['img'] = 'http://shihuoproxy.hupucdn.com/' . $this->url_base64_encode($goods_attr['LargeImage']['URL'].'_SS500_.jpg');
        } else {
            $new_attr['img'] = 'http://shihuoproxy.hupucdn.com/' . $this->url_base64_encode($goods_attr['LargeImage']['URL']);
        }
        return array('errCode'=>0,'msg'=>'','data'=> $new_attr);
    }
    
    //处理属性数据
    private function getFormatData($data,$pid,$rate,$type='usd'){
        foreach($data['content'] as $k=>&$v){
            $v['pid'] = $pid;

            if ($type == 'jpy') {
                $v['Price'] = ceil($v['price']*$rate*100)/100;
                $v['price'] = $v['price'];
                $v['currencyCode'] = '¥';
            } elseif($type == 'usd'){
                $v['Price'] = ceil($v['price']*$rate)/100;
                $v['price'] = $v['price']/100;
                $v['currencyCode'] = '$';
            } else {
                $v['Price'] = $v['price'];
                $v['price'] = $v['price'];
                $v['currencyCode'] = '¥';
            }

            if (preg_match('/images-amazon.com/',$v['img'])){
                $v['img'] = 'http://shihuoproxy.hupucdn.com/' . $this->url_base64_encode($v['img'].'_SS500_.jpg');
            } else {
                $v['img'] = 'http://shihuoproxy.hupucdn.com/' . $this->url_base64_encode($v['img']);
            }
            unset($v['code']);
        }
        $data['content'] = array_values($data['content']);
        return $data;
    }

    private function url_base64_encode($bin) {
        $base64 = base64_encode($bin);
        $base64 = str_replace('+', '-', $base64);
        $base64 = str_replace('/', '_', $base64);
        $base64 = str_replace('=', '', $base64);
        return $base64;
    }
}

