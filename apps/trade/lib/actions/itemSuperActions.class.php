<?php

/**
 * product actions.
 *
 * @package    HC
 * @subpackage product
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class itemSuperActions extends sfActions
{        
    protected function getTaobaoItem($itemId)
    {
      $c = $this->getTopClient();
      
      $req = new ItemGetRequest;
      $req->setFields("num_iid,title,price,cid,props_name,props,property_alias,num,detail_url,nick,volume,freight_payer");
      $req->setNumIid($itemId);
      $resp = $c->execute($req);            

      return $resp;
    }
    
    protected function getShop($taobaoUsername)
    {
      $taobaoShop = $this->getTaobaoShop($taobaoUsername);

      if (property_exists($taobaoShop, 'code'))
      {
        return null;
      }
      
      $shop = new TrdShop();
          
      $shop->setExternalId($taobaoShop->shop->sid);
      $shop->setLink('http://shop'.$taobaoShop->shop->sid.'.taobao.com');
      $shop->setName($taobaoShop->shop->title);
      $shop->setSrc(TrdShopTable::TAOBAO);
      $shop->setOwnerName($taobaoUsername);
      $shop->setStatus(TrdShopTable::STATUS_NORMAL);
      
      return $shop;
    }
    
    protected function getItem($itemId, $type = "shoe")
    {
      $item = $this->getTaobaoItem($itemId);
      
      if (property_exists($item, 'code'))
      {
        return null;
      }      
      
      if($type == "shoe") {
          return $this->normalizeTaobaoItemInfo($item);
      } else if($type == "all") {
          return $this->normalizeAllItemInfo($item);
      }
    }
    protected function getNowUmpPrice($itemid){

    }
    protected function getUmpInfo($itemid){
        $c = $this->getTopClient();
        $req = new UmpPromotionGetRequest();
        $req->setItemId($itemid);
        $resp = $c->execute($req); 
        
        if(isset($resp->code) && $resp->code == 7){
            return 'sleep';
        }else{
            if(isset($resp->promotions->promotion_in_item->promotion_in_item[0])){
                return $resp->promotions->promotion_in_item->promotion_in_item[0]->item_promo_price;
            }else{
                return FALSE;
            }
        }
        
    }

    protected function normalizeAllItemInfo($item)
    {
      $item = $item->item;
      $itemProps = $item->props_name;      
      $props = $this->normalizeTaobaoItemProps($itemProps);

      //$soldCount = $this->getItemSellCount($item->num_iid);
      $count_request = new TaobaoItemGetSoldCountRequest($item->num_iid);
      $soldCount = $count_request->send();

      $itm = new TrdItemAll();
      
      $itm->setItemId($item->num_iid); 
      $itm->setTitle($item->title);
      $itm->setPrice($item->price);
      $itm->setUrl($item->detail_url);
      $itm->setSoldCount($soldCount);
      $itm->setExternalUsername($item->nick);

      //是否包邮
      if(isset($item->freight_payer) && $item->freight_payer == "seller") {
          $itm->setFreightPayer(1);
      } else {
          $itm->setFreightPayer(0);
      }

      //是否淘宝客
      /*
      if($money = $this->isGiveMoney($item->num_iid)) {
          $itm->setGiveMoney($money);
      } else {
          $itm->setGiveMoney(0);
      }
       */

      $itm->setGiveMoney(0);
      
      if (isset($props['货号']) && count($props['货号']))
      {
        $itm->setItemNo(array_shift($props['货号']));
      }

      return $itm;
    }
    
    protected function normalizeTaobaoItemInfo($item)
    {
      $item = $item->item;
      $itemProps = $item->props_name;      
      $props = $this->normalizeTaobaoItemProps($itemProps);

      //$soldCount = $this->getItemSellCount($item->num_iid);
      $count_request = new TaobaoItemGetSoldCountRequest($item->num_iid);
      $soldCount = $count_request->send();

      $itm = new TrdItem();
      
      $itm->setItemId($item->num_iid); 
      $itm->setName("");
      $itm->setTitle($item->title);
      $itm->setPrice($item->price);
      $itm->setUrl($item->detail_url);
      $itm->setSoldCount($soldCount);
      $itm->setExternalUsername($item->nick);

      //是否包邮
      if(isset($item->freight_payer) && $item->freight_payer == "seller") {
          $itm->setFreightPayer(1);
      } else {
          $itm->setFreightPayer(0);
      }

      //是否淘宝客
      /*
      if($money = $this->isGiveMoney($item->num_iid)) {
          $itm->setGiveMoney($money);
      } else {
          $itm->setGiveMoney(0);
      }
       */

      $itm->setGiveMoney(0);
      
      if (isset($props['颜色分类']) && count($props['颜色分类']))
      {
        $itm->setColorIds(implode(',', $this->convertTaobaoColor($props['颜色分类'])));
      }
      
      if (isset($props['品牌']) && count($props['品牌']))
      {
        $itm->setBrandId($this->convertTaobaoBrand(array_shift($props['品牌'])));
      }
      
      if (isset($props['尺码']) && count($props['尺码']))
      {
        $sex = null;
        
        if (isset($props['运动鞋性别']) && count($props['运动鞋性别']))
        {
          $sex = $this->convertSex(array_shift($props['运动鞋性别']));
        }
        
        if (!is_null($sex))
        {
          $itm->setSizeIds(implode(',', $this->convertTaobaoSize($props['尺码'], $sex)));          
        }
      }
      
      if (isset($props['货号']) && count($props['货号']))
      {
        $itm->setItemNo(array_shift($props['货号']));
      }

      return $itm;
    }
    
    protected function convertSex($sex)
    {
      switch ($sex)
      {
        case '男性':
          return 1;
        break;
        
        case '女性':
          return 0;
        break;
        
        case '男女性通用':        
          return 2;
        break;
                
        default:
          return null;
        break;
      }
    }
    
    protected function convertTaobaoColor(array $colors)
    {      
      $cls = array();
      
      // TODO: Replace this with one query
      foreach ($colors as $color)
      {
        $colorObj = TrdColorTable::getInstance()->getColorByName($color);
                
        if ($colorObj)
        {
          $cls[] = $colorObj->getId();
        }    
      }
      
      return $cls;
    }
    
    protected function convertTaobaoSize(array $sizes, $sex)
    {
      $szs = array();     
      
      foreach ($sizes as $size)
      {
        $sizeObjs = TrdSizeTable::getInstance()->getSize($size, $sex);
        
        if (count($sizeObjs))
        {
          foreach ($sizeObjs as $sizeObj)
          {
            $szs[] = $sizeObj->getId();
          }
        }
      }
      
      return $szs;
    }
    
    protected function convertTaobaoBrand($brand)
    {
      $brandObj = TrdBrandTable::getInstance()->getBrandByName($brand);
      
      if (!$brandObj)
      {
        return '';
      }
      
      return $brandObj->getId();
    }
    
    protected function normalizeTaobaoItemProps($props)
    {
      $propArray = explode(';', $props);

      $propGroup = array();
      
      foreach ($propArray as $prop)
      {
        $p = explode(':', $prop);
        
        $propGroup[$p[2]][] = $p[3];
      }
      
      return $propGroup;
    }
    
    protected function getTopClient()
    {
      $c = new TaoBaoTopClient();
      return $c;
    }
    
    protected function getTaobaoItemCategory($itemId)
    {
      $c = $this->getTopClient();
      
      $req = new ItemcatsGetRequest;
      $req->setFields("cid,parent_cid,name,is_parent");
      $req->setCids($itemId);
      $resp = $c->execute($req);
      
      return $resp;
    }
    
