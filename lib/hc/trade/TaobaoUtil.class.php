<?php
class TaobaoUtil
{
    protected $topClient;
    protected $itemsRequest;
    protected $taobaokeRequest;
    
    /**
     * 从淘宝获取商品信息 
     * @param type $itemId 商品id 可以是单个商品。也可以是多个商品的数组
     * @return type 
     */
    public function getItemInfo($itemId,$noCache = FALSE)
    {
        $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
        $memcacheKey = md5('getiteminfo'.serialize($itemId));
        $result = unserialize($memcache->get($memcacheKey));
        if(empty($result) || $noCache)
        {
            $this->init_client();
            $this->itemsRequest = new ItemsListGetRequest;
            $this->itemsRequest->setFields('approve_status,auction_point,auto_repost,cid,delist_time,ems_fee,express_fee,freight_payer,has_discount,has_invoice,has_showcase,has_warranty,increment,input_pids,input_str,is_ex,is_taobao,list_time,location,modified,nick,num,num_iid,post_fee,postage_id,price,product_id,property_alias,props,stuff_status,title,type,valid_thru,detail_url');

            if(gettype($itemId) == 'array')
            {
                $this->itemsRequest->setNumIids(implode(',', $itemId));  
            }else{
                $this->itemsRequest->setNumIids($itemId);    
            }
            $this->itemsRequest = $this->topClient->execute($this->itemsRequest);

            $result = array();

            if(gettype($itemId) == 'array' && count($itemId) > 1)
            {
                foreach ($itemId as $val)
                {
                    $result[$val] = array();
                }            
            }
            if(property_exists($this->itemsRequest, 'items'))
            {
                if(gettype($itemId) == 'array')
                {
                    if(count($itemId) > 1)
                    {
                        foreach ($this->itemsRequest->items->item as $val)
                        {
                            $result[$val->num_iid] = get_object_vars($val);
                        }
                    }else{
                        $result = get_object_vars($this->itemsRequest->items->item[0]);
                    }            
                }else{
                    $result = get_object_vars($this->itemsRequest->items->item[0]);
                }                
            }


            $left_time = sfConfig::get('app_lefttime_taobaogotocache');
            $memcache->set($memcacheKey,  serialize($result),0,$left_time);
        }

        return $result;
    }
    
    public  function getTaoBaoKeItemInfo($itemId,$noCache = FALSE)
    {
        $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
        $memcacheKey = md5('getTaoBaoKeItemInfoNew'.serialize($itemId));
        $result = unserialize($memcache->get($memcacheKey));
        if(empty($result) || $noCache)
        {
            $this->init_client();
            $this->itemsRequest = new TbkItemsDetailGetRequest;
            $this->itemsRequest->setFields('num_iid,seller_id,nick,title,price,volume,pic_url,item_url,shop_url');

            if(gettype($itemId) == 'array')
            {
                $this->itemsRequest->setNumIids(implode(',', $itemId));  
            }else{
                $this->itemsRequest->setNumIids($itemId);    
            }
            $this->itemsRequest = $this->topClient->execute($this->itemsRequest);
            
            
            $result = array();

            if(gettype($itemId) == 'array' && count($itemId) > 1)
            {
                foreach ($itemId as $val)
                {
                    $result[$val] = array();
                }            
            }
            if(property_exists($this->itemsRequest, 'tbk_items'))
            {
                if(gettype($itemId) == 'array')
                {
                    if(count($itemId) > 1)
                    {
                        foreach ($this->itemsRequest->tbk_items->tbk_item as $val)
                        {
                            $result[$val->item->num_iid] = get_object_vars($val);
                        }
                    }else{
                        $result = get_object_vars($this->itemsRequest->tbk_items->tbk_item[0]);
                    }            
                }else{
                    $result = get_object_vars($this->itemsRequest->tbk_items->tbk_item[0]);
                }                
            }   
            $memcache->set($memcacheKey,  serialize($result),0,60*60*24);
        }

        return $result;
    }    
    /**
     * 从淘宝获取商品的淘宝客信息
     * @param type $itemId 商品id 可以是单个商品。也可以是多个商品的数组
     * @param type $outerCode 推广来源
     * @return type 
     */
    public function gettaobaokeinfo($itemId,$outerCode = 'shihuo')
    {   
        $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
        $memcacheKey = md5('gettaobaokeinfo'.serialize($itemId).$outerCode);
        $result = unserialize($memcache->get($memcacheKey));

        if(empty($result))
        {
            $this->init_client();
            $this->taobaokeRequest = new TaobaokeItemsConvertRequest;
            $this->taobaokeRequest->setFields("num_iid,title,nick,pic_url,price,click_url,commission,commission_rate,commission_num,commission_volume,shop_click_url,seller_credit_score,item_location");
            $this->taobaokeRequest->setNick($this->config['nick']);
            $this->taobaokeRequest->setOuterCode($outerCode);
            
            if(gettype($itemId) == 'array')
            {
                $this->taobaokeRequest->setNumIids(implode(',', $itemId));
            }else{
                $this->taobaokeRequest->setNumIids($itemId);
            }

            $this->taobaokeRequest = $this->topClient->execute($this->taobaokeRequest);
            
            $result = array();

            if(gettype($itemId) == 'array' && count($itemId) > 1)
            {
                foreach ($itemId as $val)
                {
                    $result[$val] = array();
                }            
            }        
            if($this->taobaokeRequest->total_results > 0)
            {
                if(gettype($itemId) == 'array')
                {
                    if(count($itemId) > 1)
                    {
                        foreach ($this->taobaokeRequest->taobaoke_items->taobaoke_item as $val)
                        {
                            $result[$val->num_iid] = get_object_vars($val);
                        }
                    }else{
                        $result = get_object_vars($this->taobaokeRequest->taobaoke_items->taobaoke_item[0]);
                    }            
                }else{
                    $result = get_object_vars($this->taobaokeRequest->taobaoke_items->taobaoke_item[0]);
                }
            }

            $left_time = sfConfig::get('app_lefttime_taobaogotocache');
            $memcache->set($memcacheKey, serialize($result),0,$left_time);
        }
        return $result;
    }
    public function getSpmeffect($date,$page = 'false',$module = 'false'){
        $this->init_client();
        $this->taobaokeRequest = new SpmeffectGetRequest;
        $this->taobaokeRequest->setDate($date);
        $this->taobaokeRequest->setPageDetail($page);
        $this->taobaokeRequest->setModuleDetail($module);
        $resp = $this->topClient->execute($this->taobaokeRequest);
        return $resp;
    }
    public function addIncrementSubscription(array $itemIds){
        $this->init_client();
        $this->taobaokeRequest = new IncrementSubscriptionAddRequest();
        $this->taobaokeRequest->setTopic("item");
        $this->taobaokeRequest->setSubscribeKey("num_iid");
        $this->taobaokeRequest->setSubscribeValues(implode(',', $itemIds));
        //$this->taobaokeRequest->setTrackIids("123_track_456,1223_track_451");
        $resp = $this->topClient->execute($this->taobaokeRequest);
        return $resp;
    }
    public function deleteIncrementSubscription(array $itemIds){
        $this->init_client();
        $this->taobaokeRequest = new IncrementSubscriptionDeleteRequest();
        $this->taobaokeRequest->setTopic("item");
        $this->taobaokeRequest->setSubscribeKey("num_iid");
        $this->taobaokeRequest->setSubscribeValues(implode(',', $itemIds));
        $resp = $this->topClient->execute($this->taobaokeRequest);
        return $resp;
    }

