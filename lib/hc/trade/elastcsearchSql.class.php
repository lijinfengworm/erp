<?Php
/*
*@date 2015/12/28
*@author 韩晓林
*es sql

elastcsearchSql::getInstance()
     ->createQuery('news')
     ->select('id,title')
     ->where('id', '>=', '10000')
     ->andWhere('rootId', '=', 1)
     ->limit(5)
     ->execute();
**/
Class elastcsearchSql{
    protected $_type;
    protected $_operate = array(
        '=','!=','>','<','>=','<=','in','like'
    );

    protected $_source = array();
    protected $_must_condition = array();
    protected $_must_not_condition = array();
    protected $_must_should_condition = array();
    protected $_limit  = 100;
    protected $_offset = 0;
    protected $_es_link;

    public static function getInstance(){
        return new elastcsearchSql();
    }

    /*
   * 查找
   */
    public function execute(){
        $search = $this->bindParam('search'); //绑定参数

        $res = $this->_es_link->search($search);
        $res = json_decode($res, true);

        if(!$res['status']){
            throw new sfException('Fatal :'.$res['error']);
        }

        return $this->check($res);
    }

    /*
    * 查找单个
    */
    public function fetchOne(){
        $return =  $this->execute();
        if(count($return) > 0){
            return array_shift($return);
        }

        return $return;
    }

    /*
    * 计数
    **/
    public function count(){
        $search = $this->bindParam('count'); //绑定参数

        $res = $this->_es_link->count($search);
        $res = json_decode($res, true);

        if(!$res['status']){
            throw new sfException('fatal :'.$res['error']);
        }

        return isset($res['data']['count']) ? $res['data']['count'] : 0;
    }


    public  function createQuery($type, $link = 'shihuo'){
        $this->_type    = $type;
        if(!$this->_type) throw new sfException('Type Not Found');

        if('shihuo' == $link){
            $this->_es_link = new tradeElasticSearch();
        }elseif('kaluli' == $link){
            $this->_es_link = new kaluliElasticSearch();
        }else{
            throw new sfException('Link Not Found');
        }

        return $this;
    }

    public function select($source = '*'){
        if('*' !== $source){
            $this->_source = explode(',', $source);
        }

        return $this;
    }

    /*
     * @$param    参数
     * @$operate  操作
     * @$val      值
     **/
    public function where(){
        $sources = func_get_args();

        if(func_num_args() < 2)
            throw new sfException('Missing Parameter');
        elseif(2 == func_num_args()){
            $param   = $sources[0];
            $operate = '=';
            $val     = $sources[1];
        }else{
            $param   = $sources[0];
            $operate = $sources[1];
            $val     = $sources[2];
        }

        if(!in_array($operate, $this->_operate))
            throw new sfException('Operate Not Found');

        $condition = array();
        switch($operate){
            case '='  :
            case 'in' :
                $val = !is_array($val) ? array($val) : $val;
                $condition = array(
                   'terms' => array(
                       $param => $val
                   )
                );

                $this->_must_condition[] = $condition;
                break;

            case '>' :
            case '<' :
            case '>=' :
            case '<=' :
                $condition['range'][$param] = array();
                if('>' == $operate || ('>=' == $operate)){
                    $condition['range'][$param]['from'] = $val;
                    if('>' == $operate){
                        $condition['range'][$param]['include_lower'] = false;
                    }
                } else{
                    $condition['range'][$param]['to']  = $val;
                    if('<' == $operate){
                        $condition['range'][$param]['include_upper'] = false;
                    }
                }

                $this->_must_condition[] = $condition;
                break;

            case 'like' :
                $condition = array(
                    'match' => array(
                        $param => array(
                            'query'    => $val,
                            'operator' => 'and'
                        )
                    )
                );

                $this->_must_condition[] = $condition;
                break;

            case '!=':
                $val = !is_array($val) ? array($val) : $val;
                $condition = array(
                    'terms' => array(
                        $param => $val
                    )
                );

                $this->_must_not_condition[] = $condition;
                break;

            default:
                throw new sfException('Operate Not Found');
        }

        return $this;
    }

    public function andWhere($param , $operate, $val){
        return $this->where($param , $operate, $val);
    }


    public function limit($limit){
        if(!is_int($limit))
            throw new sfException($limit .'Not Int');

        $this->_limit = $limit;

        return $this;
    }

    public function offset($offset){
        if(!is_int($offset))
            throw new sfException($offset .'Not Int');

        $this->_offset = $offset;

        return $this;
    }

    //绑定参数
    private function bindParam($curd){
        $query = array();
        $query['query']['bool']['must'] = $this->_must_condition;
        $query['query']['bool']['must_not'] = $this->_must_not_condition;

        if('search' == $curd){//查询
            if($this->_source)
                $query['_source'] = $this->_source;

            $query['from'] = $this->_offset;
            $query['size'] = $this->_limit;
        }

        return  array(
            'data'  => $query,
            '_type' => $this->_type
        );
    }

    //处理数据
    protected function check($res){
        $res = isset($res['data']['hits']) ? $res['data']['hits'] : array();

        $return = array();
        if(!empty($res['hits']) && is_array($res['hits'])){
            foreach($res['hits'] as $k=>$v){
                $return[$k] =  $v['_source'];
            }
        }
        
        return $return;
    }
}