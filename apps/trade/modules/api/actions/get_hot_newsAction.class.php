<?php

class get_hot_newsAction extends sfAction{
    public $limit = 10,
           $lifetime = 180;  //s
    public function execute($request) {
        sfConfig::set('sf_web_debug', false);
        
        $type = $request->getParameter('type',1);
        $limit = $request->getParameter('limit',$this->limit);
        if ($limit > 200) $limit = 200;

        if ($type === null)
            return $this->renderText(json_encode(array('status' => 500, 'msg' => '参数错误')));

        if (!is_numeric($limit) || (int)$limit < 1)
            return $this->renderText(json_encode(array('status' => 500, 'msg' => '参数错误'))); 
        
        $key = 'trade_hot_news_' . $type . '_'. $limit;
        $key_cache = tradeApiMessageManager::setCacheKey($key);
        $handle = tradeApiMessageManager::getCache();
        $data = $handle->get($key);
        
        if (!$handle->get($key_cache) || !$data){
            $rankingListsBy24h = trdNewsTable::getShiHuoInfoRankingList($type,$limit);
            $data = array();
            if (count($rankingListsBy24h)){
                sfProjectConfiguration::getActive()->loadHelpers(array('Url'));
                foreach ($rankingListsBy24h as $v){
                    $data[] = $this->formatInfo($v);
                }
            }
            unset($rankingListsBy24h);
            tradeApiMessageManager::setCacheValues($key_cache, $key, $data, $this->lifetime);            
        }
        
       return $this->renderText(json_encode(array( 'status' => 200 , 'msg' => 'ok', 'data' => $data)));  
    }
    
    private function formatInfo($record){
        $tmp = array();
        $tmp['title'] = $record->getTitle();
        $tmp['subtitle'] = $record->getSubTitle();
        $tmp['url'] = 'http://' . sfContext::getInstance()->getRequest()->getHost() . url_for('@shihuo_news_detail?id='.$record->getId());
        return $tmp;
    }
}