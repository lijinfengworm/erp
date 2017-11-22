<?php

class getNewGrouponDataAction extends sfAction{
    public function execute($request) {
        sfConfig::set('sf_web_debug', false);
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $key = 'trade_new_groupon_data_nba_hupu_7';
        $data = $redis->get($key);
        if (!$data){
            $first_key = 'trd_nba_groupon_config_info';
            $first_info = $redis->get($first_key);
            $data = array();
            if($first_info){
                $first = unserialize($first_info);//第一帧
                $data[0]['title'] = $first['title'];
                $data[0]['price'] = $first['price'];
                $data[0]['attend_count'] = $first['attend_count'];
                $data[0]['pic'] = $first['img_path'];
                $data[0]['display'] = $first['display'];
                $data[0]['detail_url'] = $first['url'];
            }
            //篮球鞋第一款商品
            $basketball_info = $this->getGrouponInfoByCid(1);
            $run_info = $this->getGrouponInfoByCid(3);
            $casual_info = $this->getGrouponInfoByCid(2);
            $basketball_info_data = $this->_formatData($basketball_info,'http://www.shihuo.cn/tuangou/list/p-0-%E7%AF%AE%E7%90%83%E9%9E%8B');
            $run_info_data = $this->_formatData($run_info,'http://www.shihuo.cn/tuangou/list/p-0-%E8%B7%91%E9%9E%8B');
            $casual_info_data = $this->_formatData($casual_info,'http://www.shihuo.cn/tuangou/list/p-0-%E4%BC%91%E9%97%B2%E9%9E%8B');
            
            if ($basketball_info_data){
              $data = array_merge($data, $basketball_info_data); 
            }
            if ($run_info_data){
              $data = array_merge($data, $run_info_data); 
            }
            if ($casual_info_data){
              $data = array_merge($data, $casual_info_data); 
            }
            if ($data) $redis->set($key,  serialize($data),1200);
        } else {
            $data = unserialize($data);
        }
       return $this->renderText(json_encode(array( 'status' => 200 , 'msg' => 'ok', 'data' => $data)));  
    }
    
    private function _formatData($data,$url){
        if (empty($data)) return false;
        $return  = array();
        $return[0]['title'] = $data->getTitle();
        $return[0]['price'] = $data->getPrice();
        $return[0]['attend_count'] = $data->getAttendCount();
        $return[0]['pic'] = $data->getImagesFristQiniuCdn(1,315,185);//七牛地址
        $return[0]['display'] = 1;
        $return[0]['detail_url'] = $url;
        return $return;
    }
    
    private function getGrouponInfoByCid($category_id){
        if (empty($category_id))
            return false;
        $result = Doctrine_Query::create()
                ->setResultCacheLifeSpan(60 * 60 * 2)
                ->useResultCache()
                ->select('*')
                ->from('TrdGroupon t')
                ->where('t.category_id = ?', $category_id)
                ->addwhere('t.start_time < ?',  date('Y-m-d H:i:s'))->addWhere('t.end_time > ?',  date('Y-m-d H:i:s'))
                ->andWhere('t.status = ?',6)
                ->addWhere('t.deleted_at is null')
                ->orderBy('t.rank desc')
                ->limit(1)
                ->fetchOne();
        return $result;
    }
}