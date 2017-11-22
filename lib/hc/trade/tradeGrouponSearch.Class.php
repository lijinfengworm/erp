<?php
Class grouponSearch extends tradeESBaseSearch{
    public   $_type = 'groupon';
    public   $_channel_type = 3;     //频道类型为3
    private  $chinaBrands = array(   //国产精品
        '贵人鸟', '安踏', '李宁', '匹克', '361', '中国乔丹', '鸿星尔克', '准者', '回力', '特步', '双星', '国产精品',
    );
    private  $sportswear = array(     //运动服饰
        '长袖', '长裤', '运动服饰'
    );
    private  $sportsEquipment = array( //运动装备
        '护具',  '运动装备'
    );

    /*
    * 团购列表搜索
    */
    public function search($search_data){
        $current_date = date('Y-m-d H:i:s');
        $array = $data = array();

        /*分页*/
        if(isset($search_data['pageSize']))
            $data['size'] = (int)$search_data['pageSize'];
        else
            $data['size'] = 10;
        if(isset($search_data['pageNo']))
            $data['from'] = ((int)$search_data['pageNo']-1) * $search_data['pageSize'];
        else
            $data['from'] = 0;

        /*排序*/
        if(isset($search_data['rankSort'])) $data['sort']['rank']['order'] = $search_data['rankSort'];                                      //rank值
        if(isset($search_data['newSort'])){
            if(strtolower($search_data['newSort']) == 'desc')
                $data['sort']['startTime']['order'] ='desc';                                                                                //时间
            elseif(strtolower($search_data['newSort']) == 'asc')
                $data['sort']['startTime']['order'] = 'asc';
        }
        if(isset($search_data['hotSort'])) $data['sort']['attendCount']['order'] = $search_data['hotSort'];                                //热度
        if(isset($search_data['dateSort'])) $data['sort']['startTime']['order'] = $search_data['dateSort'];                                //时间
        if(isset($search_data['discountSort'])) $data['sort']['agio']['order'] = $search_data['discountSort'];                             //折扣
        if(isset($search_data['priceSort'])) $data['sort']['price']['order'] = $search_data['priceSort'];                                 //价格

        /*条件*/
        $filters = $querys = array();
        if(isset($search_data['brand'])){                                                                                                   //品牌
            $filters[] = $this->setupFilter($search_data['brand'],'brand');
        }
        if(isset($search_data['type'])){                                                                                                    //类型
            $filters[] = $this->setupFilter($search_data['type'], 'type');
        }
        if(isset($search_data['price'])){                                                                                                   //价格
            $filters[] = $this->setupFilter($search_data['price'],'price');
        }
        if(isset($search_data['keywords'])){
            $querys = $this->setupFilter($search_data['keywords'],'keywords');
        }

        /*时间节点过滤*/
        if(isset($search_data['run']) && !$search_data['run']){
            $date_range = array(
                'range'=>array(
                    'endTime'=>array(
                        'from'=>null,
                        'to'=>$current_date,
                        'include_lower'=>true,
                        'include_upper'=>true,
                    )
                )
            );
            $filters[] = $date_range;
        }else{
            $date_range_one = array(
                'range'=>array(
                    'startTime'=>array(
                        'from'=>null,
                        'to'=>$current_date,
                        'include_lower'=>true,
                        'include_upper'=>true,
                    ),
                )
            );

            $date_range_two =array(
                'range'=>array(
                    'endTime'=>array(
                        'from'=>$current_date,
                        'to'=>null,
                        'include_lower'=>true,
                        'include_upper'=>true,
                    )
                )
            );

            $filters[] = $date_range_one;
            $filters[] = $date_range_two;
        }


        /*facet 返回剩余数据类型*/
        $facets = $this->setupFacet($search_data,$filters,$querys);

        if($facets)$data['facets'] = $facets;
        if($querys)$data['query'] = $querys;
        $data['post_filter']['and']['filters'] = $filters;
        $data['fields'] = 'id';
        $array['_type'] = $this->_type;
        $array['data'] = $data;
        // return $data;

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
            /*品牌*/
            case "brand":
                if($data == '国产精品')
                {
                    foreach($this->chinaBrands as $v)
                    {
                        $term_brand['bool']['should'][]['term']['attrs.brand'] = $v;
                    }
                    return $term_brand;
                }
                else
                {
                    return $term_brand = array(
                        'term'=>array(
                            'attrs.brand'=>$data
                        )
                    );
                }
                break;
            /*类型*/
            case "type":
                if($data == '运动服饰')
                {
                    return array(
                        'terms'=>array(
                            'attrs.type'=>$this->sportswear
                        )
                    );
                } elseif($data == '运动装备') {
                    return array(
                        'terms'=>array(
                            'attrs.type'=>$this->sportsEquipment
                        )
                    );
                } else {
                    $data = is_array($data) ? $data: array($data);
                    $term_type = array(
                        'terms'=>array(
                            'attrs.type'=>$data
                        )
                    );
                    return $term_type;
                }

                break;
            /*价格区间*/
            case "price":
                $price_space  = explode('-',$data);
                return $range_price = array(
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
            /*关键字*/
            case "keywords":
                return $match_keywords = array(
                    'match'=>array(
                        'title'=>array(
                            'query'   => $data,
                            'operator'=>'and',
                        )
                    )
                );
                break;
        }

    }

    /*facet 返回剩余数据类型*/
    private function setupFacet($search_data,$filters,$querys){
        $typeFacet= $this->getTypeFacet($filters,$querys);
        $brandFacet = $this->getBrandFacet($filters,$querys);
        $priceFacet = $this->getPriceFacet($filters,$querys);
        $facets = array('attrsTypeFacet'=>$typeFacet,'attrsBrandFacet'=>$brandFacet,'priceFacet'=>$priceFacet);
        if(isset($search_data['type']))  unset($facets['attrsTypeFacet']);
        if(isset($search_data['brand'])) unset($facets['attrsBrandFacet']);
        if(isset($search_data['price'])) unset($facets['priceFacet']);

        return $facets;
    }


    /*处理返回数据*/
    private function checkData($indexData){
        $result = $return = $types = $brands = $prices = array();
        if($indexData['status']){
            $data_hits= isset($indexData['data']['hits']) ? $indexData['data']['hits'] : array();
            $data_facets = isset($indexData['data']['facets']) ? $indexData['data']['facets'] : array();
            if(!empty($data_hits['hits']) && is_array($data_hits['hits'])){
                foreach($data_hits['hits'] as $k=>$v){
                    $result[]['id'] =  $v['fields']['id'][0];
                }
            }

            if(!empty($data_facets)){
                $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
                $c_key = 'trade_groupon_all_category';
                $b_key = 'trade_groupon_all_brand';

                $all_category = unserialize($redis->get($c_key));
                $all_brand = unserialize($redis->get($b_key));

                if(isset($data_facets['attrsTypeFacet']['terms'])){
                    foreach($data_facets['attrsTypeFacet']['terms'] as $k=>$v){
                        $types[] = $v['term'];
                    }

                    if($all_category){//排序
                        $types = array_intersect($all_category,$types);
                        ksort($types);
                    }
                }

                if(isset($data_facets['attrsBrandFacet']['terms'])){
                    foreach($data_facets['attrsBrandFacet']['terms'] as $k=>$v){
                        $brands[] = $v['term'];
                    }

                    if($all_brand){//排序
                        $brands = array_intersect($all_brand,$brands);
                        ksort($brands);
                    }
                }

                if(isset($data_facets['priceFacet'])){
                    foreach($data_facets['priceFacet']['ranges'] as $k=>$v){
                        if($v['count'] > 0 ){
                            if($v['to'] == '7000')
                                $prices[] = '700以上';
                            else{
                                $v['to'] = $v['to'] -1;
                                $prices[] = $v['from'].'-' .$v['to'];
                            }
                        }
                    }
                }
            }

            $return['status'] = true;
            $return['num'] = $data_hits['total'];
            $return['type'] = $types;
            $return['brand'] = $brands;
            $return['price'] = $prices;
            $return['result'] = $result;

        }else{
            $return['status'] = false;
        }

        return $return;
    }


    /*价格Facet*/
    private function getPriceFacet($facet_filter,$querys){
        return array(
            'range'=>array(
                "field" => "price",
                "ranges" => array(
                    array(
                        "from"=>0.0,
                        "to" => 300.0
                    ),
                    array(
                        "from"=>300.0,
                        "to" => 500.0
                    ),
                    array(
                        "from"=>500.0,
                        "to" => 700.0
                    ),
                    array(
                        "from"=>700.0,
                        "to" => 7000.0
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
                "field" => "attrs.brand",
                "size" => 50,
                "order" => "term"
            ),
            'facet_filter'=>array(
                'and'=>array(
                    "filters"=>$facet_filter
                )
            ),
        );
    }


    /*类型Facet*/
    private function getTypeFacet($facet_filter,$querys){
        return array(
            'terms'=>array(
                "field" => "attrs.type",
                "size" => 50,
                "order" => "count"
            ),
            'facet_filter'=>array(
                'and'=>array(
                    "filters"=>$facet_filter
                )
            ),
        );
    }

    //update data
    public function _updateData($ids){
        $table = TrdGrouponTable::getInstance();
        $tableData = $table
            ->createQuery($this->getLink())
            ->whereIn('id', $ids)
            ->fetchArray();
        $updateData = array();
        foreach($tableData as $tableDataVal) {
            if($tableDataVal && $tableDataVal['status'] == 6){
                $brandAllName = trdBrandTable::getInstance()->getAllName();
                $categoryAllName = trdGrouponCategoryTable::getInstance()->getAllCategoryName();
                $brandName = isset($brandAllName[$tableDataVal['brand_id']]) ? $brandAllName[$tableDataVal['brand_id']] : '其他';
                $categoryName = isset($categoryAllName[$tableDataVal['category_id']]) ? $categoryAllName[$tableDataVal['category_id']]: '其他';

                $data = array();
                $data['id'] =  $tableDataVal['id'];
                $data['title'] =  $tableDataVal['title'];
                $data['attendCount'] =  $tableDataVal['attend_count'];
                $data['channelType'] =  $this->_channel_type;
                $data['createTime'] =  $tableDataVal['start_time'];
                $data['startTime']  =  $tableDataVal['start_time'];
                $data['agio']   =  $tableDataVal['discount'];
                $data['endTime']    =  $tableDataVal['end_time'];
                $data['attrs'] ['brand'] = $brandName;
                $data['attrs'] ['type'] = $categoryName;
                $data['rank'] =  $tableDataVal['rank'];
                $data['price'] =  $tableDataVal['price'];
                $data['point'] =  $this->getPoint($tableDataVal['start_time'], $tableDataVal['attend_count']);
                $data['hits'] = $tableDataVal['attend_count'] * 3;

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
                    'attendCount'=>array('type'=> 'long'),
                    'channelType'=>array('type'=> 'long'),
                    'createTime'=>array('type'=> 'date','format'=> 'yyyy-MM-dd HH:mm:ss'),
                    'startTime'=>array('type'=> 'date','format'=> 'yyyy-MM-dd HH:mm:ss'),
                    'endTime'=>array('type'=> 'date','format'=> 'yyyy-MM-dd HH:mm:ss'),
                    'price'=>array('type'=> 'double'),
                    'point'=>array('type'=> 'double'),
                    'rank'=>array('type'=> 'double'),
                    'agio'=>array('type'=> 'double'),
                    'attrs'=>array(
                        'properties'=>array(
                            'type'=>array('type'=> 'string','index'=>'not_analyzed'),
                            'brand'=>array('type'=> 'string','index'=>'not_analyzed'),
                        )
                    ),
                    'hits'=>array('type'=> 'long'),
                )
            )
        );
    }

    /*团购热点值计算*/
    private  function getPoint($date,$attendCount){
        $date_point = (strtotime($date) - strtotime(date('2015-01-01'))) / (86400/2);
        $click_point = log($attendCount,2);

        if($click_point <= 0) $click_point =0;
        return round(($date_point+$click_point),2);
    }
}