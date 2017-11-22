<?php
/*
 *elasticsearch索引处理E:\kaluli_aliyun\vendor\videlalvaro\php-amqplib\PhpAmqpLib\Connection\AMQPStreamConnection.php
 **/
Class kaluliElasticSearch {
    private  $es_url = '';
    private  $_index = 'kaluli';
    public function __Construct(){
        $this->es_url  =   sfConfig::get('app_kaluli_elasticsearch_url');
    }

    /*创建*/
    public function create($array = array()){
        $_type = is_array($array['_type']) ? join(',',$array['_type']) : $array['_type'];
        $_id = $array['_id'];
        $data = $array['data'];
        if(!$_type || !$_id || !$data) return '参数不完整';

        $url = $this->_joinUrl(array($this->es_url,$this->_index,$_type,$_id)).'?pretty';

        //搜索
        $data_json = KaluliFun::requestUrl($url,'POST',json_encode($data),NULL,3);
        $data_arr=  json_decode($data_json,true);

        //处理返回数据
        $return = array();
        if(isset($data_arr['error']) ){
            return json_encode(array('status'=>'error','msg'=>$data_arr['error']));
        }else{
            return json_encode(array('status'=>'success'));
        }
    }


    /*更新*/
    public  function update($array = array()){
        $_type = is_array($array['_type']) ? join(',',$array['_type']) : $array['_type'];
        $_id = $array['_id'];
        $data = $array['data'];
        if(!$_type || !$_id || !$data) return '参数不完整';

        $url = $this->_joinUrl(array($this->es_url,$this->_index,$_type,$_id)).'?pretty';

        //搜索
        $data_json = KaluliFun::requestUrl($url,'POST',json_encode($data),NULL,3);
        $data_arr=  json_decode($data_json,true);

        //处理返回数据
        $return = array();
        if(isset($data_arr['error']) ){
            return json_encode(array('status'=>'error','msg'=>$data_arr['error']));
        }else{
            return json_encode(array('status'=>'success'));
        }

    }


    /*删除*/
    public  function delete($array = array()){
        $_type = is_array($array['_type']) ? join(',',$array['_type']) : $array['_type'];
        $_id = $array['_id'];
        $data = $array['data'];
        if(!$_type || !$_id) return '参数不完整';

        $url = $this->_joinUrl(array($this->es_url,$this->_index,$_type,$_id)).'?pretty';

        $data_json = KaluliFun::requestUrl($url,'DELETE',NULL,NULL,3);
        $data_arr=  json_decode($data_json,true);
        //处理返回数据
        $return = array();
        if(isset($data_arr['error']) ){
            echo json_encode(array('status'=>'error','msg'=>$data_arr['error']));
        }else{
            echo json_encode(array('status'=>'success'));
        }
    }

    /*查询*/
    public  function search($array = array()){
        $_type = is_array($array['_type']) ? join(',',$array['_type']) : $array['_type'];
        $data = $array['data'];
        if(!$_type  || !$data) return '参数不完整';

        $url = $this->_joinUrl(array($this->es_url,$this->_index,$_type,'_search'));
         //return   $data;
        //搜索
      
        $data_json = KaluliFun::requestUrl($url,'POST',json_encode($data),NULL,3);
        $data_arr=  json_decode($data_json,true);

        //处理返回数据
        $return = array();
        if(!$data_arr || isset($data_arr['error'])){
            $return['status'] = false;
            $return['error'] = $data_arr['error'];
        }else{
            $return['status'] = true;
            $return['data'] = $data_arr;
        }

        return json_encode($return);
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

    /*设置index*/
    public  function index($array){
        $_type = is_array($array['_type']) ? join(','   ,$array['_type']) : $array['_type'];
        $data = $array['data'];
        if(!$_type) return '参数不完整';
         $url = $this->_joinUrl(array($this->es_url,$this->_index,'_settings'));

        $data_json = KaluliFun::requestUrl($url,'POST',json_encode($data),NULL,3);
        $data_arr=  json_decode($data_json,true);

        //处理返回数据
        $return = array();
        if(isset($data_arr['error'])){
            $return['status'] = 'error';
            $return['msg'] = $data_arr['error'];
        }else{
            $return['status'] = 'success';
            $return['msg'] = $data_arr;
        }

        return json_encode($return);
    }

    /*设置mapping*/
    public  function mapping($array){
        $_type = is_array($array['_type']) ? join(',',$array['_type']) : $array['_type'];
        $data = $array['data'];
        if(!$_type  || !$data) return '参数不完整';
         $url = $this->_joinUrl(array($this->es_url,$this->_index,'_mapping',$_type));

        $data_json = KaluliFun::requestUrl($url,'POST',json_encode($data),NULL,3);
        $data_arr=  json_decode($data_json,true);

        //处理返回数据
        $return = array();
        if(isset($data_arr['error'])){
            $return['status'] = 'error';
            $return['msg'] = $data_arr['error'];
        }else{
            $return['status'] = 'success';
            $return['msg'] = $data_arr;
        }

        return json_encode($return);
    }

    /*删除mapping*/
    public  function deleteMapping($array){
        $_type = is_array($array['_type']) ? join(',',$array['_type']) : $array['_type'];

        if(!$_type) return '参数不完整';
        $url = $this->_joinUrl(array($this->es_url,$this->_index,$_type));

        $data_json = KaluliFun::requestUrl($url,'DELETE',NULL,NULL,3);
        $data_arr=  json_decode($data_json,true);

        //处理返回数据
        $return = array();
        if(isset($data_arr['error'])){
            $return['status'] = 'error';
            $return['msg'] = $data_arr['error'];
        }else{
            $return['status'] = 'success';
            $return['msg'] = $data_arr;
        }

        return json_encode($return);
    }

    /*发布新词*/
    public function  publish($message){
        $host = '192.168.1.197';
        $port = '6770';
        if(sfConfig::get('sf_environment') == 'dev')
        {
            $host = '192.168.8.247';
            $port = '6379';
        }

        if($message){
            $redis =  new redis();
            $redis->connect($host,$port);

            if(is_array($message)){
                foreach($message as $k=>$v){
                   $data = 'u:c:'.$v;
                   echo $redis->publish('ansj_term',$data);
                }
            }else{
                $data = 'u:c:'.$message;
                echo $redis->publish('ansj_term',$data);
            }
        }
    }

    /*索引*/
    public function analyze($data){
        if(!$data) return false;

        $url = $this->_joinUrl(array($this->es_url,$this->_index,'_analyze?analyzer=ansj_query'));

        $data_json = KaluliFun::requestUrl($url,'POST',$data,NULL,3);
        $data_arr =  json_decode($data_json,true);
        return $data_arr;
    }

    /*bulk 快速索引*/
    public  function bulk($array){
        $_type = is_array($array['_type']) ? join(',',$array['_type']) : $array['_type'];
        $_data = $array['data'];
        $_action = '_bulk';
        if(!$_type || !$_data) return $this->_message(false,'参数不完整');

        $url = $this->_joinUrl(array($this->es_url,$this->_index,$_type,$_action));

        $new_data = '';
        foreach($_data as $k=>$v){
            $new_data .= json_encode($v).PHP_EOL;
        }

        $res_json = KaluliFun::requestUrl($url,'POST',$new_data,NULL,3);
        $res_arr =  json_decode($res_json,true);
        return $res_arr;
    }

    /*拼接*/
    private function _joinUrl(array $urls){
        return join('/',$urls);
    }

    /*message*/
    private function _message($status,$messgae){
        return array('status'=>$status,'msg'=>$messgae);
    }
}