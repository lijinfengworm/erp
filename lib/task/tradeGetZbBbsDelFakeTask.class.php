<?php

class tradeGetZbBbsDelFakeTask extends sfBaseTask
{
  const TAOBAO_ITEM_HOST = 'item.taobao.com';
  const TMALL_ITEM_HOST = 'detail.tmall.com';
    
  private $parsedUrl = null;
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
    $this->name             = 'GetZbBbsDelFake';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:GetZbBbsDelFake|INFO] task does things.
Call it with:

  [php symfony trade:GetZbBbsDelFake|INFO]
EOF;
  }

  //获取社区装备区的go链接 并验证店铺id是否被封禁 并删除相应的帖子
  protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);    
    
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        
        // 接口名称
        $apiname = 'getthreadgear';
        // 应用ID
        $appid = '118';
        // 应用KEY
        $key = '62c7c5ccd161d52';
        $time = time();
        $sign = md5(md5($appid) . $time . $key);
        
        $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
        $memcacheKey = md5('get_golink_interface');
        $result = $memcache->get($memcacheKey);
        if (empty($result)){
             // 接口请求地址拼接
            $apiurl = 'http://interface.hoopchina.com/' . $apiname . '?appid=' . $appid . '&time=' . $time . '&sign=' . $sign;
            // 返回接口输出结果
            $result = file_get_contents($apiurl);
            $memcache->set($memcacheKey,$result, 0, 60);
        } 

        $array = json_decode($result, true);
        
        if (!empty($array)){
            $tids = '';
            foreach ($array as $kk=>$vv){
                foreach($vv as $k=>$v){
                    $v = htmlspecialchars_decode($v);
                    $this->parsedUrl = parse_url($v);
                    // Malformated URL, try to redirect it	
                    if (!$this->parsedUrl || !isset($this->parsedUrl['scheme']) || !isset($this->parsedUrl['host'])) {
                        continue;
                    }
                    
                    if ($this->parsedUrl['host'] == self::TAOBAO_ITEM_HOST || ($this->parsedUrl['host'] == self::TMALL_ITEM_HOST && strstr($this->parsedUrl['path'], '/item.htm') != false)){
                         $this->itemId = $this->parseTaobaoItemId();
                         $taobaoUtil = new TaobaoUtil();
                         !empty($this->itemId) && $shop_info = $taobaoUtil->getItemInfo($this->itemId,TRUE);
                         !empty($shop_info['nick']) && $shop = $taobaoUtil->getTaobaoShop($shop_info['nick']);
                         if (!empty($shop) && TrdShopTable::getInstance()->is_ban($shop->shop->sid)){ 
                             $tids .= $kk.',';
                             break;
                         }
                    }

                    //查看是否是封禁店铺直接旺旺聊天的链接
                    if (preg_match('/www\.taobao\.com\/webww/',$v)){
                        $queryString = $this->parsedUrl['query'];
                        parse_str($queryString, $queryStringArray);
                        $nick_name = ltrim($queryStringArray['touid'],'cntaobao');
                        $taobaoUtil = new TaobaoUtil();
                        $nick_name && $shop = $taobaoUtil->getTaobaoShop($nick_name);
                        if (!empty($shop) && TrdShopTable::getInstance()->is_ban($shop->shop->sid)){ 
                            $tids .= $kk.',';
                            break;
                        }
                    }

                    //普通shop 链接 必须以 .com 或者 是 .com/ 结尾
                    if (preg_match('/^http:\/\/shop(\d+)\.taobao\.com$/', $v, $matches) || preg_match('/^http:\/\/shop(\d+)\.taobao\.com\/$/', $v, $matches)) {
                        //$this->shopId = $matches[1];
                        $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
                        $memcacheKey = md5('get_nick_' . $v);
                        $this->nickName = $memcache->get($memcacheKey);
                        if ($this->nickName === FALSE ) {
                            $this->nickName = TaobaoUtil::getShopNickName($v);
                            if (empty($this->nickName)) {
                                $memcache->set($memcacheKey, 0, 0, 86400 * 5);
                            } else {
                                $memcache->set($memcacheKey, $this->nickName, 0, 86400 * 5);
                            }
                        }
                        $taobaoUtil = new TaobaoUtil();
                        $this->nickName && $shop = $taobaoUtil->getTaobaoShop($this->nickName);
                        if (!empty($shop) && TrdShopTable::getInstance()->is_ban($shop->shop->sid)){ 
                            $tids .= $kk.',';
                            break;
                        }
                    }
                    
                    //个性化域名的shop店链接 链接 必须以 .com 或者 是 .com/ 结尾  通过请求地址的header 头部获取对应 shopid
                    if (preg_match('/^http:\/\/.+\.taobao\.com$/', $v) || preg_match('/^http:\/\/.+\.taobao\.com\/$/', $v)) {
                        $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
                        $memcacheKey = md5('get_nick_' . $v);
                        $this->nickName = $memcache->get($memcacheKey);

                        if ($this->nickName === FALSE) {
                            $this->nickName = TaobaoUtil::getShopNickName($v);
                            if (empty($this->nickName)) {
                                $memcache->set($memcacheKey, 0, 0, 86400 * 5);
                            } else {
                                $memcache->set($memcacheKey, $this->nickName, 0, 86400 * 5);
                            }
                        }
                        $taobaoUtil = new TaobaoUtil();
                        $this->nickName && $shop = $taobaoUtil->getTaobaoShop($this->nickName);
                        if (!empty($shop) && TrdShopTable::getInstance()->is_ban($shop->shop->sid)){ 
                            $tids .= $kk.',';
                            break;
                        }
                    }       
                    
                }   
            }
            !empty($tids) && $this->deteleBbsById(rtrim($tids,','));
        }
        exit;
    }
    
    private function deteleBbsById($tids){
        // 接口名称
        $apiname = 'getthreadgear';

        // 应用ID
        $appid = '118';
        // 应用KEY
        $key = '62c7c5ccd161d52';
        $time = time();
        $sign = md5(md5($appid) . $time . $key);

        //接口方法名
        $a = 'delthread';

        //帖子tid (上限为1000个，超出部分则不处理)
        //$tids = '222222,22222,11111';  

        //删除帖子token
        $token = md5(md5($time + $appid) . $tids . $appid);

        // 接口请求地址拼接
        $apiurl = 'http://interface.hoopchina.com/' . $apiname . '?appid=' . $appid . '&time=' . $time . '&sign=' . $sign . '&a=' . $a . '&tids=' . $tids . '&token=' . $token;
        
        // 返回接口输出结果
        $result = file_get_contents($apiurl);
        // 打印结果
        echo $result;
    }
    
    /**
     * 获取url 中的 淘宝商品id url 中没有淘宝商品id 则跳转到 url 
     * @return type 
     */
    private function parseTaobaoItemId() {
        $queryString = $this->parsedUrl['query'];
        parse_str($queryString, $queryStringArray);

        if (!isset($queryStringArray['id']) || !is_numeric($queryStringArray['id'])) {
            return false;
        }
        return $queryStringArray['id'];
    }

}