    protected function init_client()
    {
        if(empty($this->topClient))
        {
            $this->topClient = new TaoBaoTopClient();
        }
    }
    static public function parseItemId($url)
    {
        $parsedUrl = parse_url($url);
        
        // Malformated URL, try to redirect it	
        if (!$parsedUrl || !isset($parsedUrl['scheme']) || !isset($parsedUrl['host']))
        {
            return 0;
        }  

        if ($parsedUrl['host'] == 'item.taobao.com'
            || ($parsedUrl['host'] == 'detail.tmall.com' && strstr($parsedUrl['path'], '/item.htm') != false))
        {
            
            $queryString = $parsedUrl['query'];

            parse_str(htmlspecialchars_decode($queryString), $queryStringArray);

            if (!isset($queryStringArray['id']) || !is_numeric(trim($queryStringArray['id'])))
            {
                return 0;
            }        
            
            return $queryStringArray['id'];
        }

        return 0;
    }
    /**
     * 通过 一个url 获取 对应的商铺id
     * @param type $url
     * @return boolean 
     */
    static function getShopIdByUrl($url)
    {
        $headerInfo = get_headers($url);     
        preg_match('/at_shoptype:\s\d_(\d+)/', implode(' ', $headerInfo),$matches);
        if(!empty($matches[1]))
        {
            return $matches[1];
        }else{
            return false;
        }
               
    }
    
    static function getShopNickName($url)
    {
       $urlInfo = self::curlGet($url);
       preg_match("/nickName:\s?'(.*?)'\s?,/", $urlInfo,$data);
       if(empty($data[1]))
       {
           return false;
       }else{
           return urldecode($data[1]);
       }
       
    }
    
    static function curlGet($url){ 
        $url=str_replace('&','&',$url); 
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $url); 
        curl_setopt($curl, CURLOPT_HEADER, false); 
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; SeaPort/1.2; Windows NT 5.1; SV1; InfoPath.2)"); 
        curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie.txt'); 
        curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt'); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0); 
        $values = curl_exec($curl); 
        curl_close($curl); 
        return $values; 
    }
    /**
     * 通过淘宝 地址 获取商品基本信息
     * @param type $url
     */
    public function getItemIdByUrl($url)
    {
        $parsedUrl = parse_url($url);
        $queryString = $parsedUrl['query'];
        parse_str($queryString, $queryStringArray);
        if (!isset($queryStringArray['id']) || !is_numeric($queryStringArray['id'])) {
            return false;
        }
        return $queryStringArray['id'];
    }
    
    public function getTaobaoShop($sellerNick)
    {
      $this->init_client();
      $req = new ShopGetRequest;
      $req->setFields("sid,cid,title,nick,shop_score");
      $req->setNick($sellerNick);
      $resp = $this->topClient->execute($req);
      return $resp;
    }
}
