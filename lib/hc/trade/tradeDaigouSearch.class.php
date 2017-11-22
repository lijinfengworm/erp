<?php
Class daigouSearch extends tradeESBaseSearch{
    public   $_type = 'daigou';
    public   $_channel_type = 6;
    private  $allDaigouKey = 'trade:daigou:all';
    private  $defaultDaceRank = 0.001;

    /*代购搜索*/
    public function search($search_data){
        $data['min_score'] = 0.25;
        if(!empty($search_data['pageSize']))       //分页
            $data['size'] = (int)$search_data['pageSize'];
        else
            $data['size'] = 10;
        if(!empty($search_data['pageNo']))
            $data['from'] = ((int)$search_data['pageNo']-1) * $search_data['pageSize'];
        else
            $data['from'] = 0;

        /*排序*/
        if(!empty($search_data['dateSort'])) $data['sort']['createTime']['order'] = $search_data['dateSort'];                            //时间
        if(!empty($search_data['hotSort'])) $data['sort']['rank']['order'] = $search_data['hotSort'];                                    //rank值
        if(!empty($search_data['daceSort'])) $data['sort']['daceRank']['order'] = $search_data['daceSort'];                              //dacerank排序
        if(!empty($search_data['priceSort'])){
            if(strtolower($search_data['priceSort']) == 'desc')
                $data['sort']['price']['order'] ='desc';                                                                                //时间
            elseif(strtolower($search_data['priceSort']) == 'asc')
                $data['sort']['price']['order'] = 'asc';
        }

        /*条件*/
        $filters = $querys = $not_must = $shoulds = array();
        if(!empty($search_data['brand'])){                                                                                                   //品牌
            $filters[] = $this->setupFilter($search_data['brand'],'brand');
        }
        if(!empty($search_data['root_id'])){                                                                                                 //一级分类
            $filters[] = $this->setupFilter($search_data['root_id'],'root_id');
        }
        if(!empty($search_data['children_id'])){                                                                                             //二级分类
            $filters[] = $this->setupFilter($search_data['children_id'],'children_id');
        }
        if(!empty($search_data['price'])){                                                                                                   //价格
            $filters[] = $this->setupFilter($search_data['price'],'price');
        }
        if(!empty($search_data['keywords'])){
            $querys = $this->setupFilter($search_data['keywords'],'keywords');

            $shoulds = array(
                'match'=> array(
                    'title.shingle' => $search_data['keywords']
                )
            );
        }
        if(!empty($search_data['aid'])){                                                                                                  //活动Id
            if( $search_data['aid'] == 'all' ){
                $aidAllFilter = $this->setupFilter($search_data['aid'],'aid_all');

                $filters[] = $aidAllFilter['start'];
                $filters[] = $aidAllFilter['end'];
            }else{
                $filters[] = $this->setupFilter($search_data['aid'], 'aid');
            }
        }

        /*facet 返回剩余数据类型*/
        $facets = $this->setupFacet($search_data, $filters, $querys);

        if($facets) $data['facets'] = $facets;
        if($querys)   $data['query']['bool']['must']     = $querys;
        if($not_must) $data['query']['bool']['must_not'] = $not_must;
        if($shoulds)  $data['query']['bool']['should']   = $shoulds;
        $data['post_filter']['and']['filters'] = $filters;
        $array['_type'] = $this->_type;
        $array['data']  = $data;
        //return $data;

        /*搜索*/
        $es =new tradeElasticSearch();
        $indexData = $es->search($array);
        $indexData = json_decode($indexData, true);

        /*处理返回数据*/
        $return = $this->checkData($indexData);
        return $return;
    }



    /*处理返回数据*/
    private function checkData($indexData){
        $result = $return = $rootId = $childrenId = $brands = $prices = $childrenName = $rootName = array();
        if($indexData['status']){
            $data_hits= isset($indexData['data']['hits']) ? $indexData['data']['hits'] : array();
            $data_facets = isset($indexData['data']['facets']) ? $indexData['data']['facets'] : array();
            if(!empty($data_hits['hits']) && is_array($data_hits['hits'])){
                foreach($data_hits['hits'] as $k=>$v){
                    $result[$v['_source']['id']]['id'] =  $v['_source']['id'];
                    if(isset($v['_source']['activity'])){
                        $result[$v['_source']['id']]['activity'] =  $v['_source']['activity'];
                    }else{
                        $result[$v['_source']['id']]['activity'] = array();
                    }
                }
            }

            if(!empty($data_facets)){
                if(isset($data_facets['rootIdFacet']['terms'])){
                    foreach($data_facets['rootIdFacet']['terms'] as $k=>$v){
                        $rootId[] = $v['term'];
                    }

                    //排序
                    $all_root_name = trdDaigouMenuTable::getAllMenuName(0);
                    $all_root_id = array_keys($all_root_name);

                    $rootId = array_intersect($all_root_id,$rootId);
                    $rootId = array_flip($rootId);
                    $rootName = array_intersect_key($all_root_name,$rootId);
                }

                if(isset($data_facets['childrenIdFacet']['terms'])){
                    foreach($data_facets['childrenIdFacet']['terms'] as $k=>$v){
                        $childrenId[] = $v['term'];
                    }
                    //排序
                    $all_children_name = trdDaigouMenuTable::getAllMenuName(1);
                    $all_children_id = array_keys($all_children_name);
                    $childrenId = array_intersect($all_children_id,$childrenId);
                    $childrenId = array_flip($childrenId);
                    $childrenName = array_intersect_key($all_children_name, $childrenId);
                }

                if(isset($data_facets['brandFacet']['terms'])){
                    foreach($data_facets['brandFacet']['terms'] as $k=>$v){
                        $brands[] = $v['term'];
                    }

                    //排序
                    if(($brand_key = array_search('其他',$brands)) !== FALSE){
                        unset($brands[$brand_key]);
                        array_push($brands,'其他');
                    }
                }

                if(isset($data_facets['priceFacet'])){
                    foreach($data_facets['priceFacet']['ranges'] as $k=>$v){
                        if($v['count'] > 0 ){
                            if($v['to'] == '10000')
                                $prices[] = '1000以上';
                            elseif($v['from'] == '0')
                                $prices[] = '100以下';
                            else
                            {
                                $v['to'] = $v['to'] -1;
                                $prices[] = $v['from'].'-' .$v['to'];
                            }
                        }
                    }
                }
            }

            $return['status'] = true;
            $return['num'] = $data_hits['total'];
            $return['root_name'] = $rootName;
            $return['children_name'] = $childrenName;
            $return['brand']  = $brands;
            $return['price']  = $prices;
            $return['result'] = $result;

        }else{
            $return['status'] = false;
        }

        return $return;
    }


    /*post_filter 过滤*/
    private function setupFilter($data,$flag){
        switch($flag){
            /*品牌*/
            case "brand":
                return array(
                    'term'=>array(
                        'brand'=>$data
                    )
                );
                break;
            /*一级分类*/
            case "root_id":
                return array(
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
            /*价格区间*/
            case "price":
                $price_space  = explode('-',$data);
                return  array(
                    'range'=>array(
                        'price'=>array(
                            'from'=>$price_space[0],
                            'to'=>$price_space[1],
                            "include_lower" => true,
                            "include_upper" => true
                        )
                    )
                );
                break;
            /*活动ID*/
            case "aid":
                return array(
                    'terms'=>array(
                        'activity.id'=>$data
                    )
                );
                break;
            /*全部 活动*/
            case "aid_all":
                $current_time = date('Y-m-d H:i:s');
                return  array(
                    'start'=>array(
                        'range'=>array(
                            'activity.detail.startTime'=>array(
                                'from'=>null,
                                'to'=>$current_time,
                                "include_lower" => true,
                                "include_upper" => true
                            )
                        )
                    ),
                    'end'=>array(
                        'range'=>array(
                            'activity.detail.endTime'=>array(
                                'from'=>$current_time,
                                'to'=>null,
                                "include_lower" => true,
                                "include_upper" => true
                            )
                        )
                    )
                );
                break;
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
        }

    }

    /*facet 返回剩余数据类型*/
    private function setupFacet($search_data,$filters,$querys){
        $rootIdFacet = $this->getRootIdFacet($filters,$querys);
        $childrenIdFacet = $this->getChildrenIdFacet($filters,$querys);
        $brandFacet = $this->getBrandFacet($filters,$querys);
        $priceFacet = $this->getPriceFacet($filters,$querys);
        $facets = array('rootIdFacet'=>$rootIdFacet,'childrenIdFacet'=>$childrenIdFacet,'brandFacet'=>$brandFacet,'priceFacet'=>$priceFacet);
        if(isset($search_data['rootId']))  unset($facets['rootIdFacet']);
        if(isset($search_data['childrenId']))  unset($facets['childrenIdFacet']);
        if(isset($search_data['brand'])) unset($facets['attrsBrandFacet']);
        if(isset($search_data['price'])) unset($facets['priceFacet']);

        return $facets;
    }



    /*价格Facet*/
    private function getPriceFacet($facet_filter,$querys){
        return array(
            'range'=>array(
                "field" => "price",
                "ranges" => array(
                    array(
                        "from"=>0.0,
                        "to" => 100.0
                    ),
                    array(
                        "from"=>100.0,
                        "to" => 300.0
                    ),
                    array(
                        "from"=>300.0,
                        "to" => 500.0
                    ),
                    array(
                        "from"=>500.0,
                        "to" => 1000.0
                    ),
                    array(
                        "from"=>1000.0,
                        "to" => 10000.0
                    ),
                ),
            ),
            'facet_filter'=>array(
                'and'=>array(
                    "filters"=>$facet_filter
                )
            ),
        );
    }

    /*品牌Facet*/
    private function getBrandFacet($facet_filter,$querys){
        return array(
            'terms'=>array(
                "field" => "brand",
                "size" => 10,
                "order" => "count"
            ),
            'facet_filter'=>array(
                'and'=>array(
                    "filters"=>$facet_filter
                )
            ),
        );
    }


    /*一级分类Facet*/
    private function getRootIdFacet($facet_filter,$querys){
        return array(
            'terms'=>array(
                "field" => "rootId",
                "size" => 10,
                "order" => "count"
            ),
            'facet_filter'=>array(
                'and'=>array(
                    "filters"=>$facet_filter
                )
            ),
        );
    }

    /*二级分类Facet*/
    private function getChildrenIdFacet($facet_filter,$querys){
        return array(
            'terms'=>array(
                "field" => "childrenId",
                "size" => 10,
                "order" => "count"
            ),
            'facet_filter'=>array(
                'and'=>array(
                    "filters"=>$facet_filter
                )
            ),
        );
    }

    /*获取品牌名*/
    private  function getBrandName($brandId){
        if(!$brandId) return '其他';

        $brandTable = trdNewsBrandsTable::getInstance();
        $brand = $brandTable
            ->createQuery($this->getLink())
            ->where('id =?', $brandId)
            ->fetchOne();

        if($brand)
            $brandName = trim($brand->getBrandName());
        else
            $brandName = '其他';

        return $brandName;
    }

    /*
    *获取活动信息
    **/
    private function getMarketingActivityInfo($id){
        $trdMarketingGroupTable = trdMarketingActivityGroupTable::getInstance();
        $trdMarketingGroup = $trdMarketingGroupTable
            ->createQuery($this->getLink())
            ->where('item_id = ?',$id)
            ->execute()
            ->toArray();

        $return = array('status' => false);

        if($trdMarketingGroup){
            $time = time();

            $sql = "SELECT ma.* FROM trd_marketing_activity_group mag LEFT JOIN
trd_marketing_activity ma ON ma.id = mag.activity_id  WHERE mag.item_id = ? AND  mag.etime > {$time}";
            $conn = $this->getLink();
            $st = $conn->execute($sql, array($id));
            $marketingActivity = $st->fetchAll(Doctrine_Core::FETCH_ASSOC);

            if($marketingActivity){
                $return['status'] = true;

                foreach($marketingActivity as $marketingActivity_val){
                    $return['data'][] = array(
                        'id'=> $marketingActivity_val['id'],
                        'name'=>$marketingActivity_val['short_name'],
                        'detail'=>array(
                            'mode'=>$marketingActivity_val['mode'],
                            'intro'=>$marketingActivity_val['intro'],
                            'startTime'=>date('Y-m-d H:i:s',$marketingActivity_val['stime']),
                            'endTime'=>date('Y-m-d H:i:s',$marketingActivity_val['etime'])
                        )
                    );
                }
            }
        }

        return $return;
    }

    /*代购rank值*/
    private  function getRank($date,$attendCount){
        $date_point = (strtotime($date) - strtotime(date('2015-01-01'))) / (86400/2);
        $click_point = log($attendCount,1.8);

        if($click_point <= 0) $click_point = 0;
        return round(($date_point+$click_point),2);
    }

    //热点值计算
    private  function getPoint($date,$attendCount){
        $date_point  = (strtotime($date) - strtotime(date('2015-01-01'))) / (86400/2);
        $click_point = log($attendCount,3);

        if($click_point <= 0) $click_point =0;
        return round(($date_point+$click_point),2);
    }

    //update data
    public function _updateData($ids){
        $productAttrTable = TrdProductAttrTable::getInstance();
        $productAttr = $productAttrTable
            ->createQuery($this->getLink())
            ->whereIn('id', $ids)
            ->fetchArray();
        
        $updateData = array();
        foreach($productAttr as $productAttrVal){
            if($productAttr
                && $productAttrVal["status"] == 0
                && $productAttrVal["show_flag"] == 1
                && $productAttrVal["purchase_flag"] == 0
            ){
                $brandName = $this->getBrandName($productAttrVal["brand_id"]);                           //品牌名
                $marketingActivity = $this->getMarketingActivityInfo($productAttrVal["id"]);             //营销活动
                $daceRank = $this->getDaceRank($productAttrVal["id"]);                                   //daceRank

                $data = array();
                $data['id'] =  $productAttrVal["id"];
                $data['title'] =  $productAttrVal["title"];
                $data['channelType'] = $this->_channel_type;
                $data['imgPath'] =  $productAttrVal["img_path"];
                $data['brand'] = $brandName;
                $data['price'] =  $productAttrVal["price"];
                $data['business'] =  $productAttrVal["business"];
                $data['point']  =  $this->getPoint($productAttrVal["publish_date"], $productAttrVal['hits']);
                $data['rank']   =  $this->getRank($productAttrVal["publish_date"], $productAttrVal['hits']);
                $data['rootId'] =  $productAttrVal["root_id"];
                $data['childrenId'] =  $productAttrVal["children_id"];
                $data['createTime'] =  $productAttrVal["publish_date"];
                $data['daceRank'] = $daceRank;
                $data['hits'] = $productAttrVal['hits'];

                if($marketingActivity['status']){
                    $data['activity'] =  $marketingActivity['data'];
                }

                $updateData[] = $data;
            }
        }

        return $updateData;
    }

    //mapping data
    public function _mappingData(){
        return   array(
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
                    'brand'=> array('type'=> 'string','index'=>'not_analyzed'),
                    'business'=> array('type'=> 'string','index'=>'not_analyzed'),
                    'price'=>array('type'=> 'double'),
                    'imagePath'=>array('type'=> 'string'),
                    'rootId'=>array('type'=> 'long'),
                    'childrenId'=>array('type'=> 'long'),
                    'point'=>array('type'=> 'double'),
                    'rank'=>array('type'=> 'double'),
                    'daceRank'=>array('type'=> 'double'),
                    'activity'=> array(
                        'properties'=> array(
                            'id' => array('type'=> 'long'),
                            'name'=> array('type'=> 'string','index'=>'not_analyzed'),
                            'detail'=>array(
                                'type'=> 'object',
                                'properties'=>array(
                                    'mode'=> array('type'=> 'long'),
                                    'intro'=> array('type'=> 'string','index'=>'not_analyzed'),
                                    'startTime'=> array('type'=> 'date','format'=> 'yyyy-MM-dd HH:mm:ss'),
                                    'endTime'=>array('type'=> 'date','format'=> 'yyyy-MM-dd HH:mm:ss'),
                                )
                            )
                        )
                    ),
                    'hits'=>array('type'=> 'long'),
                )
            )
        );
    }

    //dace rank值
    private  function getDaceRank($id){
        $daceRank = $this->getRedis()->hget($this->allDaigouKey, $id);
        if($daceRank){
            return $daceRank;
        }else{
            return $this->defaultDaceRank;
        }
    }
}