//    protected function getItemSellCount($itemId)
//    { 
//      $apiConfig = sfConfig::get('app_api');
//      
//      $response = file_get_contents($apiConfig['taobao_item_detail']['url'].$itemId);
//      $sellCount = 0;
//      
//      print_r($response);
//      preg_match("/quanity: (\d+),/", $response, $matches); 
//
//      print_r($matches);
//      exit();
//      
//      return $sellCount;
//    }
    
    protected function getTaobaoShop($sellerNick)
    {
      $c = $this->getTopClient();
      
      $req = new ShopGetRequest;
      $req->setFields("sid,cid,title,nick,shop_score");
      $req->setNick($sellerNick);
      $resp = $c->execute($req);
      
      return $resp;
    }

    protected function getTaobaoSeller($sellerNick)
    {
      $c = $this->getTopClient();
      
      $req = new UserGetRequest;
      $req->setFields("sid,seller_credit,location");
      $req->setNick($sellerNick);
      $resp = $c->execute($req);
      
      return $resp;
    }

    //前台商品信息
    protected function get_show_info($item, $hasCredential, $like_arr = array(), $detail = false) {
        $this->getContext()->getConfiguration()->loadHelpers('TrdLink');       
        if(in_array($item["id"], $like_arr)) {
            $item["like"] = true;
        }
        if($detail) {
            //店铺类型
            if(strchr($item["url"], "taobao")) {
                $TrdShop = TrdShopTable::getInstance();
                $item["shop_type"] = $TrdShop->get_type($item["shop_id"]);
            } else {
                $shop_info =  $this->get_shop_type($item["url"]);
                $item["shop_type"] = $shop_info["type"];
                $item["shop_domain"] = $shop_info["domain"];
                $item["shop_link"] = $this->get_shop_link($item["url"]);
            }
        }
        $item['go_url'] = $item['url'];
        $item['url']     = go_link($item['url'], empty($item['item_id'])?0:$item['item_id']);                
        $item['edit_url'] = '';

        if ($hasCredential)
        {
            $item['edit_url'] = $this->getController()->genUrl('@item_edit?item_id='.$item['id']);
        }

        //详情页地址
        $item['detail_url'] = $this->getController()->genUrl("@item_detail?item_id={$item['id']}");
        //大图地址
        $item['img_big_url'] = str_replace(".jpg", "_300.jpg", $item["img_url"]);

        //各种缩略图的地址
        $item['img_300'] = str_replace(".jpg", "_300.jpg", $item["img_url"]);
        $item['img_210'] = str_replace(".jpg", "_210.jpg", $item["img_url"]);
        $item['img_150'] = str_replace(".jpg", "_150.jpg", $item["img_url"]);
        $item['img_90'] = str_replace(".jpg", "_90.jpg", $item["img_url"]);

        if (!$item['Brand'])
        {
            $item['brand_name'] = '其他';
            $item['brand_id'] = 19;
        }
        else
        {
            $item['brand_name'] = $item['Brand']['name'];
            $item['brand_id'] = $item['Brand']['id'];
        }

        unset($item['Brand']);

        if (!isset($item['Category']) || !$item['Category'])
        {
            $item['category_name'] = '';
        }
        else
        {
            $item['category_name'] = $item['Category']['name'];
        }

        unset($item['Category']);

        //补全title
        if(!isset($item["title"]) || !$item["title"]) {
            $item["title"] = $item["name"];
        }

        if($item['is_verified'] == 1) {
            $item["verified_text"] = "已鉴定";
        } else {
            $item["verified_text"] = "未鉴定";
        }
        
         //时间
        if(!empty($item["publish_date"])) {
            $item["publish_date"] = $this->getFormatTime($item["publish_date"]);
        } else {
            $item["publish_date"] = $this->getFormatTime(strtotime($item["created_at"]));
        }

        return $item;
    }

    //前台商品信息(全品类)
    protected function get_show_info_all($item, $hasCredential, $like_arr = array(), $detail = false) {
        $this->getContext()->getConfiguration()->loadHelpers('TrdLink');            
        $rout_name = sfContext::getInstance()->getRouting()->getCurrentRouteName();

        $item['go_url'] = $item['url'];
        //马老板配置flag
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $ma_key = 'trd_go_ma_config_info';
        $ma_info = unserialize($redis->get($ma_key));
        $ma_source = isset($ma_info['status']) && $ma_info['status'] == 2 ? 2 : 1;
        $item['url']     = go_link($item['url'], empty($item['item_id'])?0:$item['item_id'],$ma_source);                
        $item['edit_url'] = '';

        if ($hasCredential)
        {
            $item['edit_url'] = $this->getController()->genUrl('@item_all_edit?item_id='.$item['id'] . "&type=find");
        }

        if(isset($item["shoe_id"])) {
            if(in_array($item["shoe_id"], $like_arr)) {
                $item["like"] = true;
            }
        } else {
            if(in_array($item["id"], $like_arr)) {
                $item["like"] = true;
            }
        }

        //详情页地址
        $item['detail_url'] = $this->getController()->genUrl("@find_product_detail?item_id={$item['id']}");
        
        //大图地址
        $item['img_big_url'] = str_replace(".jpg", "_300.jpg", $item["img_url"]);

        //各种缩略图的地址
        $item['img_300'] = str_replace(".jpg", "_300.jpg", $item["img_url"]);
        $item['img_210'] = str_replace(".jpg", "_210.jpg", $item["img_url"]);
        $item['img_150'] = str_replace(".jpg", "_150.jpg", $item["img_url"]);
        $item['img_90'] = str_replace(".jpg", "_90.jpg", $item["img_url"]);

        if($item['width'] > 208){
            $item['height'] = round(208*$item['height']/$item['width']);
            $item['width'] = 208;
        }
        if (!isset($item['Category']) || !$item['Category'])
        {
            $item['category_name'] = '';
        }
        else
        {
            $item['category_name'] = $item['Category']['name'];
        }

        unset($item['Category']);

        //补全title
        if(!isset($item["title"]) || !$item["title"]) {
            $item["title"] = $item["name"];
        }

        //时间
        if(!empty($item["publish_date"])) {
            $item["publish_date"] = $this->getFormatTime($item["publish_date"]);
        } else {
            $item["publish_date"] = $this->getFormatTime(strtotime($item["created_at"]));
        }
        
        //是否是天猫淘宝的商品
        $url = parse_url($item['go_url']);
        $flag = false;
        if ($url['host'] == 'item.taobao.com' || ($url['host'] == 'detail.tmall.com' && strstr($url['path'], '/item.htm') != false)) {
            parse_str($url['query'], $queryStringArray);
            if (!isset($queryStringArray['id']) || !is_numeric($queryStringArray['id'])) {
                $flag = false;
            }
            $flag = true;
        }
        $item['is_taobao'] = $flag;

        return $item;
    }
    
    //前台商品信息(全品类) 新的
    protected function get_show_info_all_new($item, $hasCredential) {
        $this->getContext()->getConfiguration()->loadHelpers('TrdLink');            
        $rout_name = sfContext::getInstance()->getRouting()->getCurrentRouteName();

        if ($item['root_id'] == 1 && $item['children_id'] == 8){
            $dace_statistics = $rout_name == 'find_product_detail' ? sfConfig::get('app_dace_statistics_find_shoes_detail') : sfConfig::get('app_dace_statistics_find_shoes');
        } else {
            $dace_statistics = $rout_name == 'find_product_detail' ? sfConfig::get('app_dace_statistics_find_goods_detail') : sfConfig::get('app_dace_statistics_find_goods');
        }
        $item['go_url'] = $item['url'];
        $item['edit_url'] = '';

        if ($hasCredential)
        {
            $item['edit_url'] = $this->getController()->genUrl('@item_all_edit?item_id='.$item['id'] . "&type=find");
        }


        //详情页地址
        $item['detail_url'] = $this->getController()->genUrl("@find_product_detail?item_id={$item['id']}").'#qk='.$dace_statistics;
        
        //大图地址
        //$item['img_url'] = str_replace(".jpg", "_300.jpg", $item["img_url"]);

        //补全title
        if(!isset($item["title"]) || !$item["title"]) {
            $item["title"] = $item["name"];
        }

        //时间
        if(!empty($item["publish_date"])) {
            $item["publish_date"] = $this->getFormatTime($item["publish_date"]);
        } else {
            $item["publish_date"] = $this->getFormatTime(strtotime($item["created_at"]));
        }
        return $item;
    }

    public function isGiveMoney($id) {
        //是否淘宝客
        $config = ConfigTaobao::getTaobaoConfig();
        $c = $this->getTopClient();
        $req = new TaobaokeItemsConvertRequest;
        $req->setFields("num_iid,click_url,commission");
        $req->setNumIids($id);
        $req->setNick($config['nick']);
        $resp = $c->execute($req);     

        if ($resp && property_exists($resp, 'total_results') && $resp->total_results) {
            $url = $resp->taobaoke_items->taobaoke_item[0]->click_url;
            if(strstr($url, "s.click") !== false) {
                return round($resp->taobaoke_items->taobaoke_item[0]->commission, 2);
            }
        } else {
            return 0;
        }

        return 0;
    }

    //商品对应的店铺是否被屏蔽
    public function is_ban_by_shop($id) {
        $is_ban = false;
        $TrdShop = TrdShopTable::getInstance();
        $info = $this->getItem($id);

        $shop_name = $info->getExternalUsername();
        $shop_id = $this->getShop($shop_name)->getExternalId();
        if($TrdShop->is_ban($shop_id)) {
            $is_ban = true;
        }

        return $is_ban;
    }
    public function is_ban_by_shop_name($shop_name) {
        $is_ban = false;
        $TrdShop = TrdShopTable::getInstance();
        $shop_id = $this->getShop($shop_name)->getExternalId();
        if($TrdShop->is_ban($shop_id)) {
            $is_ban = true;
        }

        return $is_ban;
    }
    //商品否被屏蔽
    public function is_ban_by_item($id) {
        $is_ban = false;
        $TrdItem = TrdItemTable::getInstance();
        $items = $TrdItem->getByItemId($id);

        if(!empty($items)) {
            foreach($items as $item) {
                if($item->getStatus() != TrdItemTable::STATUS_NORMAL) {
                    $is_ban = true;
                }
            }
        }

        return $is_ban;
    }

    //取得店铺的类型
    public function get_shop_type($url) {
        $type = 0;
        $domain = "www.shihuo.cn";

        if(strchr($url, "taobao")) {
            $type = TrdShopTable::TAOBAO;
        } else if(strchr($url, "tmall")) {
            $type = TrdShopTable::TMALL;
        } else {
            $parsedUrl = parse_url($url);
            if(preg_match('/(banggo\.com|ikappa\.com\.cn|vancl\.com|ihush\.com|360buy\.com|quwan\.com|51buy\.com|newegg\.com\.cn|homevv\.com|yihaodian\.com|dangdang\.com|amazon\.cn|s\.cn|suning\.com|efeihu\.com|paixie\.net|k121\.com|inshion\.com|taoxie\.com|e-lining\.com|xietoo\.com|yougou\.com|gome\.com\.cn|tao3c\.com|coo8\.com|vjia\.com|underarmour\.cn)/', $parsedUrl["host"], $matches)) {
                $type = "i_" . str_replace(".", "_", $matches[1]);
            }
            $domain = empty($matches[1])?'':$matches[1];
       }

        return array("type" => $type, "domain" => $domain);
    }

    public function get_shop_link($url) {
        $link = "";
        $parsedUrl = parse_url($url);
        if($parsedUrl) {
            $link = "http://" . $parsedUrl["host"];
        }

        return $link;
    }

    public function is_other($url) {
        /*
        $parsedUrl = parse_url($url);
        if(preg_match('/(360buy\.com|quwan\.com|51buy\.com|newegg\.com\.cn|homevv\.com|yihaodian\.com|dangdang\.com|amazon\.cn|s\.cn|suning\.com|efeihu\.com|paixie\.net|k121\.com|inshion\.com|taoxie\.com|e-lining\.com|xietoo\.com|yougou\.com|gome\.com\.cn|tao3c\.com|coo8\.com)/', $parsedUrl["host"])) {
            return true;
        }

        return false;
         */

//        if($url && strlen($url) > 5) {
//            return true;
//        }

        if(strchr($url, "taobao") || strchr($url, "tmall")) {
            return false;
        }

        return true;

    }


    public function thumb_img($file, $config) {

        $image = new Imagick($file);
        $sizes = $image->getImageGeometry();

        if($config["width"] == $config["height"]) {
            $w = $sizes["width"];
            $h = $sizes["height"];

            if ($w >= $h) {
                $limit = $h; 
                $left = ($w - $h) / 2;
                $image->cropImage($limit, $limit, $left, 0); 

            } else {
                $limit = $w; 
                $top = ($h - $w) / 2;
                $image->cropImage($limit, $limit, 0, $top);
            }
        }

        $image->thumbnailImage($config["width"], $config["height"]);
        $image->writeImages($config["target"], true);
    }

    public function getShopDetail($taobaoUsername) {
        $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
        $memcacheKey = md5('taobao_shop_info'.serialize($taobaoUsername));
        $result = unserialize($memcache->get($memcacheKey));

        if(empty($result)) {
            $result = null;
            $taobaoShop = $this->getTaobaoShop($taobaoUsername);
            if(isset($taobaoShop->shop)) {
                $result = $taobaoShop->shop;
                $memcache->set($memcacheKey,  serialize($result), 0, 60*60*24);
            }
        }

        return $result;
    }
    
    //爆料
    protected function getBaoliao($baoliaoId, $type = "shoe")
    {
      $baoliao = $this->getTaobaoItem($baoliaoId);
      if (property_exists($baoliao, 'code'))
      {
        return null;
      }      
      
      if($type == "shoe") {
          return $this->normalizeTaobaoBaoliaoInfo($baoliao);
      } else if($type == "all") {
          return $this->normalizeAllBaoliaoInfo($baoliao);
      }
    }
    
    protected function normalizeAllBaoliaoInfo($baoliao)
    {
      $baoliao = $baoliao->item;
      $itemProps = $baoliao->props_name;      
      $props = $this->normalizeTaobaoItemProps($itemProps);

      //$soldCount = $this->getItemSellCount($item->num_iid);
      $count_request = new TaobaoItemGetSoldCountRequest($baoliao->num_iid);
      $soldCount = $count_request->send();
      $itm = new TrdBaoliao();
      
      $itm->setItemId($baoliao->num_iid); 
      $itm->setName($baoliao->title);
      $itm->setPrice($baoliao->price);
      $itm->setUrl($baoliao->detail_url);
      $itm->setSoldCount($soldCount);
      $itm->setExternalUsername($baoliao->nick);


      //是否淘宝客
      /*
      if($money = $this->isGiveMoney($item->num_iid)) {
          $itm->setGiveMoney($money);
      } else {
          $itm->setGiveMoney(0);
      }
       */

      $itm->setGiveMoney(0);
      
      return $itm;
    }
    
    protected function normalizeTaobaoBaoliaoInfo($baoliao)
    {
      $baoliao = $baoliao->item;
      $itemProps = $baoliao->props_name;      
      $props = $this->normalizeTaobaoItemProps($itemProps);

      //$soldCount = $this->getItemSellCount($item->num_iid);
      $count_request = new TaobaoItemGetSoldCountRequest($baoliao->num_iid);
      $soldCount = $count_request->send();

      $itm = new TrdBaoliao();
      
      $itm->setItemId($baoliao->num_iid); 
      $itm->setName($baoliao->title);
      $itm->setPrice($baoliao->price);
      $itm->setUrl($baoliao->detail_url);
      $itm->setSoldCount($soldCount);
      $itm->setExternalUsername($baoliao->nick);

      //是否淘宝客
      /*
      if($money = $this->isGiveMoney($item->num_iid)) {
          $itm->setGiveMoney($money);
      } else {
          $itm->setGiveMoney(0);
      }
       */

      $itm->setGiveMoney(0);
      
      
      
      if (isset($props['品牌']) && count($props['品牌']))
      {
        $itm->setBrandId($this->convertTaobaoBrand(array_shift($props['品牌'])));
      }
      
      if (isset($props['尺码']) && count($props['尺码']))
      {
        $sex = null;
        
        if (isset($props['运动鞋性别']) && count($props['运动鞋性别']))
        {
          $sex = $this->convertSex(array_shift($props['运动鞋性别']));
        }
        
        if (!is_null($sex))
        {
          $itm->setSizeIds(implode(',', $this->convertTaobaoSize($props['尺码'], $sex)));          
        }
      }
      return $itm;
    }
    
     /**
   * 根据域名获取统一商店名称
   * @param string $url 出站的url
   * @return string shop 商店名称
   */
  protected function getShopNameByUrl($url){
      if (!$url) return false;
      $info = parse_url($url);
      if (preg_match('/taobao.com/', $info['host'])) return '淘宝';
      if (preg_match('/tmall.com/', $info['host'])) return '天猫';
      if (preg_match('/suning.com/', $info['host'])) return '苏宁易购';
      if (preg_match('/letao.com/', $info['host'])) return '乐淘';
      if (preg_match('/vancl.com/', $info['host'])) return '凡客诚品';
      if (preg_match('/yougou.com/', $info['host'])) return '优购';
      if (preg_match('/jd.com/', $info['host'])) return '京东';
      if (preg_match('/(vipshop.com|vip.com)/', $info['host'])) return '唯品会';
      if (preg_match('/amazon.cn/', $info['host'])) return '中国亚马逊';
      if (preg_match('/yixun.com/', $info['host'])) return '易迅';
      if (preg_match('/gome.com.cn/', $info['host'])) return '国美';
      if (preg_match('/dangdang.com/', $info['host'])) return '当当';
      if (preg_match('/(yihaodian.com|yhd.com|1mall.com)/', $info['host'])) return '一号店';
      if (preg_match('/ctrip.com/', $info['host'])) return '携程';
      if (preg_match('/newegg.com.cn/', $info['host'])) return '新蛋';
      if (preg_match('/springtour.com/', $info['host'])) return '春秋旅游';
      if (preg_match('/womai.com/', $info['host'])) return '我买';
      if (preg_match('/laiyifen.com/', $info['host'])) return '来伊份';
      if (preg_match('/dianping.com/', $info['host'])) return '大众点评';
      if (preg_match('/paixie.net/', $info['host'])) return '拍鞋';
      if (preg_match('/amazon.com/', $info['host'])) return '美国亚马逊';
      if (preg_match('/efeihu.com/', $info['host'])) return '飞虎乐购';
      if (preg_match('/k121.com/', $info['host'])) return '酷运动';
      if (preg_match('/taoxie.com/', $info['host'])) return '淘鞋';
      if (preg_match('/e-lining.com/', $info['host'])) return '李宁';
      if (preg_match('/xietoo.com/', $info['host'])) return '鞋途';
      if (preg_match('/coo8.com/', $info['host'])) return '库巴';
      if (preg_match('/yintai.com/', $info['host'])) return '银泰';
      if (preg_match('/camel.com/', $info['host'])) return '骆驼';
      if (preg_match('/zm7.cn/', $info['host'])) return '卓美';
      if (preg_match('/vjia.com/', $info['host'])) return '凡客V+';
      if (preg_match('/tonlion.com/', $info['host'])) return '唐狮';
      if (preg_match('/ihush.com/', $info['host'])) return '俏物悄语';
      if (preg_match('/banggo.com/', $info['host'])) return '邦购';
      if (preg_match('/www.shihuo.cn/', $info['host'])) return '识货';
      if (preg_match('/bbs.hupu.com/', $info['host'])) return '虎扑bbs';
      if (preg_match('/xiaomi.com/', $info['host'])) return '小米';
      if (preg_match('/yohobuy.com/', $info['host'])) return '有货';
      
      return '其他';
  }

  //根据不同的type类型生成不同的widgt
    protected function getWidgtByType($form,$type,$id,$data){
        if ($type == 1){//checkbox 
            return $form->setWidget('attribute_'.$id, new sfWidgetFormSelectCheckbox(array('choices'=> $data)));
        } else if ($type == 2){//select
            return $form->setWidget('attribute_'.$id, new sfWidgetFormChoice(array('choices'=> $data)));
        } else {//radio
            return $form->setWidget("attribute_".$id, new sfWidgetFormSelectRadio(array("choices" => $data)));
        }
    }
    
    //根据不同的type类型、是否必填生成不同的验证器
    protected function getValidatorByType($form,$type,$id,$data,$name,$flag){
        return $form->setValidator('attribute_'.$id,
                new sfValidatorChoice(
                    array('choices' => array_keys($data), 'required' => $flag),
                    array('invalid' => $name.'错误', 'required' => $name.'不能为空')
                )
            );
    }
    
  
  /**
   *
   * 识货时间处理函数 
   * @param int $time 时间戳
   */
  private function getFormatTime($time){
      $data = strtotime(date('Y-m-d',time()).' 00:00:00');
      $data1 = strtotime(date('Y',time()).'-01-01 00:00:00');
      if ($time > $data){
          return date("H:i",$time);
      } else if ($time > $data1){
          return date("m-d",$time);
      } else {
          return date("Y-m-d",$time);
      }
  }
  
  function curlGet($url){ 
        $url=str_replace('&','&',$url); 
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $url); 
        curl_setopt($curl, CURLOPT_HEADER, false); 

        //curl_setopt($curl, CURLOPT_REFERER,$url); 
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; SeaPort/1.2; Windows NT 5.1; SV1; InfoPath.2)"); 
        curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie.txt'); 
        curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt'); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0); 
        $values = curl_exec($curl); 
        curl_close($curl); 
        return $values; 
    } 
    
      // 计算中文字符串长度
 protected function utf8_strlen($str = null) {
    $count = 0;
    for($i = 0; $i < strlen($str); $i++){
        $value = ord($str[$i]);
        if($value > 127) {
            $count++;
            if($value >= 192 && $value <= 223) $i++;
            elseif($value >= 224 && $value <= 239) $i = $i + 2;
            elseif($value >= 240 && $value <= 247) $i = $i + 3;
            else die('Not a UTF-8 compatible string');
        }
        $count++;
    }
    return $count;
    }
    
public function is_crawler() {
        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $spiders = array(
            'Googlebot', // Google 爬虫
            'Baiduspider', // 百度爬虫
            'Yahoo! Slurp', // 雅虎爬虫
            'YodaoBot', // 有道爬虫
            'msnbot', // Bing爬虫
            'Sogou', // 搜狗爬虫
            'iaskspider', // 新浪爱问爬虫
            'Mediapartners', // Google AdSense广告内容匹配爬虫
            'QihooBot', // 北大天网的搜索引擎爬虫
            'Gigabot', // Gigabot搜索引擎爬虫
            'spider', // 更多爬虫关键字
        );
        foreach ($spiders as $spider) {
            $spider = strtolower($spider);
            if (strpos($userAgent, $spider) !== false) {
                return true;
            }
        }
        return false;
    }
}
