<?php
Class shaiwuSearch extends tradeESBaseSearch{
    public   $_type = 'shaiwu';
    public   $_channel_type = 7;   //频道类型为3

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
        if(isset($search_data['isHot']) && $search_data['isHot']){                                                                          //精华                         //精华
            $filters[] = $this->setupFilter($search_data['isHot'],'isHot');
        }
        if(isset($search_data['type'])){                                                                                                    //类型
            $filters[] = $this->setupFilter($search_data['type'],'type');
        }
        if(isset($search_data['activityId'])){                                                                                             //活动ID
            $filters[] = $this->setupFilter($search_data['activityId'],'activityId');
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


        if($querys)$data['query'] = $querys;
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
            /*精华*/
            case "isHot":
                return array(
                    'term' => array(
                        'isHot' => 1
                    )
                );
                break;
            /*类型*/
            case "type":
                return array(
                    'term' => array(
                        'type' => $data
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
            /*活动ID*/
            case "activityId":
                return array(
                    'term' => array(
                        'activityId' => $data
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

    //update data
    public function _updateData($ids){
        $table = TrdShaiwuProductTable::getInstance();
        $tableData = $table
            ->createQuery($this->getLink())
            ->whereIn('id', $ids)
            ->fetchArray();
        $updateData = array();
        foreach($tableData as $tableDataVal) {
            if($tableDataVal && $tableDataVal["status"] == 1) {
                $channelTypes = sfConfig::get('app_shihuo_elasticsearch_channel_types');
                $channelType = $channelTypes[$this->_type];

                $tag_ids = $tableDataVal["tag_ids"];
                $tag_names = explode(',', $tag_ids);
                $brandName = $this->getBrandName($tableDataVal["children_id"], $tableDataVal["brand_id"]);

                $data = array();
                $data['id'] = $tableDataVal["id"];
                $data['title'] = $tableDataVal["title"];
                $data['channelType'] = $channelType;
                $data['type']  = $tableDataVal["type"];
                $data['brand'] = $brandName;
                $data['isHot'] = $tableDataVal["is_hot"];
                $data['childId'] = $tableDataVal["children_id"];
                $data['rootId']  = $tableDataVal["root_id"];
                $data['imagePath'] = $tableDataVal["front_pic"];
                $data['activityId'] = $tableDataVal["activity_id"];
                $data['tag'] = $tag_names;
                $data['point'] = $tableDataVal["rank"];
                $data['createTime'] = $tableDataVal["publish_time"];
                $data['hits'] = $tableDataVal['hits'];

                $updateData[] = $data;
            }
        }

        return $updateData;
    }

    //mapping data
    public function _mappingData(){
        return array(
            'shaiwu'=>array(
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
                    'type'=>array('type'=> 'string','index'=>'not_analyzed'),
                    'brand'=>array('type'=> 'string','index'=>'not_analyzed'),
                    'isHot'=>array('type'=> 'long'),
                    'childId'=>array('type'=> 'long'),
                    'rootId'=>array('type'=> 'long'),
                    'activityId'=>array('type'=> 'long'),
                    'tag'=>array('type'=> 'string','index'=>'not_analyzed'),
                    'imagePath'=>array('type'=> 'string'),
                    'point'=>array('type'=> 'double'),
                    'createTime'=>array('type'=> 'date','format'=> 'yyyy-MM-dd HH:mm:ss'),
                    'hits'=>array('type'=> 'long'),
                )
            )
        );
    }

    /*获取品牌名*/
    private  function getBrandName($children_id,$brandId){
        if(empty($brandId) || empty($children_id)) return '其他';
        $groupTable = TrdGroupTable::getInstance();
        $groupData = $groupTable->createQuery($this->getLink())
            ->where('menu_id = ?',$children_id)
            ->andWhere('usage = 1')
            ->orderBy('sort asc')
            ->fetchOne();
        if(empty($groupData)) return  '其他';
        $groupData = $groupData->toArray();
        /*  获取品牌 */
        $brandTable = TrdAttrGroupTable::getInstance();
        $brandData = $brandTable->createQuery('m')
            ->where('m.trd_group_id = ?',$groupData['id'])
            ->leftJoin('m.TrdAttribute a on a.id = ? ',$brandId)
            ->orderBy('a.id asc')
            ->fetchOne();
        if(empty($brandData)) return  '其他';
        $brandData = $brandData->toArray();
        $brand_name = $brandData['TrdAttribute']['name'];

        if(!empty($brand_name)) return $brand_name;
        return '其他';
    }
}













