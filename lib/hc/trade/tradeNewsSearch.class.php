<?php
Class newsSearch extends tradeESBaseSearch{
    public   $_type = 'news';
    public   $_channel_type;
    public function  preSerach(
        $page = 1, $pagesize = 30, $type = 0, $root_id = 0,  $children_id = 0,
        $store_id = 0, $root_type = array(),  $shopping = 0, $w = 0,
        $date = 0, $hotSort = '', $dateSort = 'desc'
    ){
        $param = array();
        if( $page )        $param['pageNo']       =     $page;
        if( $pagesize )    $param['pageSize']     =     $pagesize;
        if( $type )        $param['type']         =     $type;
        if( $root_id)      $param['root_id']      =     $root_id;
        if( $children_id ) $param['children_id']  =     $children_id;
        if( $store_id )    $param['store_id']     =     $store_id;
        if( $root_type )   $param['root_type']    =     $root_type;
        if( $shopping )    $param['shopping']     =     $shopping;
        if( $w )           $param['keywords']     =     $w;
        if( $date )        $param['current_date'] =     $date;
        if( $hotSort )
            $param['hotSort']      =     $hotSort;
        else{
            $param['dateSort']     =     $dateSort;
        }

        $res = $this->search($param);
        return $res;
    }

    /*信息搜索*/
    public function search($search_data){
        if(isset($search_data['current_date'])) {//时间过滤点
            $current_date = $search_data['current_date'];
        }else{
            $current_date = date('Y-m-d H:i:s');
        }

        $array = $data = array();

        /*分页*/
        if(isset($search_data['pageSize']))
            $data['size'] = (int)$search_data['pageSize'];
        else
            $data['size'] = 30;
        if(isset($search_data['pageNo']))
            $data['from'] = ((int)$search_data['pageNo']-1) * $search_data['pageSize'];
        else
            $data['from'] = 0;

        /*排序*/
        if(isset($search_data['dateSort']))
            $data['sort']['createTime']['order'] = $search_data['dateSort'];                                                            //date
        elseif(isset($search_data['hotSort']))
            $data['sort']['point']['order']      = $search_data['hotSort'];                                                             //point


        /*条件*/
        $filters = $querys = $must_no = array();
        if(isset($search_data['type'])){
            $filters[] = $this->setupFilter($search_data['type'],'type');
        }
        if(isset($search_data['root_id'])){
            $filters[] = $this->setupFilter($search_data['root_id'],'root_id');
        }
        if(isset($search_data['children_id'])){
            $filters[] = $this->setupFilter($search_data['children_id'],'children_id');
        }
        if(isset($search_data['store_id'])){
            $filters[] = $this->setupFilter($search_data['store_id'],'store_id');
        }
        if(isset($search_data['root_type'])){
            $filters[] = $this->setupFilter($search_data['root_type'],'root_type');
        }
        if(isset($search_data['shopping'])){
            $must_no = $this->setupFilter($search_data['shopping'],'shopping');
        }
        if(isset($search_data['keywords'])){
            $querys = $this->setupFilter($search_data['keywords'],'keywords');
        }

        //时间点过滤
        $date_range = array(
            'range'=>array(
                'createTime'=>array(
                    'from'=>null,
                    'to'=>$current_date,
                    'include_lower'=>true,
                    'include_upper'=>true,
                )
            )
        );
        $filters[] = $date_range;

        if($querys)  $data['query'] = $querys;
        if($must_no) $data['query']['bool']= $must_no;
        $data['post_filter']['and']['filters'] = $filters;
        $data['fields'] = 'id';
        $array['_type'] = $this->_type;
        $array['data']  = $data;
        //return $data;

        /*搜索*/
        $es =new tradeElasticSearch();
        $indexData = $es->search($array);
        $indexData = json_decode($indexData,true);

        /*处理返回数据*/
        $return = $this->checkData($indexData);

        return $return;
    }

    /*post_filter 过滤*/
    private function setupFilter($data,$flag){
        switch($flag){
            /*类型*/
            case "type":
                return  array(
                    'term'=>array(
                        'type'=>$data,
                    )
                );
                break;
            /*一级分类*/
            case "root_id":
                return  array(
                    'term'=>array(
                        'rootId'=>$data
                    )
                );
                break;
            /*二级分类*/
            case "children_id":
                return  array(
                    'term'=>array(
                        'childrenId'=>$data
                    )
                );
                break;
            /*商城*/
            case "store_id":
                return array(
                    'term'=>array(
                        'storeId'=>$data
                    )
                );
                break;
            /*信息类型*/
            case "root_type":
                return  array(
                    'terms'=>array(
                        'rootType'=>$data
                    )
                );
                break;
            /*代购*/
            case "shopping":
                return  array(
                    'must_not'=>array(
                            'terms'=>array(
                                'shoppingId'=>array(0)
                            )
                    )
                );

                break;
            /*关键字*/
            case "keywords":
                return $match_keywords = array(
                    'match'=>array(
                        'title'=>array(
                            'query'=>$data,
                            'operator'=>'and',
                        )
                    )
                );
                break;
        }

    }

    //处理返回数据
    private function checkData($indexData){
        $result = $return = $types = $brands = $prices = array();
        if($indexData['status']){
            $data_hits= isset($indexData['data']['hits']) ? $indexData['data']['hits'] : array();
            if(!empty($data_hits['hits']) && is_array($data_hits['hits'])){
                foreach($data_hits['hits'] as $k=>$v){
                    $result[]['id'] =  $v['fields']['id'][0];
                }
            }

            $return['status'] = true;
            $return['num']    = $data_hits['total'];
            $return['result'] = $result;

        }else{
            $return['status'] = false;
        }
        return $return;
    }

    //update data
    public function _updateData($ids){
        $table = TrdNewsTable::getInstance();
        $tableData = $table
            ->createQuery($this->getLink())
            ->whereIn('id', $ids)
            ->fetchArray();
        $updateData = array();
        foreach($tableData as $tableDataVal) {
            if ($tableDataVal
                && $tableDataVal["is_delete"] == 0
                && in_array($tableDataVal["audit_status"], array(1, 4))
            ) {
                $channel_type = $tableDataVal["type"] ? $tableDataVal["type"] : 1;                              //频道类型
                $newTagsArr = $this->getTag($tableDataVal["id"]);

                $data = array();
                $data['id'] = $tableDataVal["id"];
                $data['title'] = $tableDataVal["title"];
                $data['type'] = $tableDataVal["type"];
                $data['channelType'] = $channel_type;
                $data['imgPath'] = $tableDataVal["img_path"];
                $data['price'] = $tableDataVal["price"];
                $data['point'] = $this->getPoint($tableDataVal["publish_date"], $tableDataVal['hits']);
                $data['rootId'] = $tableDataVal["root_id"];
                $data['childrenId'] = $tableDataVal["children_id"];
                $data['storeId'] = $tableDataVal["store_id"];
                $data['rootType'] = (int)$tableDataVal["root_type"];
                $data['shoppingId'] = (int)$tableDataVal["product_id"];
                $data['createTime'] = $tableDataVal["publish_date"];
                $data['tag'] = $newTagsArr;
                $data['hits'] = $tableDataVal['hits'];

                $updateData[] = $data;
            }
        }
        return $updateData;
    }

    //mapping data
    public function _mappingData(){
        return array(
            $this->_type => array(
                'properties'=>array(
                    'id'=>array('type'=> 'long'),
                    'title'=>array(
                        'index_analyzer'=> 'index_ansj',
                        'search_analyzer'=> 'query_ansj',
                        'type'=> 'string',
                        'fields'=>array(
                            'shingle'=>array(
                                'type'=>'string',
                                'analyzer'=>'shingle_ansj'
                            )
                        )
                    ),
                    'channelType'=>array('type'=> 'long'),
                    'createTime'=>array('type'=> 'date','format'=> 'yyyy-MM-dd HH:mm:ss'),
                    'price'=>array('type'=> 'string'),
                    'imagePath'=>array('type'=> 'string'),
                    'type'=>array('type'=> 'long'),
                    'rootId'=>array('type'=> 'long'),
                    'childrenId'=>array('type'=> 'long'),
                    'storeId'=>array('type'=> 'long'),
                    'rootType'=>array('type'=> 'long'),
                    'shoppingId'=>array('type'=> 'long'),
                    'tag'=>array('type'=> 'string','index'=>'not_analyzed'),
                    'point'=>array('type'=> 'double'),
                    'hits'=>array('type'=> 'long'),
                )
            )
        );
    }

    //热点值计算
    private  function getPoint($date,$attendCount){
        $date_point  = (strtotime($date) - strtotime(date('2015-01-01'))) / (86400/2);
        $click_point = log($attendCount, 4);

        if($click_point <= 0) $click_point =0;
        return round(($date_point+$click_point),2);
    }

    //获取tag
    private function getTag($id){
        $sql = 'SELECT pt.name FROM trd_news_tag nt LEFT JOIN trd_product_tag pt ON nt.trd_product_tag_id = pt.id  WHERE nt.trd_news_id = ?';
        $conn = $this->getLink();
        $st = $conn->execute($sql, array($id));
        $tagsArr = $st->fetchAll(Doctrine_Core::FETCH_ASSOC);

        $newTagsArr = array();
        foreach($tagsArr as $k=>$v){
            $newTagsArr[] = $v['name'];
        }
        return $newTagsArr;
    }
}













