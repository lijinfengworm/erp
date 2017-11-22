<?php
Class newFindSearch extends tradeESBaseSearch{
    public   $_type = 'newfind';
    public   $_channel_type = 9;
    /*运动鞋列表搜索*/
    public function search($search_data){
        $current_date = date('Y-m-d H:i:s');
        if(isset($search_data['pageSize']))       //分页
            $data['size'] = (int)$search_data['pageSize'];
        else
            $data['size'] = 10;
        if(isset($search_data['pageNo']))
            $data['from'] = ((int)$search_data['pageNo']-1) * $search_data['pageSize'];
        else
            $data['from'] = 0;

        /*排序*/
        if(isset($search_data['dateSort'])) $data['sort']['createTime']['order'] = $search_data['dateSort'];                               //时间
        if(isset($search_data['hotSort'])) $data['sort']['rank']['order'] = $search_data['hotSort'];                                       //rank值

        /*条件*/
        $filters = $querys = array();
        if(isset($search_data['keywords'])){
            $querys[] = $this->setupFilter($search_data['keywords'], 'keywords');
        }
        if(isset($search_data['rootId'])){
            $filters[] = $this->setupFilter($search_data['rootId'], 'rootId');
        }
        if(isset($search_data['tag'])){
            $filters[] = $this->setupFilter($search_data['tag'], 'tag');
        }
        if(isset($search_data['ltId'])){
            $filters[] = $this->setupFilter($search_data['ltId'], 'ltId');
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

        if($querys)$data['query'] = $querys;
        $data['post_filter']['and']['filters'] = $filters;
        $data['fields'] = 'id';
        $array['_type'] = $this->_type;
        $array['data']  = $data;

        /*搜索*/
        $es =new tradeElasticSearch();
        $indexData = $es->search($array);
        $indexData = json_decode($indexData,true);

        /*处理返回数据*/
        $return = $this->checkData($indexData);
        return $return;
    }

    /*搜索BY tag*/
    public function searchByTag($params){
        $array = $data = $terms = $orders = array();

        foreach($params['tags'] as $tag){
            $terms[] = array('term'=>
                array('tag'=>$tag)
            );
        }

        $orders = array( '_score'=>'desc' , 'point'=>'desc' );
        if( !empty($params['notId']) ){
            $data['query']['bool']['must_not'] = array('term'=>array('id'=>$params['notId']));
        }
        $data['query']['bool']['should'] = $terms;
        $data['size'] = $params['num'];
        $data['sort'] = $orders;

        $data['fields'] = array('id');
        $array['_type'] = $this->_type;
        $array['data']  = $data;

        #搜索
        $es =new tradeElasticSearch();
        $indexData = $es->search($array);
        $indexData = json_decode($indexData, true);

        #返回数据
        return $res = $this->checkData($indexData);
    }

    /*post_filter 过滤*/
    private function setupFilter($data,$flag){
        switch($flag) {
            /*关键字*/
            case "keywords":
                return  array(
                    'match'=>array(
                        'title'=>array(
                            'query'=>$data,
                            'operator'=>'and',
                        )
                    )
                );
                break;
            /*一级分类*/
            case "rootId":
                return  array(
                    'term'=>array(
                        'rootId'=> $data,
                    )
                );
                break;
            /*TAG*/
            case "tag":
                return  array(
                    'term'=>array(
                        'tag'=>$data
                    )
                );
                break;
            /*lt id*/
            case "ltId":
                return array(
                   'range'=>array(
                       'id'=>array(
                            'from'=>0,
                            'to'  =>$data,
                            'include_lower'=>true,
                            'include_upper'=>false,
                        )
                   )
                );
               break;
        }
    }


    /*处理返回数据*/
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
            $return['num'] = $data_hits['total'];
            $return['result'] = $result;

        }else{
            $return['status'] = false;
        }

        return $return;
    }

    /*热点值计算*/
    private  function getPoint($date,$attendCount){
        $date_point = (strtotime($date) - strtotime(date('2015-01-01'))) / (86400/2);
        $click_point = log($attendCount,4);

        if($click_point <= 0) $click_point =0;
        return round(($date_point+$click_point),2);
    }

    //update data
    public function _updateData($ids){
        $table = TrdFindTable::getInstance();
        $tableData = $table
            ->createQuery($this->getLink())
            ->whereIn('id', $ids)
            ->fetchArray();
        $updateData = array();
        foreach($tableData as $tableDataVal) {
            if($tableDataVal && $tableDataVal["status"] == 1){//审核通过
                $channelTypes = sfConfig::get('app_shihuo_elasticsearch_channel_types');
                $channelType = $channelTypes[$this->_type];

                $tagsAttr  = $tableDataVal["tags_attr"];  //tag
                $tagsAttr  = json_decode($tagsAttr, true);
                $tag_names = $tagsAttr['name'];

                $data =  array();
                $data['id']     =  $tableDataVal["id"];
                $data['title']  =  $tableDataVal["title"];
                $data['channelType'] =  $channelType ;
                $data['childId'] =  $tableDataVal["children_id"];
                $data['rootId']  =  $tableDataVal["root_id"];
                $data['tag']     =  $tag_names;
                $data['price']   =  (int)$tableDataVal["price"];
                $data['point'] =  $this->getPoint($tableDataVal['publish_date'], $tableDataVal['hits']);
                $data['createTime'] =  $tableDataVal["publish_date"];
                $data['hits'] = $tableDataVal['hits'];

                $updateData[] = $data;
            }
        }

        return  $updateData;
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
                    'childId'=>array('type'=> 'long'),
                    'rootId'=>array('type'=> 'long'),
                    'tag'=>array('type'=> 'string','index'=>'not_analyzed'),
                    'price'=>array('type'=> 'double'),
                    'point'=>array('type'=> 'double'),
                    'createTime'=>array('type'=> 'date','format'=> 'yyyy-MM-dd HH:mm:ss'),
                    'hits'=>array('type'=> 'long'),
                )
            )
        );
    }
}













