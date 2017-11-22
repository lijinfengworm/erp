<?php
Class goodsSearch extends tradeESBaseSearch{
    public   $_type = 'goods';
    public   $_channel_type = 10;   //频道类型为10
    public  $fields = array(
            'id','name','rootBrand','type','rootCategory','goodsId','pic','fromType','createTime','status','price'
    );


    /*列表搜索*/
    public function search($search_data){
        $search_data = array_filter($search_data);
        if(isset($search_data['pageSize']))       //分页
            $data['size'] = (int)$search_data['pageSize'];
        else
            $data['size'] = 20;
        if(isset($search_data['pageNo']))
            $data['from'] = ((int)$search_data['pageNo']) * $search_data['pageSize'];
        else
            $data['from'] = 0;

        /*条件*/
        if(isset($search_data['code']) && $search_data['code']){
            $query[] = $this->setupFilter($search_data['code'],'code');
        }
        if(isset($search_data['rootBrand'])){
            $query[] = $this->setupFilter($search_data['rootBrand'],'rootBrand');
        }
        if(isset($search_data['childBrand'])){
            $query[] = $this->setupFilter($search_data['childBrand'],'childBrand');
        }
        if(isset($search_data['rootCategory'])){
            $query[] = $this->setupFilter($search_data['rootCategory'],'rootCategory');
        }
        if(isset($search_data['childCategory'])){
            $query[] = $this->setupFilter($search_data['childCategory'],'childCategory');
        }
        if(isset($search_data['type'])){
            $query[] = $this->setupFilter($search_data['type'],'type');
        }

        if(isset($search_data['price']) && $search_data['price']){
            $tmp = $this->setupFilter($search_data['price'],'price');
            if(!empty($tmp)) $query[] = $tmp;
        }

        if(isset($search_data['name'])){
            $data['query']['bool']['must'][]['multi_match'] = $this->setupFilter($search_data['name'],'name');
        }


        # 分组标签查询
        if(isset($search_data['groups']) && is_array($search_data['groups']))
        {
            foreach($search_data['groups'] as $k=>$v)
            {
                $data['query']['bool']['must'][]['nested'] = array(
                    'path'=>'tags',
                    'query'=> array(
                        'bool'=>array(
                            'must'=>array(
                                array('match'=>array( 'tags.name'=>$k)) ,
                                array('match'=>array( 'tags.detail'=>$v)) ,
                            ),
                        ),

                    ),
                );
            }
        }

        # 后台
        if(empty($search_data['backend']))
        {
            $query[] = array(
                'term' => array(
                    'isDefault' => 1,
                )
            );
        }
        else
        {
            # 前台
            $query[] = array(
                'term' => array(
                    'status' => 1,
                )
            );
        }


        # 后台
        if(!empty($search_data['backend']))
        {
            $query[] = $this->setupFilter(1,'isDefault');
        }
        else
        {
            # 前台
            $query[] = array(
                'term' => array(
                    'status' => 1,
                )
            );
            if(!empty($search_data['isDefault'])) $query[] = $this->setupFilter(1,'isDefault');
        }
        # 完善列表筛选
        if(!empty($search_data['check']))
        {
            $query[] = array(
               'range' => array(
                   'fromType' => array(
                       'gt'=>0,
                   ),
               )
            );
        }
        if(!empty($query))
        {
            $data['query']['bool']['must'][] = $query;
        }

        # 排除id
        if(isset($search_data['notId']))
        {
            $data['query']['bool']['must_not'][] = array(
                'term' => array(
                    'goodsId' => $search_data['notId'],
                )
            );

        }

        if(isset($search_data['hits']))
        {
            # 人气排序
            $data['sort']['hits']['order'] = 'desc';
        }
        else
        {
            # 时间排序
            $data['sort']['createTime']['order'] = 'desc';
        }

        if(isset($search_data['priceSort']) && in_array($search_data['priceSort'],array('desc','asc')))
        {
            # 价格排序
            $data['sort']['price']['order'] = $search_data['priceSort'];
        }

        # 返回聚合菜单
        if(isset($search_data['aggs']))
        {
            $data['aggs'] = $this->setupAggs();
        }


        $data['fields'] = $this->fields;
        $array['_type'] = $this->_type;
        $array['data'] = $data;

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
            /*货号*/
            case "code":
                return array(
                    'term' => array(
                        'code' => $data
                    )
                );
                break;
            /*品牌*/
            case "rootBrand":
                $filterType = is_array($data)?'terms':'term';
                return array(
                    $filterType => array(
                        'rootBrand' => $data
                    )
                );
                break;
            /*系列*/
            case "childBrand":
                $filterType = is_array($data)?'terms':'term';
                return array(
                    $filterType => array(
                        'childBrand' => $data
                    )
                );
                break;
            /*一级类目*/
            case "rootCategory":
                $filterType = is_array($data)?'terms':'term';
                return array(
                    $filterType => array(
                        'rootCategory' => $data
                    )
                );
                break;
            /*品牌*/
            case "childCategory":
                $filterType = is_array($data)?'terms':'term';
                return array(
                    $filterType => array(
                        'childCategory' => $data
                    )
                );
                break;
            /*类型*/
            case "type":
                $filterType = is_array($data)?'terms':'term';
                return array(
                    $filterType => array(
                        'type' => $data
                    )
                );
                break; 
            /*只显示默认款式*/
            case "isDefault":
                return array(
                    'term' => array(
                        'isDefault' => 1
                    )
                );
                break;
            /*价格区间*/
            case "price":
                $tmp = array();
                if(is_array($data))
                {
                    if(!empty($data['from']))
                    {
                        $tmp['range']['price']['gte'] = $data['from'];
                    }
                    if(!empty($data['to']))
                    {
                        $tmp['range']['price']['lt'] = $data['to'];
                    }
                }

                return $tmp;
                break;
            /*关键字*/
            case "name":
                return  array(
                    'query'=>$data,
                    'type'=>'best_fields',
                    'fields'=>array('name','code'),
                    'operator'=>'and',
                );
                break;
        }
    }

    # 返回菜单结构
    private function setupAggs(){
        # 聚合返回菜单
        $data['prices']['range'] = array(
            'field'=>'id',
            'ranges'=>array(
                array(
                    'from'=>0,
                    'to'=>100,
                ),
                array(
                    'from'=>100,
                    'to'=>200,
                ),
                array(
                    'from'=>200,
                ),
            ),
        );
        $data['brands']['terms'] = array(
            'field'=>'rootBrand',
        );

        $data['childCategory']['terms'] = array(
            'field'=>'childCategory',
        );

        $data['group']=array(
            'nested'=>array(
                'path'=>'tags',
            ),
            'aggs'=>array(
                'gNames'=>array(
                    'terms'=>array(
                        'field'=>'tags.name',
                    ),
                    'aggs'=>array(
                        'gTags'=>array(
                            'terms'=>array(
                                'field'=>'tags.detail',
                            ),
                        ),
                    ),
                ),
            ),
        );

        return $data;
    }

    /*处理返回数据*/
    private function checkData($indexData){
        $result = $return = $types = $brands = $prices = $menu = array();
        if($indexData['status']){
            $data_hits= isset($indexData['data']['hits']) ? $indexData['data']['hits'] : array();
            if(!empty($data_hits['hits']) && is_array($data_hits['hits'])){
                foreach($data_hits['hits'] as $k=>$v){
                    foreach($this->fields as $v2)
                    {
                        if($v2 == 'type')
                        {
                            $tmp[$v2] = $v['fields'][$v2];
                        }else{
                            $tmp[$v2] = $v['fields'][$v2][0];
                        }

                    }
                    $result[] = $tmp;
                }
            }
            # 整理菜单数据
            $data_aggregations = isset($indexData['data']['aggregations']) ? $indexData['data']['aggregations'] : array();
            if(!empty($data_aggregations) && is_array($data_aggregations)){
                if(!empty($data_aggregations['brands']['buckets'])){
                    foreach($data_aggregations['brands']['buckets'] as $v)
                    {
                        $menu['brands'][] = $v['key'];
                    }
                    $menu['brands'] = array_filter($menu['brands']);
                }

                if(!empty($data_aggregations['prices']['buckets'])){
                    foreach($data_aggregations['prices']['buckets'] as $v)
                    {
                        if(isset($v['from']) && isset($v['to']))
                        {
                            $menu['prices'][] = "{$v['from']}-{$v['to']}";
                        }
                        elseif(isset($v['from']))
                        {
                            $menu['prices'][] = "{$v['from']}以上";
                        }
                        elseif(isset($v['to']))
                        {
                            $menu['prices'][] = "{$v['to']}以下";
                        }
                    }
                    $menu['prices'] = array_filter($menu['prices']);
                }

                # 分组
                if(!empty($data_aggregations['group']['gNames'])){
                    foreach($data_aggregations['group']['gNames']['buckets'] as $v)
                    {
                        $tags = array();
                        foreach($v['gTags']['buckets'] as $v2)
                        {
                            $tags[] = $v2['key'];
                        }
                        $menu['group'][$v['key']] = $tags;
                    }
                    if(!empty($menu['group']))$menu['group'] = array_filter($menu['group']);
                }
            }
            $return['status'] = true;
            $return['num'] = $data_hits['total'];
            $return['result'] = $result;

            if(!empty($menu))
            {
                $return['meun'] = $menu;
            }
        }else{
            $return['status'] = false;
        }

        return $return;
    }

    //索引创建
    public function create($id,$styleId=0){
        return $this->update($id,$styleId=0);
    }

    //索引更新
    public function update($id,$styleId=0){
        if(!$id || !is_numeric($id)){
            return $this->_return(false, '参数错误');
        }
        $array = array();
        $es = new tradeElasticSearch();
        if($data = $this->_updateData($id,$styleId=0)){
            # 若是修改商品属性，则批量修改
            if(empty($styleId))
            {
                foreach($data as $v) {
                    $array[] = array(
                        'index'=>array(
                            '_id'=>$v['id'],)
                    );
                    $array[] = $v;
                }

                $newData = array(
                    '_type'=> $this->_type,
                    'data' => $array
                );
                $r = $es->bulk($newData);
            }
            else
            {
                $array = array(
                    '_type'=> $this->_type,
                    '_id'  => $id,
                    'data' => $data[0]
                );
                $r = $es->update($array);
            }
            return $r;
        }else{
            $styles = TrdGoodsStyleTable::getInstance()->createQuery()->andWhere('goods_id =?',$id)->fetchArray();
            foreach($styles as $v)
            {
                $array[] = array(
                    'delete'=>array(
                        '_id'=>$v['id'],)
                );
                $array[] = $v;
            }
            $newData = array(
                '_type'=> $this->_type,
                'data' => $array
            );
            $r = $es->bulk($newData);
            return $r;
        }
    }


    //索引删除
    public  function delete($id,$styleId=0){
        if(!$id || !is_numeric($id)){
            return $this->_return(false, '参数错误');
        }
        $ins = TrdGoodsStyleTable::getInstance()->createQuery();
        $ins->andWhere('goods_id =?',$id);
        if(!empty($styleId))
        {
            $ins->andWhere('id =?',$styleId);
        }
        $styles = $ins->fetchArray();
        foreach($styles as $v)
        {
            $array[] = array(
                'delete'=>array(
                    '_id'=>$v['id'],)
            );
          //  $array[] = $v;
        }
        $newData = array(
            '_type'=> $this->_type,
            'data' => $array
        );
        $es =new tradeElasticSearch();
        return $es->bulk($newData);
    }

    //update data
    public function _updateData($id,$styleId=0){
        $updateData = array();
        $goods = TrdGoodsTable::getInstance()->find($id);

        if(!empty($goods))
        {
            $table = TrdGoodsStyleTable::getInstance()->createQuery($this->getLink());
            $table->andWhere('goods_id =?',$id);
            if(!empty($styleId))
            {
                $table->andWhere('id =?',$styleId);
            }
            $tableData = $table->fetchArray();
            if(!empty($tableData))
            {
                # 获取标签
                $tags = array();
                $connection = Doctrine_Manager::getInstance()->getConnection('trade');
                $query = "SELECT a.group_id as groupId,b.name as groupName,c.name as tagName from trd_goods_tag_relation as a join trd_goods_tag_group as b on a.group_id=b.id join trd_goods_tag as c on a.tag_id=c.id where a.goods_id={$goods->id}";
                $statement = $connection->execute($query);
                $statement->execute();
                $resultset = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach($resultset as $v)
                {
                    $groups[$v['groupId']] = $v['groupName'];
                    $groupsTags[$v['groupId']][] = $v['tagName'];
                }
                if(!empty($groups))
                {
                    foreach($groups as $k=>$v)
                    {
                        $tags[] = array(
                            'groupId'=>$k,
                            'name'=>$v,
                            'detail'=>$groupsTags[$k],
                        );
                    }
                }

                # 获取价格
                $price = TrdGoodsSupplierTable::getMinPrice($goods['id']);
                # 获取品牌系列和分类
                $root_brand = TrdGoodsBrandTable::getInstance()->find($goods['root_brand_id']);
                $root_brand = $root_brand->name;
                $child_brand = TrdGoodsBrandTable::getInstance()->find($goods['child_brand_id']);
                $child_brand = $child_brand->name;
                $root_category = TrdGoodsCategoryTable::getInstance()->find($goods['root_category_id']);
                $root_category = $root_category->name;
                $child_category = TrdGoodsCategoryTable::getInstance()->find($goods['child_category_id']);
                $child_category = $child_category->name;
                # 获取频道
                $channelTypes = sfConfig::get('app_shihuo_elasticsearch_channel_types');
                $channelType = $channelTypes[$this->_type];
                # 获取货号
                if(!empty($goods['code']))
                {
                    $codes = explode(',',$goods['code']);
                }
                else
                {
                    $codes = array();
                }

                if(!empty($goods['type']))
                {
                    $types = explode(',',$goods['type']);
                    foreach($types as $k=>$v)
                    {
                        if(!empty(TrdGoodsForm::$type[$v]))
                        {
                            $types[$k] = TrdGoodsForm::$type[$v];
                        }
                        else
                        {
                            unset($types[$k]);
                        }
                    }
                }
                else
                {
                    $types = array();
                }
                foreach($tableData as $tableDataVal)
                {
                    $data = array();
                    $data['id'] = $tableDataVal["id"];
                    $data['name'] = $goods["name"];
                    $data['channelType'] = $channelType;
                    $data['goodsId']  = $goods["id"];
                    $data['code'] = $codes;
                    $data['pic'] = $tableDataVal["pic"];
                    $data['price'] = $price;
                    $data['tags'] = $tags;
                    $data['rootBrand'] = !empty($root_brand)?$root_brand:'';
                    $data['childBrand']  = !empty($child_brand)?$child_brand:'';
                    $data['rootCategory'] = !empty($root_category)?$root_category:'';
                    $data['childCategory'] = !empty($child_category)?$child_category:'';
                    $data['isDefault'] = $tableDataVal['is_default'];
                    $data['createTime'] = $goods['created_at'];
                    $data['hits'] = $tableDataVal['hits'];
                    $data['status'] = $goods["status"];
                    $data['fromType'] = $goods["from_type"];
                    $data['fromId'] = $goods["from_id"];
                    $data['type'] = $types;
                    $updateData[] = $data;
                }

            }
        }

        return $updateData;
    }

    //mapping data
    public function _mappingData(){
        return array(
            'goods'=>array(
                'properties'=>array(
                    'id'=>array('type'=> 'long'),
                    'goodsId'=>array('type'=> 'long'),
                    'name'=>array(
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
                    'code'=>array(
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
                    'pic'=>array('type'=> 'string','index'=>'not_analyzed'),
                    'price'=>array('type'=> 'double'),
                    'rootBrand'=>array('type'=> 'string','index'=>'not_analyzed'),
                    'childBrand'=>array('type'=> 'string','index'=>'not_analyzed'),
                    'rootCategory'=>array('type'=> 'string','index'=>'not_analyzed'),
                    'childCategory'=>array('type'=> 'string','index'=>'not_analyzed'),
                    'tags'=> array(
                        'type'=>'nested',
                        'properties'=>array(
                            'groupId'=>array('type'=> 'long'),
                            'name'=>array('type'=> 'string','index'=>'not_analyzed'),
                            'detail'=>array('type'=> 'string','index'=>'not_analyzed'),
                        ),
                    ),
                    'createTime'=>array('type'=> 'date','format'=> 'yyyy-MM-dd HH:mm:ss'),
                    'hits'=>array('type'=> 'long'),
                    'status'=>array('type'=> 'long'),
                    'isDefault'=>array('type'=> 'long'),
                    'fromType'=>array('type'=> 'long'),
                    'fromId'=>array('type'=> 'long'),
                    'type'=>array('type'=> 'string','index'=>'not_analyzed'),
                )
            )
        );
    }


}













