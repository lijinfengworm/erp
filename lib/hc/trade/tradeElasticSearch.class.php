<?php
/*
 *elasticsearch索引处理
 **/
Class tradeElasticSearch {
    private  $es_url;
    private  $es_redis;
    private  $_index = 'shihuo';
    public function __Construct(){
        $this->es_url    =   sfConfig::get('app_shihuo_elasticsearch_url');
        //$this->es_url    =   sfConfig::get('app_shihuo_elasticsearch_testurl');
        $this->es_redis  =   sfConfig::get('app_shihuo_elasticsearch_redis');
    }

    /*
    * 创建
    */
    public function create($array = array()){
        $_type = is_array($array['_type']) ? join(',', $array['_type']) : $array['_type'];
        $_id   = $array['_id'];
        $data  = $array['data'];
        if(!$_type || !$_id || !$data){
            return $this->_message(false, '参数不完整');
        }

        $url = $this->_joinUrl(array(
                $this->es_url,
                $this->_index,
                $_type,$_id
            ));

        //搜索
        $data_json = tradeCommon::requestUrl($url, 'POST', json_encode($data), NULL, 3);
        $data_arr  =  json_decode($data_json,true);

        //处理返回数据
        if(isset($data_arr['error']) ){
            return $this->_message(false, $data_arr['error']);
        }else{
            return $this->_message(true);
        }
    }


    /*
    * 更新
    */
    public  function update($array = array()){
        $_type = is_array($array['_type']) ? join(',', $array['_type']) : $array['_type'];
        $_id   = $array['_id'];
        $data  = $array['data'];
        if(!$_type || !$_id || !$data){
            return $this->_message(false, '参数不完整');
        }

        $url = $this->_joinUrl(array(
            $this->es_url,
            $this->_index,
            $_type,$_id
        ));

        //搜索
        $data_json = tradeCommon::requestUrl($url, 'POST', json_encode($data), NULL, 3);
        $data_arr  =  json_decode($data_json,true);

        //处理返回数据
        if(isset($data_arr['error']) ){
            return $this->_message(false, $data_arr['error']);
        }else{
            return $this->_message(true);
        }

    }


    /*
    * 删除
    */
    public  function delete($array = array()){
        $_type = is_array($array['_type']) ? join(',', $array['_type']) : $array['_type'];
        $_id   = $array['_id'];
        $data  = $array['data'];
        if(!$_type || !$_id) return $this->_message(false, '参数不完整');

        $url = $this->_joinUrl(array($this->es_url, $this->_index, $_type, $_id));

        $data_json = tradeCommon::requestUrl($url, 'DELETE', NULL, NULL, 3);
        $data_arr  =  json_decode($data_json,true);

        //处理返回数据
        if(isset($data_arr['error']) ){
            return $this->_message(false, $data_arr['error']);
        }else{
            return $this->_message(true);
        }
    }

    /*
    * 查询
    */
    public  function search($array = array()){
        $_type = is_array($array['_type']) ? join(',', $array['_type']) : $array['_type'];
        $data  = $array['data'];
        if(!$_type  || !$data) return $this->_message(false, '参数不完整');

        $url = $this->_joinUrl(array($this->es_url, $this->_index, $_type, '_search'));

        //搜索
        $data_json = tradeCommon::requestUrl($url,'POST',json_encode($data),NULL,3);

        $data_arr  =  json_decode($data_json,true);

        //处理返回数据
        if(!$data_arr || isset($data_arr['error'])){
            return $this->_message(false, $data_arr['error']);
        }else{
            return $this->_message(true, $data_arr);
        }
    }

    /*
    *count
    *
    **/
    public  function count($array = array()){
        $_type = is_array($array['_type']) ? join(',', $array['_type']) : $array['_type'];
        $data  = $array['data'];
        if(!$_type  || !$data) return $this->_message(false, '参数不完整');

        $url = $this->_joinUrl(array($this->es_url, $this->_index, $_type, '_count'));

        //搜索
        $data_json = tradeCommon::requestUrl($url,'POST',json_encode($data),NULL,3);
        $data_arr  =  json_decode($data_json,true);

        //处理返回数据
        if(!$data_arr || isset($data_arr['error'])){
            return $this->_message(false, $data_arr['error']);
        }else{
            return $this->_message(true, $data_arr);
        }
    }


    /*
    * 设置index
    */
    public  function index($array){
        $_type = $array['_type'];
        $data  = $array['data'];
        if(!$_type){
            return $this->_message(false, '参数不完整');
        }
        $url = $this->_joinUrl(array($this->es_url, $this->_index, '_settings'));

        $data_json = tradeCommon::requestUrl($url, 'POST', json_encode($data), NULL, 3);
        $data_arr  = json_decode($data_json,true);
        //处理返回数据
        if(!$data_arr || isset($data_arr['error'])){
            return $this->_message(false, $data_arr['error']);
        }else{
            return $this->_message(true, $data_arr);
        }
    }

    /*
    * 设置mapping
    */
    public  function mapping($array){
        $_type = is_array($array['_type']) ? join(',', $array['_type']) : $array['_type'];
        $data = $array['data'];
        if(!$_type  || !$data) return $this->_message(false, '参数不完整');
         $url = $this->_joinUrl(array($this->es_url, $this->_index, '_mapping', $_type));

        $data_json = tradeCommon::requestUrl($url, 'POST', json_encode($data), NULL, 3);
        $data_arr=  json_decode($data_json,true);

        //处理返回数据
        if(!$data_arr || isset($data_arr['error'])){
            return $this->_message(false, $data_arr['error']);
        }else{
            return $this->_message(true, $data_arr);
        }
    }

    /*
     * 删除mapping
     **/
    public  function deleteMapping($array){
        $_type = is_array($array['_type']) ? join(',', $array['_type']) : $array['_type'];

        if(!$_type) return $this->_message(false, '参数不完整');
        $url = $this->_joinUrl(array($this->es_url, $this->_index, $_type));

        $data_json = tradeCommon::requestUrl($url, 'DELETE', NULL ,NULL, 3);
        $data_arr  =  json_decode($data_json,true);

        //处理返回数据
        if(!$data_arr || isset($data_arr['error'])){
            return $this->_message(false, $data_arr['error']);
        }else{
            return $this->_message(true, $data_arr);
        }
    }

    /*
    *发布新词
    */
    public function  publish($message){
        if($message){
            $res = file_get_contents('http://www.shihuo.cn/api/getCustomDict?act=add&data='.$message);
            $res = json_decode($res, true);
            if($res['status']){
                return $this->_message(true,  $message.'添加成功');
            }else{
                return $this->_message(false, $message.'添加失败');
            }
        }else{
            return $this->_message(false, '不能为空');
        }
    }

    /*
    * 删除词
    */
    public function  out($message){
        if($message){
            $res = file_get_contents('http://www.shihuo.cn/api/getCustomDict?act=del&data='.$message);
            $res = json_decode($res, true);

            if($res['status']){
                return $this->_message(true,  $message.'删除成功');
            }else{
                return $this->_message(false, $message.'删除失败');
            }
        }else{
            return $this->_message(false, '不能为空');
        }

    }

    /*添加同义词*/
    public function  synonymy($message){
        // ik暂时不支持动态添加
    }

    /*删除同义词*/
    public function  synonymyOut($message){
        // ik暂时不支持动态删除
    }

    /*索引*/
    public function analyze($data){
        if($data){
            $url = $this->_joinUrl(array($this->es_url,$this->_index,'_analyze?analyzer=ik_syno'));

            $data_json = tradeCommon::requestUrl($url,'POST',$data,NULL,3);
            $data_arr  =  json_decode($data_json,true);

            if(!$data_arr || isset($data_arr['error'])){
                return $this->_message(false, $data_arr['error']);
            }else{
                return $this->_message(true, $data_arr);
            }
        }else{
            return $this->_message(false, '不能为空');
        }
    }

    /*bulk 快速索引*/
    public  function bulk($array){

        $_type = is_array($array['_type']) ? join(',', $array['_type']) : $array['_type'];
        $_data = $array['data'];
        $_action = '_bulk';
        if(!$_type || !$_data) return $this->_message(false, '参数不完整');

        $url = $this->_joinUrl(array($this->es_url, $this->_index, $_type, $_action));

        $new_data = '';
        foreach($_data as $k=>$v){
            $new_data .= json_encode($v).PHP_EOL;
        }

        //返回
        $data_json = tradeCommon::requestUrl($url, 'POST', $new_data, NULL, 3);
        $data_arr  =  json_decode($data_json,true);
        if(!$data_arr || isset($data_arr['error'])){
            return $this->_message(false, $data_arr['error']);
        }else{
            return $this->_message(true, $data_arr);
        }
    }

    /*拼接*/
    private function _joinUrl(array $urls){
        return join('/',$urls);
    }

    /*message*/
    private function _message($status, $data = ''){
        return json_encode(array(
            'status'=>$status,
            'data'=>$data
            )
        );
    }
}