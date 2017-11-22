<?php
Class kaluliItemSearch{
    CONST _TYPE = 'item';             //表
    CONST _CHANNEL_TYPE = 2;
    private static $fields_compare = array(
        'id'=>'id',
        'pic'=>'imagePath',
        'discount_price'=>'price',
        'price'=>'oldPrice',
        'sell_point'=>'sellPoint',
        'title'=>'title',
        'intro'=>'intro',
        'activity_type'=>'activity.detail.mode',
        'endTime'=>'activity.detail.endTime',
        'startTime'=>'activity.detail.startTime',
        'discount_rate' => 'activity.detail.attr2',
        'storehouse'=>'storehouse',
        'scheme' =>'attrs.scheme',
        'point' =>'point'
    );

    /*代购搜索*/
    public function search($search_data){
        $current_date = date('Y-m-d H:i:s');
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
        if(isset($search_data['hotSort']))
            $data['sort']['hits']['order'] = $search_data['hotSort'];                                                       //热度
        else
            $data['sort']['point']['order'] = 'desc';

        /*条件*/
        $filters = $querys = $facets = $fields =$shoulds= array();
        if(isset($search_data['brand'])){                                                                                    //品牌
            $filters[] = $this->setupFilter($search_data['brand'],'brand');
        }
        if(isset($search_data['price'])){                                                                                   //价格
            $filters[] = $this->setupFilter($search_data['price'],'price');
        }
        if(isset($search_data['scheme'])){                                                                                   //tag功能
            $filters[] = $this->setupFilter($search_data['scheme'],'scheme');
        }
        if(isset($search_data['type'])){                                                                                    //tag类型
            $filters[] = $this->setupFilter($search_data['type'],'type');
        }
        if(isset($search_data['notId'])){                                                                                    //notId
            $filters[] = $this->setupFilter($search_data['notId'],'notId');
        }
        if(isset($search_data['id'])){
            $filters[] = $this->setupFilter($search_data['id'],"id");
        }
        if(isset($search_data['forPeople'])) {
            $filters[] = $this->setupFilter($search_data['forPeople'],"forPeople");
        }

        if(!empty($search_data['keywords'])){
           // $data['query']['bool']['must']   = $this->setupFilter($search_data['keywords'],'keywords');

            $shoulds = $this->setupFilter($search_data['keywords'],'keywords');
        }
        if(isset($search_data['activity'])){
            $tmp = array();
            # 筛选活动ID
            if(is_array($search_data['activity']))
            {
                $tmp = $this->setupFilter($search_data['activity'],'activity');
                if(!empty($tmp))
                {
                    $filters[] = $tmp;
                }
            }
            # 过滤营销活动有效时间
            if(!empty($tmp) || $search_data['activity'] == 'all')
            {
                $time = date('Y-m-d H:i:s',time());
                $filters[] = array(
                    'range'=>array(
                        'activity.detail.startTime'=>array(
                            'lte' => $time,
                        ),
                    )
                );
                $filters[] = array(
                    'range'=>array(
                        'activity.detail.endTime'=>array(
                            'gte' => $time,
                        ),
                    )
                );
            }

        }


        if(isset($search_data['notIds']))                                                                                   //不需要ID
            $querys['bool']['must_not'] = array('terms'=>array('id'=>$search_data['notIds']));

        if(isset($search_data['fields'])){                                                                                 //查询字段
            $fields_key = $search_data['fields'];
            foreach($fields_key as $val){
                $fields[] = self::$fields_compare[$val];
            }
        }else
            $fields = array('id','activity.detail.mode','activity.detail.endTime','activity.detail.startTime','activity.detail.attr2','storehouse','title','attrs.scheme','point');
        /*facet 返回剩余数据类型*/
        if(isset($search_data['facet']))
            $facets = $this->setupFacet($search_data,$filters);

        if($facets)$data['facets'] = $facets;
        if($querys)$data['query'] = $querys;
        if($shoulds)  $data['query']['bool']['should']   = $shoulds;
        $data['post_filter']['and']['filters'] = $filters;
        $data['fields'] = $fields;
        $array['_type'] = self::_TYPE;
        $array['data'] = $data;
        // return $data;
        /*搜索*/
        $es =new kaluliElasticSearch();

        $indexData = $es->search($array);
        $indexData = json_decode($indexData,true);
        /*处理返回数据*/
        $return = $this->checkData($indexData,$fields);
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

        $orders = array('_score'=>'desc','point'=>'desc');

        if($params['notId'])  $data['query']['bool']['must_not'] = array('term'=>array('id'=>$params['notId']));
        $data['query']['bool']['should'] = $terms;
        $data['size'] = $params['num'];
        $data['sort'] = $orders;

        $data['fields'] = array('id','title','imagePath','price','sellPoint');
        $array['_type'] = self::_TYPE;
        $array['data'] = $data;

        #搜索
        $es =new kaluliElasticSearch();
        $indexData = $es->search($array);
        $indexData = json_decode($indexData,true);

        #返回数据
        return $res = $this->checkTagData($indexData);
    }


    #列表页返回数据
    private function checkData($indexData,$fields){

        $result = $return = $types = $schemes = $brands = $prices =$forPeople= array();
        if($indexData['status']){
            $data_hits= isset($indexData['data']['hits']) ? $indexData['data']['hits'] : array();
            $data_facets = isset($indexData['data']['facets']) ? $indexData['data']['facets'] : array();
            if(!empty($data_hits['hits']) && is_array($data_hits['hits'])){
                $fields_compare = array_flip(self::$fields_compare);
                foreach($data_hits['hits'] as $k=>$v) {
                    foreach ($fields as $f_v) {
                        if(!empty($v['fields'][$f_v][0]))$result[$k][$fields_compare[$f_v]] = $v['fields'][$f_v][0];
                    }
                }
            }
            if(!empty($data_facets)){
                if(isset($data_facets['attrsTypeFacet']['terms'])){
                    foreach($data_facets['attrsTypeFacet']['terms'] as $k=>$v){
                        $types[] = $v['term'];
                    }
                }

                if(isset($data_facets['attrsSchemeFacet']['terms'])){
                    foreach($data_facets['attrsSchemeFacet']['terms'] as $k=>$v){
                        $schemes[] = $v['term'];
                    }
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

                if(isset($data_facets['attrsForPeopleFacet']['terms'])){
                    foreach($data_facets['attrsForPeopleFacet']['terms'] as $k=>$v){
                        $forPeople[] = $v['term'];
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
                                $prices[] = '500以上';
                            else
                            {
                                $v['to'] = ($v['to'] -1);

                                $prices[] = $v['from'].'-' .$v['to'];
                            }
                        }
                    }
                }
            }

            $return['status'] = true;
            $return['num'] = $data_hits['total'];
            $return['type'] = $types;
            $return['scheme'] = $schemes;
            $return['brand'] = $brands;
            $return['price'] = $prices;
            $return['forPeople'] = $forPeople;
            $return['result'] = $result;

        }else{
            $return['status'] = false;
        }

        return $return;
    }

    #tag返回数据
    private function checkTagData($indexData){
        if($indexData['status']){
            $result = array();
            $data_hits= isset($indexData['data']['hits']) ? $indexData['data']['hits'] : array();
            if(!empty($data_hits['hits']) && is_array($data_hits['hits'])){
                foreach($data_hits['hits'] as $k=>$v){
                    $result[$k]['id'] =  $v['fields']['id'][0];
                    $result[$k]['title'] =  $v['fields']['title'][0];
                    $result[$k]['pic'] =  $v['fields']['imagePath'][0];
                    $result[$k]['price'] =  $v['fields']['price'][0];
                    $result[$k]['sell_point'] =  $v['fields']['sellPoint'][0];
                }
            }

            $return['status'] = true;
            $return['result'] = $result;

        }else{
            $return['status'] = false;
        }

        return $return;
    }

    #facet 返回剩余数据类型
    private function setupFacet($search_data,$filters){
        $facets = array();

        $typeFacet= $this->getTypeFacet($filters);
        $brandFacet = $this->getBrandFacet($filters);
        $priceFacet = $this->getPriceFacet($filters,$search_data);
        $schemeFacet = $this->getSchemeFacet($filters);
        $activityFacet = $this->getActivityFacet($filters);
        $forPeopleFacet = $this->getForPeopleFacet($filters);
        $facets = array('attrsTypeFacet'=>$typeFacet,'attrsSchemeFacet'=>$schemeFacet,'brandFacet'=>$brandFacet,'priceFacet'=>$priceFacet,'activityFacet'=>$activityFacet,'attrsForPeopleFacet'=>$forPeopleFacet);
        /*
        if(isset($search_data['type']))  unset($facets['attrsTypeFacet']);
        if(isset($search_data['scheme'])) unset($facets['attrsSchemeFacet']);
        if(isset($search_data['price'])) unset($facets['priceFacet']);
        if(isset($search_data['brand'])) unset($facets['brandFacet']);
        */
        return $facets;
    }

    /*post_filter 过滤*/
    private function setupFilter($data,$flag){
        if(in_array($flag,['brand','type','scheme','forPeople',"price"])) {
            $data = explode(",",$data);
        }
        switch($flag){
            /*品牌*/
            case "brand":
                return $term_brand = array(
                    'terms'=>array(
                        'brand'=>$data
                    )
                );
                break;
            /*分类*/
            case "type":
                return $term_brand = array(
                    'terms'=>array(
                        'attrs.type'=>$data
                    )
                );
                break;
            /*功能*/
            case "scheme":
                return $term_brand = array(
                    'terms'=>array(
                        'attrs.scheme'=>$data
                    )
                );
                break;
            /*营销活动ID*/
            case "activity":
                $terms = array();
                foreach($data as $v)
                {
                    if(is_numeric($v))
                    {
                        $terms['bool']['should'][]['term'] = array(
                            'activity.id'=>$v,
                        );
                    }
                }
                return $terms;
                break;

            /*价格区间*/
            case "price":
                    $orArray = array();
                    foreach ($data as $v) {
                        $price_space = explode("-", $v);
                        $range = array('range' => array(
                            'price' => array(
                                'from' => $price_space[0],
                                'to' => $price_space[1],
                                "include_lower" => true,
                                "include_upper" => true
                            )
                        ));
                        $orArray[] = $range;

                    }
                    return $range_price = array(
                        "or" => $orArray

                    );

                break;
            /*关键字*/
            case "keywords":
                return  array(array(
                    'match'=>array(
                        'title'=>array(
                            'query'=>$data,
                            'operator'=>'or',
                        )
                    )
                ),array(
                    'match'=>array(
                        'sellPoint'=>array(
                            'query'=>$data,
                            'operator'=>'or',
                        )
                    )
                ));
                break;
            case "id":
                return $term_brand = array(
                    'term'=>array(
                        'id'=>$data
                    )
                );
                break;
            case "forPeople":
                return $term_brand = array(
                    'terms'=>array(
                        'attrs.forPeople'=>$data
                    )
                );
                break;
        }

    }



    #类型Facet
    private function getTypeFacet($facet_filter){
        return array(
            'terms'=>array(
                "field" => "attrs.type",
                "size" => 100,
                "order" => "count"
            ),
            'facet_filter'=>array(
                'and'=>array(
                    "filters"=>$facet_filter
                )
            ),
        );
    }

    private function getForPeopleFacet($facet_filter){
        return array(
            'terms'=>array(
                "field" => "attrs.forPeople",
                "size" => 100,
                "order" => "count"
            ),
            'facet_filter'=>array(
                'and'=>array(
                    "filters"=>$facet_filter
                )
            ),
        );
    }

    #activity
    private function getActivityFacet($facet_filter){
        return array(
            'terms'=>array(
                "field" => "activity.id",
                "size" => 100,
                "order" => "count"
            ),
            'facet_filter'=>array(
                'and'=>array(
                    "filters"=>$facet_filter
                )
            ),
        );
    }




    #Scheme Facet
    private function getSchemeFacet($facet_filter){
        return array(
            'terms'=>array(
                "field" => "attrs.scheme",
                "size" => 100,
                "order" => "count"
            ),
            'facet_filter'=>array(
                'and'=>array(
                    "filters"=>$facet_filter
                )
            ),
        );
    }

    /*价格Facet*/
    private function getPriceFacet($facet_filter){
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
                        "to" => 400.0
                    ),
                    array(
                        "from"=>400.0,
                        "to" => 500.0
                    ),
                    array(
                        "from"=>500.0,
                        "to" => 10000.0
                    )
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
    private function getBrandFacet($facet_filter){
        return array(
            'terms'=>array(
                "field" => "brand",
                "size" => 100,
                "order" => "count"
            ),
            'facet_filter'=>array(
                'and'=>array(
                    "filters"=>$facet_filter
                )
            ),
        );
    }

    /*重建索引*/
    public  function reindex($id = 1){
        set_time_limit(3600);
        $id = is_numeric($id) ? $id : 1;

        echo str_repeat(" ",1024);      //达到输出限制
        for($i = $id; $i >= 1 ; $i--){
            echo $this->update($i);

            ob_flush();
            flush();
            usleep(50);
        }
    }

    /*索引创建*/
    public function create($id,$close_link = false){
        return $this->update($id,$close_link);
    }


    /*索引更新*/
    public function update($id,$close_link = false){

        $return = array('status'=>'error');
        if(!$id || !is_numeric($id)){
            $return['msg'] = '参数错误';
            return json_encode($return);
        }

        $itemTable = KaluliItemTable::getInstance();
        $itemSkuTable = KaluliItemSkuTable::getInstance();

        $item = $itemTable->findOneBy('id', $id);
        $itemSku = $itemSkuTable->createQuery()->where('item_id = ?',$id)->fetchArray();

        if($item && $item->getStatus() == 3 && $itemSku && $item->getStatusEs()==1){#上架

            $tagsdata = $this->getTag($id);
            $brands  = KaluliItemForm::getBrandsByDictionary();
            $brand = isset($brands[$item->getBrandId($id)]) ? $brands[$item->getBrandId($id)] : '';

            $data = array();
            $data['id'] =  $item->getId();

            $data['title'] =  $item->getTitle();
            $data['channelType'] =  self::_CHANNEL_TYPE;
            $data['imagePath'] =  $item->getPic();
            $data['price'] =  $item->getDiscountPrice();
            $data['oldPrice'] =  $item->getPrice();
            $data['brand'] = $brand;
            $data['point'] =  $this->getPoint($item->getId());
            $data['intro'] =  strip_tags($item->getIntro());
            $data['sellPoint'] =  $item->getSellPoint();
            $data['hits'] = $item->getHits();
            $data['attrs']['scheme'] =  $tagsdata['scheme'];
            $data['attrs']['type'] =  $tagsdata['type'];
            $data['attrs']['forPeople'] = $tagsdata['forPeople'];
            $data['tag'] =  $tagsdata['tags'];
            $data['createTime'] =  $item->getCreatedAt();
            # 营销活动
            $marketingActivity = $this->getMarketingActivityInfo($item->getId());
            if($marketingActivity['status']){
                $data['activity'] =  $marketingActivity['data'];
            }
            # 保税仓库
            $warehouses = array(
                '南沙保税',
                '郑州保税',
                '香港直邮',
                '宁波保税仓'
                
            );
            $tmp = KaluliWarehousesTable::getInstance()->createQuery()->whereIn('note',$warehouses)->fetchArray();
            foreach($tmp as $v)
            {
                $rsWares[$v['id']] = $v['note'];
            }
            $storehouse = '';
            if(!empty($rsWares))
            {
                foreach($itemSku as $sku)
                {
                    if(!empty($rsWares[$sku['storehouse_id']]))
                    {
                        $storehouse = $rsWares[$sku['storehouse_id']];
                    }
                }
            }
            $data['storehouse'] =  $storehouse;

            //增加品牌国家字段
            $brandInfo = KllItemBrandTable::getInstance()->findOneById($item->getBrandId($id));
            if($brandInfo) {
                $data['attrs']['country'] = $brandInfo->getPlace() ? $brandInfo->getPlace() : "中国";
            } else {
                $data['attrs']['country'] = "中国";
            }

            $array = array(
                '_type'=>self::_TYPE,
                '_id'=>$item->getId(),
                'data'=>$data
            );

            $es =new kaluliElasticSearch();
            $put = $es->update($array);

            if($close_link){
//                $itemTable->getConnection()->close();
//                $itemSkuTable->getConnection()->close();
            }

            return  $put;
        }else{
            if($close_link){
//                $itemTable->getConnection()->close();
//                $itemSkuTable->getConnection()->close();
            }

            return $this->delete($id);
        }
    }

    /*索引删除*/
    public  function delete($id){
        $return = array('status'=>'error');
        if(!$id || !is_numeric($id)){
            $return['msg'] = '参数错误';
            return json_encode($return);
        }

        $data = array();
        $array = array(
            '_type'=>self::_TYPE,
            '_id'=>$id,
            'data'=>$data
        );

        $es =new kaluliElasticSearch();
        $put = $es->delete($array);
        return $put;
    }


    /*设置mapping*/
    public  function mapping(){
        $data = array(
            'item'=>array(
                'properties'=>array(
                    'id'=>array('type'=> 'long'),
                    'title'=>array(
                        'index_analyzer'=> 'index_ansj',
                        'search_analyzer'=> 'query_ansj',
                        'type'=> 'string'
                    ),
                    'channelType'=>array('type'=> 'long'),
                    'createTime'=>array('type'=> 'date','format'=> 'yyyy-MM-dd HH:mm:ss'),
                    'intro'=> array(
                        'index_analyzer'=> 'index_ansj',
                        'search_analyzer'=> 'query_ansj',
                        'type'=> 'string'
                    ),
                    'sellPoint'=> array(
                        'index_analyzer'=> 'index_ansj',
                        'search_analyzer'=> 'query_ansj',
                        'type'=> 'string'
                    ),
                    'imagePath'=>array('type'=> 'string'),
                    'tag'=>array('type'=> 'string','index'=>'not_analyzed'),
                    'attrs'=>array(
                        'properties'=>array(
                            'scheme'=>array('type'=> 'string','index'=>'not_analyzed'),
                            'type'=>array('type'=> 'string','index'=>'not_analyzed'),
                            'country'=>array('type'=>'string','index'=>'not_analyzed'),
                            'forPeople'=>array('type'=>'string','index'=>'not_analyzed')
                        )
                    ),
                    'brand'=>array('type'=> 'string','index'=>'not_analyzed'),
                    'price'=>array('type'=> 'double'),
                    'hits'=>array('type'=> 'double'),
                    'point'=>array('type'=> 'double'),
                    'oldPoint'=>array('type'=> 'double'),
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
                    'storehouse'=>array('type'=> 'string','index'=>'not_analyzed'),
                )
            )
        );

        $array = array(
            '_type'=>self::_TYPE,
            'data'=>$data
        );

        $es =new kaluliElasticSearch();
        $shoe_put = $es->mapping($array);
        return $shoe_put;
    }

    /*删除mapping*/
    public function deleteMapping(){
        $array = array(
            '_type'=>self::_TYPE,
        );
        $es =new kaluliElasticSearch();
        $shoe_put = $es->deleteMapping($array);
        return $shoe_put;
    }

    /*设置索引*/
    public  function  index(){
        $data = array();

        $array = array(
            '_type'=>self::_TYPE,
            'data'=>$data
        );

        $es =new kaluliElasticSearch();                                               //更新索引
        $put = $es->index($array);
        return $put;
    }


    #获取类目名
    private function getCategoryName($id){
        if(!$id || !is_numeric($id)) return '';

        $categoryTable = KaluliCategoryTable::getInstance();

        $category = $categoryTable->find($id);
        $categoryName = $category->getName();

//        $categoryTable->getConnection()->close();   #关闭连接

        return $categoryName;
    }

    #获取tag
    private function getTag($id){
        $sql = 'SELECT t.name,t.type FROM kll_tags_relate tr LEFT JOIN kll_tags t ON tr.tag_id = t.id  WHERE tr.type = 1 AND `pid` = ?';
        $conn = Doctrine_Manager::getInstance()->getConnection('kaluli');
        $st = $conn->execute($sql, array($id));
        $res = $st->fetchAll(Doctrine_Core::FETCH_ASSOC);
//        $conn->close();

        $tagsArr = $schemeArr = $typeArr=$forPeopleArr = array();
        foreach($res as $k=>$v){
            $tagsArr[] = $v['name'];

            if($v['type'] == 1)
                $schemeArr[] = $v['name'];
            else if($v['type'] == 2)
                $typeArr[] = $v['name'];
            else if($v['type'] == 3)
                $forPeopleArr[] = $v['name'];
        }

        $data = array(
            'tags'=>$tagsArr,
            'scheme'=>$schemeArr,
            'type'=>$typeArr,
            'forPeople'=>$forPeopleArr
        );

        return $data;
    }

    /*热点值计算
    调整分值计算规则
    商品权重=标签权重×1000+品牌权重
    */
    private  function getPoint($id){
        $relates = KaluliTagsRelateTable::getInstance()->createQuery()->andWhere("type = ?",1)->andWhere("pid = ?",$id)->fetchArray();
        if(empty($relates)) {
            return 0;
        }
        foreach($relates as $v) {
            $tags[] = $v['tag_id'];
        }
        $point = KaluliTagsTable::getInstance()->createQuery()->select("max(weight) as point")->whereIn("id",$tags)->andWhere("type =?",2)->fetchOne();
        if(empty($point->point)) {
            return 0;
        }
        $item = KaluliItemTable::getItemById($id);
        $brandId = $item['brand_id'];
        //产品品牌表的分数
        $brand = KllItemBrandTable::getInstance()->findOneById($brandId);
        if(empty($brand)){
            return $point->point;
        }
        $tagPoint = $point->point;
        $brandPoint = $brand->getWeight();
        $totalPoint = $tagPoint*1000 + $brandPoint;
        return $totalPoint;  //根据最大的权重返回分值
    }


    /*
   *获取活动信息
   **/
    private function getMarketingActivityInfo($id){
        $kllMarketingGroupTable = KllMarketingActivityGroupTable::getInstance();
        $kllMarketingGroup = $kllMarketingGroupTable
            ->createQuery(Doctrine_Manager::getInstance()->getConnection('kaluli'))
            ->where('item_id = ?',$id)
            ->execute()
            ->toArray();

        $return = array('status' => false);

        if($kllMarketingGroup){
            $time = time();

            $sql = "SELECT ma.* FROM kll_marketing_activity_group mag LEFT JOIN
kll_marketing_activity ma ON ma.id = mag.activity_id  WHERE mag.item_id = ? AND ma.status=3 AND  mag.etime > {$time} ";
            $conn = Doctrine_Manager::getInstance()->getConnection('kaluli');
            $st = $conn->execute($sql, array($id));
            $marketingActivity = $st->fetchAll(Doctrine_Core::FETCH_ASSOC);

            if($marketingActivity){
                $return['status'] = true;

                foreach($marketingActivity as $marketingActivity_val){
                    $return['data'][] = array(
                        'id'=> $marketingActivity_val['id'],
                        'name'=>$marketingActivity_val['title'],
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
}













