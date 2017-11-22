<?php

/**
 * TrdGoodsSupplier form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdGoodsSupplierForm extends BaseTrdGoodsSupplierForm
{
    public static $_store = array(
        1=>array('淘宝',array('taobao.com'),'淘宝'),
        2=>array('天猫',array('tmall.com'),'天猫'),
        3=>array('美亚',array('amazon.com'),'美国亚马逊'),
        4=>array('中亚',array('amazon.cn'),'中国亚马逊'),
        5=>array('日亚',array('amazon.co.jp'),'日本亚马逊'),
        6=>array('6pm',array('6pm.com'),'6pm'),
        7=>array('优购',array('yougou.com'),'优购'),
        8=>array('nike商城',array('nike.com'),'nike商城'),
        9=>array('京东',array('jd.com'),'京东'),
        10=>array('识货海淘',array('shihuo.cn\/haitao'),'识货海淘'),
        11=>array('识货团购',array('shihuo.cn\/tuangou'),'识货团购'),
        99=>array('其他',array(),'其他')
    );

    public static $_status = array(
        0=>'正常',
        1=>'下架',
    );

    public function configure()
    {
    }

    //获取shop name
    public static function getShopName($url){
        $storeName = TrdGoodsSupplierForm::getStoreName($url,'long');
        if($storeName == '淘宝' || $storeName == '天猫'){
            $urlinfo = parse_url($url);
            parse_str($urlinfo['query'], $params);

            if (!empty($params['id'])) {
                $itemInfo = tradeCommon::requestUrl('http://hws.m.taobao.com/cache/wdetail/5.0/?id='.$params['id'].'&qq-pf-to=pcqq.c2c', 'GET', NULL, NULL ,3);
                $itemInfo = json_decode($itemInfo, true);

                if($itemInfo['ret'][0] == "SUCCESS::调用成功" && !empty($itemInfo['data']['seller']['shopTitle'])){
                    $storeName = $itemInfo['data']['seller']['shopTitle'];
                }
            }
        }

        return $storeName;
    }

    //匹配商城
    public static function getStoreName($url, $type = 'short'){
        foreach(self::$_store as $store){
            if(!empty($store[1])){
                foreach($store[1] as $store_url){
                    if(preg_match("/".$store_url."/isu", $url)){

                        if($store[0] == '识货海淘') {//海淘特殊处理
                            $pattern_buy = '/.*?shihuo\.cn\/haitao\/buy\/(\d+)[-]{0,1}.*?/si';
                            preg_match($pattern_buy, $url, $match);
                            if (!empty($match[1])) {
                                $serviceRequest = new tradeServiceClient();
                                $serviceRequest->setMethod('daigouproduct.detail.get');
                                $serviceRequest->setVersion('1.0');
                                $serviceRequest->setApiParam('product_id', $match[1]);
                                $response = $serviceRequest->execute();
                                $is_self_business = $response->getValue('is_self_business');

                                if ($is_self_business) {
                                    return '识货自营';
                                }
                            }
                        }

                        if($type == 'short'){
                            return $store[0];
                        } else{
                            return $store[2];
                        }
                    }
                }
            }
        }

        if($type == 'short')
            return self::$_store[99][0];
        else
            return self::$_store[99][2];

    }
}
