<?php

class get_new_grouponAction extends sfAction{
    public $limit = 10,
           $lifetime = 7200;  //s
    public function execute($request) {
        sfConfig::set('sf_web_debug', false);
        
        $limit = $request->getParameter('limit',$this->limit);
        if ($limit > 200) $limit = 200;

        if (!is_numeric($limit) || (int)$limit < 1)
            return $this->renderText(json_encode(array('status' => 500, 'msg' => '参数错误'))); 
        
        $key = 'trade_new_groupon_' . $limit;
        $key_cache = tradeApiMessageManager::setCacheKey($key);
        $handle = tradeApiMessageManager::getCache();
        $data = $handle->get($key);
        
        if (!$handle->get($key_cache) || !$data){
            $trdGroupTable = TrdGrouponTable::getInstance();
            $newgrouponinfo = $trdGroupTable->getActiveGoodsByTime('show_order',$limit);
            $data = array();
            if (count($newgrouponinfo)){
                $prefix_url = sfConfig::get('app_home_url');
                $go_url = sfConfig::get('app_api');
                foreach ($newgrouponinfo as $k=>$v){
                    if (preg_match("/http:\/\//",$v->getImagesFrist())){
                        $pic_url = $v->getImagesFristQiniuCdn(1,315,185);//七牛地址
                    } else {
                        $pic_name = $this->thumbGrouponImg($v->getImagesFrist());
                        $pic_url = $pic_name ? str_replace($pic_name,$pic_name.'_315',$v->getImagesFristQiniuCdn()) : '';
                    }
                    $data[$k]['title'] = $v->getTitle();
                    $data[$k]['price'] = $v->getPrice();
                    $data[$k]['original_price'] = $v->getOriginalPrice();
                    $data[$k]['discount'] = $v->getDiscount();
                    $data[$k]['attend_count'] = $v->getAttendCount();
                    $data[$k]['pic'] = $pic_url;
                    $data[$k]['go_url'] = $go_url['go']['url'].'?url='.urlencode($v->getUrl());
                    $data[$k]['detail_url'] = $prefix_url.'/tuangou/'.$v->getId();
                }
            }
            unset($newgrouponinfo);
            tradeApiMessageManager::setCacheValues($key_cache, $key, $data, $this->lifetime);            
        }
       return $this->renderText(json_encode(array( 'status' => 200 , 'msg' => 'ok', 'data' => $data)));  
    }
    
    /**
     *压缩图片 
     */
    private function thumbGrouponImg($file){
        $upload_dir = sfConfig::get('sf_upload_dir').'/trade/groupon/';
        preg_match_all('/.*\/(.*)\..*$/', $file, $matches);
        if (is_file($upload_dir.$file)){
            $name_315 = str_replace($matches[1][0],$matches[1][0].'_315',$upload_dir.$file);
            
            if (!is_file($name_315)){
                $image = new Imagick($upload_dir.$file);
                $image->thumbnailImage (315,185);
                $image->writeImages($name_315, true);
            }
        } else {
            return false;
        }
        return $matches[1][0];
    }
}