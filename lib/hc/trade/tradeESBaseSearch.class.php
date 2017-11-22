<?php
/**
 * ES 搜搜基类
 * @author: 韩晓林
 * @date: 2015/9/16  18:15
 */
abstract class tradeESBaseSearch{
    protected  $_type;
    protected  $link;
    protected  $redis;

    //索引创建
    public function create($id){
        return $this->update($id);
    }

    //索引更新
    public function update($id){
        if(!$id || !is_numeric($id)){
            return $this->_return(false, '参数错误');
        }

        if($data = $this->_updateData($id)){
            $array = array(
                '_type'=> $this->_type,
                '_id'  => $id,
                'data' => $data[0]
            );
           // FunBase::myDebug($array);
            $es = new tradeElasticSearch();
            return $es->update($array);
        }else{
            return $this->delete($id);
        }
    }


    //索引删除
    public  function delete($id){
        if(!$id || !is_numeric($id)){
            return $this->_return(false, '参数错误');
        }

        $data = array();
        $array = array(
            '_type'=> $this->_type,
            '_id'  => $id,
            'data' => $data
        );

        $es =new tradeElasticSearch();
        return $es->delete($array);
    }


    //设置mapping
    public  function mapping(){
        $mappingData = $this->_mappingData();

        $array = array(
            '_type'=>  $this->_type,
            'data' => $mappingData
        );
        $es = new tradeElasticSearch();
        return $es->mapping( $array );
    }

    //删除mapping
    public function deleteMapping(){
        $array = array(
            '_type' => $this->_type,
        );

        $es = new tradeElasticSearch();
        return $es->deleteMapping( $array );
    }

    //设置索引[暂缺]
    public  function  index(){
        $data = array();
        $array = array(
            '_type' => $this->_type,
            'data'  => $data
        );

        $es  = new tradeElasticSearch();
        return $es->index($array);
    }

    //代购重建索引
    public function reindex($id = 1){
        set_time_limit(0);
        $id = is_numeric($id) ? $id : 1;

        echo str_repeat(" ",1024);       //达到输出限制
        for($i = $id; $i >= 1 ; $i--){
            echo $i.PHP_EOL;
            echo $this->update($i);

            ob_flush();
            flush();
            usleep(50);
        }
    }

    //索引数据
    abstract function _updateData($id);

    //mapping数据
    abstract function _mappingData();

    //返回处理
    final  function _return($status, $data = ''){
        return array(
            'status'=> $status,
            'data'  => $data
        );
    }

    //获取数据库连接
    protected  function getLink(){
        if(!$this->link){
            $this->link = Doctrine_Manager::getInstance()->getConnection('trade');
        }
        return $this->link;
    }

    //获取redis连接
    protected  function getRedis(){
        if(!$this->redis){
            $this->redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
            $this->redis->select(5);
        }
        return $this->redis;
    }

    //销毁
    public function __desctruct(){
        if($this->redis) $this->redis->close();
        if($this->link)  $this->link->close();
    }
}