<?php
class get_youhui_newsAction extends sfAction{
    public $limit = 10,
           $lifetime = 600;  //s
    public function execute($request) {
        sfConfig::set('sf_web_debug', false);
        
        $limit = $request->getParameter('limit',$this->limit);
        $rootid = $request->getParameter('rootid',0);
        $childrenid = $request->getParameter('childrenid',0);
        if ($limit > 200) $limit = 200;

        if (!is_numeric($limit) || (int)$limit < 1)
            return $this->renderText(json_encode(array('status' => 500, 'msg' => '参数错误'))); 
        if (!is_numeric($rootid))
            return $this->renderText(json_encode(array('status' => 500, 'msg' => '参数错误'))); 
        if (!is_numeric($childrenid))
            return $this->renderText(json_encode(array('status' => 500, 'msg' => '参数错误'))); 
        
        $key = 'trade_youhui_news_'.$rootid .'_'.$childrenid.'_'.$limit;
        $key_cache = tradeApiMessageManager::setCacheKey($key);
        $handle = tradeApiMessageManager::getCache();
        $data = $handle->get($key);
        
        if (!$handle->get($key_cache) || !$data){
            $youhuinews = trdNewsTable::getNewShiHuoInfo($limit,$rootid,$childrenid);
            $data = array();
            $prefix_url = sfConfig::get('app_home_url');
            $go_url = sfConfig::get('app_api');
            if (count($youhuinews)){
                foreach ($youhuinews as $k=>$v){
                    $data[$k]['title'] = $v->getTitle();
                    $data[$k]['subtitle'] = $v->getSubTitle();
                    $data[$k]['intro'] = $v->getIntro();
                    $data[$k]['img_path'] = $v->getImgPath();
                    $data[$k]['go_url'] = $go_url['go']['url'].'?url='.urlencode($v->getOrginalUrl());
                    $data[$k]['detail_url'] = $prefix_url.'/youhui/'.$v->getId().'.html';
                    $data[$k]['time'] = $v->getPublishDate();
                }
            }
            unset($youhuinews);
            tradeApiMessageManager::setCacheValues($key_cache, $key, $data, $this->lifetime);                    
        }
        
       return $this->renderText(json_encode(array( 'status' => 200 , 'msg' => 'ok', 'data' => $data)));  
}
}