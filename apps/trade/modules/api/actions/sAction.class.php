<?php
/**
 * 搜索提示
 * @author: 韩晓林
 * @date: 2015/5/6  11:54
 */
Class sAction extends sfAction{
    private $es_url;
    private $es_index;
    private $_data;
    public function execute($request){
        sfConfig::set('sf_web_debug', false);

        $keywords = FunBase::clearUrl($request->getParameter('keywords'));
        $f = $request->getParameter('f');

        $this->es_url    =  sfConfig::get('app_shihuo_elasticsearch_url');
        $this->es_index  =  'suggest';

        $keywords = FunBase::escapeSpecialCharacters($keywords, true);
        $this->_search($keywords);

        return $this->renderText($this->_data);
    }

    /*
     *search
     **/
    private function _search($keywords){
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(5);
        $reidsKey   = 'trade:search2015:s:'.$keywords;
        if(!$redisRes = $redis->get($reidsKey)){
            if(!$keywords){
                $this->_data = $return['status'] = false;
                return false;
            }
            
            $url = join('/', array($this->es_url, $this->es_index, '_suggest'));

            $data = array();
            $data['shihuo-suggest'] = array();
            $data['shihuo-suggest']['text'] = $keywords;
            $data['shihuo-suggest']['completion']['field'] = "suggest_field";

            $data_json = tradeCommon::requestUrl($url,'POST',json_encode($data),NULL,3);
            $this->_data =  $this->checkData(json_decode($data_json, true));

            $redis->set($reidsKey, serialize($this->_data), 30);
        }else{
            $this->_data =  unserialize($redisRes);
        }
    }


    /*
    * 处理返回数据
    */
    private  function checkData($data){
        $return = $result = array();

        if($data || !isset($data['shihuo-suggest'][0]['options'])){
            $options = (array)$data['shihuo-suggest'][0]['options'];

            foreach($options as $options_v){
                $result[] = $options_v['text'];
            }
            $return['data']   = $result;
            $return['status'] = true;
        }else{
            $return['status'] = false;
        }

        return json_encode($return);
    }
}