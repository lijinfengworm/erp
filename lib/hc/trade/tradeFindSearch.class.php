<?php
Class findSearch extends tradeESBaseSearch{
    public   $_type = 'find';
    /*
    * 运动鞋列表搜索
    */
    public function search($search_data){
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
        if(isset($search_data['priceSort']))
            $data['sort']['price']['order'] = $search_data['priceSort'];                                  //价格
        else if(isset($search_data['hotSort']))
            $data['sort']['point']['order'] = $search_data['hotSort'];                                    //热度
        else
            $data['sort']['createTime']['order'] = 'desc';

        /*条件*/
        $filters = $querys = $facets = array();
        $filters[] = $this->setupFilter(5,'channelType');
        if(!empty($search_data['brand'])){                                                                                                   //品牌
            $filters[] = $this->setupFilter($search_data['brand'], 'brand');
        }
        if(!empty($search_data['type'])){                                                                                                    //类型
            $filters[] = $this->setupFilter($search_data['type'], 'type');
        }
        if(!empty($search_data['price'])){                                                                                                   //价格
            $filters[] = $this->setupFilter($search_data['price'], 'price');
        }
        if(!empty($search_data['sex'])){                                                                                                     //性别
            $filters[] = $this->setupFilter($search_data['sex'], 'sex');
        }
        if(!empty($search_data['tag'])){                                                                                                     //tag
            $filters[] = $this->setupFilter($search_data['tag'], 'tag');
        }
        if(!empty($search_data['rootId'])){                                                                                                 //rootid
            $filters[] = $this->setupFilter($search_data['rootId'], 'rootId');
        }
        if(!empty($search_data['childId'])){                                                                                                //childrenid
            $filters[] = $this->setupFilter($search_data['childId'], 'childId');
        }
        if(!empty($search_data['time'])){                                                                                                //childrenid
            $filters[] = $this->setupFilter($search_data['time'], 'time');
        }
        if(!empty($search_data['keywords'])){
            $querys = $this->setupFilter($search_data['keywords'], 'keywords');
        }

        /*facet 返回剩余数据类型*/
        if(!isset($search_data['facet']) || $search_data['facet']){
            $facets = $this->setupFacet($search_data,$filters);
        }

        if($facets) $data['facets'] = $facets;
        if($querys) $data['query'] = $querys;
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

    /*
    *按 tag 搜索
    **/
    public function searchByTag($params){
        $array = $data = $terms = $orders = array();
        //排序
        $data['sort'] =array(
            '_score'=>'desc',
            'point'=>'desc'
        );
        //size
        $data['size'] = !empty($params['num']) ? $params['num'] : 10 ;
        //tag
        if(!empty($params['oTag'])){
            foreach($params['oTag'] as $tag){
                $terms[] = array(
                    'term'=> array( 'tag' => $tag )
                );
            }
        }
        //过滤 id
        if(!empty($params['id'])){
            $data[ 'query' ][ 'bool' ][ 'must_not' ] = array(
                    'term' =>array(
                        'id' => $params['id']
                )
            );
        }

        $data['query']['bool']['should'] = $terms;
        $data['fields'] = array('id');
        $array['_type'] = $this->_type;
        $array['data'] = $data;

        //搜索
        $es =new tradeElasticSearch();
        $indexData = $es->search($array);
        $indexData = json_decode($indexData, true);

        //处理返回数据
        return $return = $this->checkData($indexData);
    }

    /*post_filter 过滤*/
    private function setupFilter($data,$flag){
        switch($flag){
            /*品牌*/
            case "channelType":
                return array(
                    'term'=>array(
                        'channelType'=>$data
                    )
                );
                break;
            /*品牌*/
            case "brand":
                return array(
                    'term'=>array(
                        'attrs.brand'=>$data
                    )
                );
                break;
            /*类型*/
            case "type":
                return  array(
                    'term'=>array(
                        'attrs.type'=>$data
                    )
                );
                break;
            /*性别*/
            case "sex":
                return array(
                    'term'=>array(
                        'attrs.sex'=>$data
                    )
                );
                break;
            /*tag*/
            case "tag":
                return array(
                    'term'=>array(
                        'tag'=>$data
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
            /*时间*/
            case "time":
                return  array(
                    'range'=>array(
                        'createTime'=>array(
                            'from'=>$data,
                            'to'=>NULL,
                            "include_lower" => true,
                            "include_upper" => true
                        )
                    )
                );
                break;
            /*一级分类*/
            case "rootId":
                return  array(
                    'term'=>array(
                        'rootId'=>$data
                    )
                );
                break;
            /*二级分类*/
            case "childId":
                return array(
                    'term'=>array(
                        'childId'=>$data
                    )
                );
                break;
            /*关键字*/
            case "keywords":
                return array(
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
    private function setupFacet($search_data,$filters){
        $facets = array();

        $typeFacet= $this->getTypeFacet($filters);
        $brandFacet = $this->getBrandFacet($filters);
        $priceFacet = $this->getPriceFacet($filters,$search_data);
        $tagFacet = $this->getTagFacet($filters);
        $sexFacet = $this->getSexFacet($filters);
        $facets = array('attrsTypeFacet'=>$typeFacet,'attrsSexFacet'=>$sexFacet,'attrsBrandFacet'=>$brandFacet,'priceFacet'=>$priceFacet,'tagFacet'=>$tagFacet);
        if(isset($search_data['type']))  unset($facets['attrsTypeFacet']);
        if(isset($search_data['brand'])) unset($facets['attrsBrandFacet']);
        if(isset($search_data['price'])) unset($facets['priceFacet']);
        if(isset($search_data['sex'])) unset($facets['attrsSexFacet']);
        if(isset($search_data['tag'])) unset($facets['tagFacet']);

        return $facets;
    }

    /*处理返回数据*/
    private function checkData($indexData){
        $result = $return = $types = $brands = $prices = $sexs = $tags = array();
        if($indexData['status']){
            $data_hits= isset($indexData['data']['hits']) ? $indexData['data']['hits'] : array();
            $data_facets = isset($indexData['data']['facets']) ? $indexData['data']['facets'] : array();
            if(!empty($data_hits['hits']) && is_array($data_hits['hits'])){
                foreach($data_hits['hits'] as $k=>$v){
                    $result[]['id'] =  $v['fields']['id'][0];
                }
            }

            if(!empty($data_facets)){
                if(isset($data_facets['attrsTypeFacet']['terms'])){
                    foreach($data_facets['attrsTypeFacet']['terms'] as $k=>$v){
                        $types[] = $v['term'];
                    }
                }

                if(isset($data_facets['attrsBrandFacet']['terms'])){
                    foreach($data_facets['attrsBrandFacet']['terms'] as $k=>$v){
                        $brands[] = $v['term'];
                    }
                }

                if(isset($data_facets['attrsSexFacet']['terms'])){
                    foreach($data_facets['attrsSexFacet']['terms'] as $k=>$v){
                        $sexs[] = $v['term'];
                    }
                }

                if(isset($data_facets['tagFacet']['terms'])){
                    foreach($data_facets['tagFacet']['terms'] as $k=>$v){
                        $tags[] = $v['term'];
                    }
                }

                if(isset($data_facets['priceFacet'])){
                    foreach($data_facets['priceFacet']['ranges'] as $k=>$v){
                        if($v['count'] > 0 ){
                                $v['to'] = $v['to'] -1;
                                $prices[] = $v['from'].'-' .$v['to'];
                        }
                    }
                }
            }

            $return['status'] = true;
            $return['num']   = $data_hits['total'];
            $return['type']  = $types;
            $return['brand'] = $brands;
            $return['price'] = $prices;
            $return['sex'] = $sexs;
            $return['tag'] = $tags;
            $return['result'] = $result;

        }else{
            $return['status'] = false;
        }

        return $return;
    }

    /*价格Facet*/
    private function getPriceFacet($facet_filter,$search_data){
        $priceRanges = array(
            1 => array(
                array("from"=>0.0, "to" => 500.0), array("from"=>500.0, "to" => 700.0), array("from"=>700.0, "to" => 900.0), array("from"=>900.0, "to" => 1300.0), array("from"=>1300.0, "to" => 1800.0), array("from"=>1800.0, "to" => 2000.0),
            ),
            2 => array(
                array("from"=>0.0, "to" => 300.0), array("from"=>300.0, "to" => 400.0), array("from"=>400.0, "to" => 500.0), array("from"=>500.0, "to" => 700.0), array("from"=>700.0, "to" => 1000.0), array("from"=>1000.0, "to" => 2000.0),
            ),
            3 => array(
                array("from"=>0.0, "to" => 300.0), array("from"=>300.0, "to" => 400.0), array("from"=>400.0, "to" => 500.0), array("from"=>500.0, "to" => 700.0), array("from"=>700.0, "to" => 1000.0), array("from"=>1000.0, "to" => 1200.0), array("from"=>1200.0, "to" => 1900.0)
            )
        );

        $brandType = array(
            1=>array('AJ'),
            2=>array('耐克' ,'新百伦', '三叶草', '锐步' ,'美津浓' , '彪马', '万斯' , '斐乐' , '匡威'),
            3=>array('李宁', '匹克' , '安踏', '其它')
        );


        if(isset($search_data['brand'])){
            $index = 2;
            foreach($brandType as $k=>$v){
                if(array_search($search_data['brand'],$v) !== false){
                    $index = $k;
                    break;
                }
            }

            $rangs = $priceRanges[$index];
        }else{
            $rangs = $priceRanges[2];
        }

        return array(
            'range'=>array(
                "field" => "price",
                "ranges" =>$rangs
            ),
            'facet_filter'=>array(
                'and'=>array(
                    "filters"=>$facet_filter
                )
            ),
        );
    }

    /*品牌Facet*/
    private function getBrandFacet($facet_filter){
        return array(
            'terms'=>array(
                "field" => "attrs.brand",
                "size" => 15,
                "order" => 'count',
            ),
        );
    }


    /*sexFacet*/
    private function getSexFacet($facet_filter){
        return array(
            'terms'=>array(
                "field" => "attrs.sex",
                "size" => 2,
                "order" => "count"
            ),
            'facet_filter'=>array(
                'and'=>array(
                    "filters"=>$facet_filter
                )
            ),
        );
    }


    /*tagFacet*/
    private function getTagFacet($facet_filter){
        return array(
            'terms'=>array(
                "field" => "tag",
                "size" => 15,
                "order" => "count"
            ),
            'facet_filter'=>array(
                'and'=>array(
                    "filters"=>$facet_filter
                )
            ),
        );
    }


    /*类型Facet*/
    private function getTypeFacet($facet_filter){
        return array(
            'terms'=>array(
                "field" => "attrs.type",
                "size" => 12,
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
        $table = TrdItemallTable::getInstance();
        $tableData = $table
            ->createQuery($this->getLink())
            ->whereIn('id', $ids)
            ->fetchArray();
        $updateData = array();
        foreach($tableData as $tableDataVal) {
            if($tableDataVal
                && $tableDataVal["status"] == 0
                && $tableDataVal["is_hide"] == 0
                && $tableDataVal["is_soldout"] == 0
                && $tableDataVal["title"]
                && $tableDataVal["publish_date"]
            ){
                $channelType = $tableDataVal["children_id"]  == 8 ? 5 : 4;    //发现好货4，运动鞋5
                $root_name = $this->getMenuName($tableDataVal["root_id"]);
                $children_name = $this->getMenuName($tableDataVal["children_id"]);
                $tags = $tableDataVal["tag_collect"] ? explode(',',$tableDataVal["tag_collect"]) : array();
                $json = '';
                if ($tableDataVal["attr_collect"]){
                    $attr = explode(',', $tableDataVal["attr_collect"]);
                    foreach ($attr as $k=>$v){
                        $attr_other = explode('-',$v);
                        $group_id = ltrim($attr_other[0],'G');
                        $attr_id = ltrim($attr_other[1],'A');
                        $attr_key = '';
                        if ($group_id == 1){
                            $attr_key = 'brand';
                        } else if ($group_id == 2){
                            $attr_key = 'type';
                        } else if ($group_id == 3){
                            $attr_key = 'sex';
                        }

                        if($attr_key){
                            $name = $this->getAttrName($attr_id);
                            $json["$attr_key"] = $name->getName();
                        }
                    }
                }

                $data = array();
                $data['id'] =  $tableDataVal["id"];
                $data['title'] = $tableDataVal["title"];
                $data['channelType'] = $channelType;
                $data['createTime'] =  date('Y-m-d H:i:s',$tableDataVal["publish_date"]);
                $data['showSports'] =  $tableDataVal["is_showsports"];
                $data['childId'] =  $tableDataVal["children_id"];
                $data['rootId'] =  $tableDataVal["root_id"];
                $data['tag'] =  $tags;
                $data['childName'] =  $children_name['name'];
                $data['rootName'] =  $root_name['name'];
                if(isset($json['brand'])) $data['attrs'] ['brand'] = $json['brand'];
                if(isset($json['type']))  $data['attrs'] ['type'] = $json['type'];
                if(isset($json['sex']))   $data['attrs'] ['sex'] = $json['sex'];
                $data['price'] =  (float)$tableDataVal["price"];
                $data['point'] =  $this->getPoint($tableDataVal['publish_date'], $tableDataVal['click_count']);
                $data['hits'] = $tableDataVal['click_count'];

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
                    'showSports'=>array('type'=> 'long'),
                    'channelType'=>array('type'=> 'long'),
                    'createTime'=>array('type'=> 'date','format'=> 'yyyy-MM-dd HH:mm:ss'),
                    'childId'=>array('type'=> 'long'),
                    'rootId'=>array('type'=> 'long'),
                    'childName'=>array('type'=> 'string','index'=>'not_analyzed'),
                    'rootName'=>array('type'=> 'string','index'=>'not_analyzed'),
                    'tag'=>array('type'=> 'string','index'=>'not_analyzed'),
                    'price'=>array('type'=> 'double'),
                    'point'=>array('type'=> 'double'),
                    'attrs'=>array(
                        'properties'=>array(
                            'sex'=>array('type'=> 'string','index'=>'not_analyzed'),
                            'type'=>array('type'=> 'string','index'=>'not_analyzed'),
                            'brand'=>array('type'=> 'string','index'=>'not_analyzed'),
                        )
                    ),
                    'hits'=>array('type'=> 'long'),
                )
            )
        );
    }


    /*发现好货热点值计算*/
    private  function getPoint($date,$attendCount){
        $date_point = ($date - strtotime(date('2015-01-01'))) / (86400/2);
        $click_point = log($attendCount,3);

        if($click_point <= 0) $click_point = 0;
        return round(($date_point+$click_point),2);
    }

    /**
     *
     * 获取菜单名称（为了不影响后台，在此写获取菜单名称的方法，防止因为后台没有配置缓存而报错）
     */
    private function getMenuName($menu_id = 0, $root_id = 0) {
        if (empty($menu_id) && empty($root_id))
            return false;

        if (!empty($menu_id)) {
            $result = Doctrine_Query::create($this->getLink())
                ->setResultCacheLifeSpan(60 * 60 * 2)
                ->useResultCache()
                ->select('t.id, t.name')
                ->from('TrdMenu t')
                ->where('t.id = ?', $menu_id)
                ->fetchOne();
        } else {
            $result = Doctrine_Query::create($this->getLink())
                ->setResultCacheLifeSpan(60 * 60 * 2)
                ->useResultCache()
                ->select('t.id, t.name')
                ->from('TrdMenu t')
                ->where('t.root_id = ?', $root_id)
                ->andWhere('t.level = ?', 1)
                ->fetchArray();
        }

        return $result;
    }

    /**
     *
     * 获取属性名称（为了不影响后台，在此写获取属性名称的方法，防止因为后台没有配置缓存而报错）
     */
    private function getAttrName($attr_id) {
        if (empty($attr_id))
            return false;

        $result = Doctrine_Query::create($this->getLink())
            ->setResultCacheLifeSpan(60 * 60 * 2)
            ->useResultCache()
            ->select('t.id, t.name')
            ->from('TrdAttribute t')
            ->where('t.id = ?', $attr_id)
            ->fetchOne();

        return $result;
    }
}













