<?php
Class shopSearch extends tradeESBaseSearch{
    public   $_type = 'shop';
    public   $_channel_type = 8;

    /*列表搜索*/
    public function search($search_data){
        if(isset($search_data['pageSize']))       //分页
            $data['size'] = (int)$search_data['pageSize'];
        else
            $data['size'] = 10;
        if(isset($search_data['pageNo']))
            $data['from'] = ((int)$search_data['pageNo']-1) * $search_data['pageSize'];
        else
            $data['from'] = 0;
        /*条件*/
        $filters = $querys = array();

        if(isset($search_data['keywords']) && $search_data['keywords']){
            $data['query']['multi_match'] = array(
                'query'=>$search_data['keywords'],
                'type'=>'best_fields',
                'fields'=>array('name','business'),
                'operator'=>'and',
            );

            $data['highlight'] =array(
                "pre_tags" => array('<font color="red">'),
                "post_tags" => array('</font>'),
                "fields" => array('name'=>new stdClass(),'business'=>new stdClass())
            );

        }

        # 类型
        if(isset($search_data['type']) && $search_data['type']){
            $filters[] = array(
                'term' => array(
                    'shop_category_id' => $search_data['type']
                )
            );
        }

        # 按照佣金排序
        $data['sort']['charge']['order'] = 'desc';

        # 筛选显示的店铺
        $filters[] = array(
            'term' => array(
                'status' => 0
            )
        );
        $data['post_filter']['and']['filters'] = $filters;
        $data['fields'] = 'id';
        $array['_type'] = $this->_type;
        $array['data'] = $data;
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
        switch($flag) {
            /*佣金*/
            case "charge":
                return array(
                    'range' => array(
                        'balance' => array(
                            "gte"=> 0,
                        ),
                    )
                );
                break;

            /*店铺名称*/
            case "name":
                return  array(
                        'name'=>array(
                            'query'=>$data,
                            'operator'=>'and',
                        )
                );
                break;
            /*店主名称*/
            case "owner_name":
                return  array(
                        'owner_name'=>array(
                            'query'=>$data,
                            'operator'=>'and',
                        )
                );
                break;
            /*类型ID*/
            case "shop_category_id":
                return array(
                    'term' => array(
                        'status' => 0
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
                    $result[$k]['id'] =  $v['fields']['id'][0];
                    if(!empty($v['highlight']['name'])) $result[$k]['name'] = $v['highlight']['name'][0];
                    if(!empty($v['highlight']['business'])) $result[$k]['business'] = $v['highlight']['business'][0];
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

    //update data
    public function _updateData($ids){
        $table = TrdShopInfoTable::getInstance();
        $tableData = $table
            ->createQuery($this->getLink())
            ->whereIn('id', $ids)
            ->fetchArray();
        $updateData = array();

        foreach($tableData as $tableDataVal) {
            if($tableDataVal){
                $channelTypes = sfConfig::get('app_shihuo_elasticsearch_channel_types');
                $channelType  = $channelTypes[$this->_type];

                $data =  array();
                $data['id'] =  $tableDataVal["id"];
                $data['charge'] =  $tableDataVal["charge"];
                $data['status'] =  $tableDataVal["status"];
                $data['name'] =  $tableDataVal["name"];
                $data['shop_category_id'] =  $tableDataVal["shop_category_id"];
                $data['channelType'] =  $channelType ;
                $data['business'] =  $tableDataVal["business"];
                $data['owner_name'] =  $tableDataVal["owner_name"];
                $data['position'] =  $tableDataVal["position"];

                $updateData[] = $data;
            }
        }

        return  $updateData;
    }
    //mapping data
    public function _mappingData(){
        return array(
            'shop'=>array(
                'properties'=>array(
                    'id'=>array('type'=> 'long'),
                    'position'=>array('type'=> 'long'),
                    'status'=>array('type'=> 'long'),
                    'shop_category_id'=>array('type'=> 'long'),
                    'charge'=>array(
                        'type'=> 'string'
                    ),
                    'name'=>array(
                        'index_analyzer'=> 'index_ansj',
                        'search_analyzer'=> 'query_ansj',
                        'type'=> 'string'
                    ),
                    'channelType'=>array('type'=> 'long'),
                    'business'=>array(
                        'index_analyzer'=> 'index_ansj',
                        'search_analyzer'=> 'query_ansj',
                        'type'=> 'string'
                    ),
                )
            )
        );
    }
}













