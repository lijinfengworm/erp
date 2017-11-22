<?php
Class goodsNoticeSearch extends tradeESBaseSearch{
    public   $_type = 'goods_notice';
    public   $_channel_type = 11;   //频道类型为3
    public  $fields = array(
        'id','goodsId','supplierId','supplierName','desc','price','fromType','createTime','status','type','checkTime','pic','tagType',
    );

    /*列表搜索*/
    public function search($search_data){
        $search_data = array_filter($search_data,function($v){ if($v === '') { return false; }else { return true; }
        });
        if(isset($search_data['pageSize']))       //分页
            $data['size'] = (int)$search_data['pageSize'];
        else
            $data['size'] = 20;
        if(isset($search_data['pageNo']))
            $data['from'] = ((int)$search_data['pageNo']) * $search_data['pageSize'];
        else
            $data['from'] = 0;

        /*条件*/
        if(isset($search_data['type'])){
            $query[] = $this->setupFilter($search_data['type'],'type');
        }

        if(isset($search_data['status'])){
            $query[] = $this->setupFilter($search_data['status'],'status');
        }
        if(isset($search_data['fromType'])){
            $query[] = $this->setupFilter($search_data['fromType'],'fromType');
        }
        if(isset($search_data['goodsType'])){
            $query[] = $this->setupFilter($search_data['goodsType'],'goodsType');
        }

        if(!empty($query))
        {
            $data['query']['bool']['must'][] = $query;
        }

        # 时间排序
        $data['sort']['checkTime']['order'] = 'desc';


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


    private function setupFilter($data,$flag){
        switch($flag) {
            /*商品运动场景*/
            case "goodsType":
                return array(
                    'term' => array(
                        'goodsType' => $data
                    )
                );
                break;
            /*推送至运动场景*/
            case "type":
                $filterType = is_array($data)?'terms':'term';
                return array(
                    $filterType => array(
                        'type' => $data
                    )
                );
                break;

            /*状态*/
            case "status":
                return array(
                    'term' => array(
                        'status' => $data
                    )
                );
                break;
            /*来源ID*/
            case "fromType":
                return array(
                    'term' => array(
                        'fromType' => $data
                    )
                );
                break;
        }
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
                        if($v2 == 'type' || $v2 == 'goodsType')
                        {
                            if(!empty($v['fields'][$v2]))$tmp[$v2] = $v['fields'][$v2];
                        }else{
                            if($v2 == 'checkTime' && empty($v['fields'][$v2][0])){
                                $tmp[$v2] = '';
                            }else{
                                $tmp[$v2] = $v['fields'][$v2][0];
                            }
                        }

                    }
                    $result[] = $tmp;
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

    //update data
    public function _updateData($id){
        $table = TrdGoodsNoticeTable::getInstance();
        $tableData = $table
            ->createQuery($this->getLink())
            ->andWhere('id =?', $id)
            # 未通过的删除掉
            ->andWhereIn('status', array(0,1))
            ->fetchArray();

        $updateData = array();

        foreach($tableData as $tableDataVal) {

            $channelTypes = sfConfig::get('app_shihuo_elasticsearch_channel_types');

            $channelType = $channelTypes[$this->_type];

            $data = array();
            $data['id'] = $tableDataVal["id"];
            $data['channelType'] = $channelType;
            $goods = TrdGoodsTable::getInstance()->find($tableDataVal["goods_id"]);
            if($goods)
            {
                $data['goodsId'] = $tableDataVal["goods_id"];
            }
            else
            {
                continue;
            }
            $supplier = TrdGoodsSupplierTable::getInstance()->find($tableDataVal['supplier_id']);
            if(empty($supplier) || $supplier->status == 1) continue;
            $data['supplierId']  = $supplier->id;
            $data['supplierName']  = $supplier->name;
            $data['store']  = $supplier->store;
            $data['desc']  = $supplier->description;
            $data['price']  = $supplier->price;
            $data['url']  = $supplier->url;
            $data['fromType']  = $supplier->from_type;

            if(!empty($tableDataVal['type']))
            {
                $types = explode(',',$tableDataVal['type']);
                $data['type']  = $types;
            }
            $data['status']  = $tableDataVal['status'];
            $data['pic']  = $tableDataVal['pic'];
            $data['tagType']  = $tableDataVal['tag_type'];
            if($tableDataVal['checked_at'] != '0000-00-00 00:00:00')
            {
                $data['checkTime'] = $tableDataVal['checked_at'];
            }

            $data['createTime'] = $tableDataVal['created_at'];
            $updateData[] = $data;
        }

        return $updateData;
    }

    //mapping data
    public function _mappingData(){
        return array(
            'goods_notice'=>array(
                'properties'=>array(
                    'id'=>array('type'=> 'long'),
                    'channelType'=>array('type'=> 'long'),
                    'pic'=>array('type'=> 'string','index'=>'not_analyzed'),//商品ID
                    'goodsId'=>array('type'=> 'long'),//商品ID
                    'tagType'=>array('type'=> 'long'),//商品标签
//                    'goodsName'=>array('type'=> 'string','index'=>'not_analyzed'),//商品名称
//                    'goodsType'=>array('type'=> 'string','index'=>'not_analyzed'),//商品类型
                    'supplierId'=>array('type'=> 'long'),//渠道ID
                    'supplierName'=>array('type'=> 'string','index'=>'not_analyzed'),//渠道名
                    'store'=>array('type'=> 'string','index'=>'not_analyzed'),//商城
                    'desc'=>array('type'=> 'string','index'=>'not_analyzed'),//描述
                    'price'=>array('type'=> 'double'),//价格
                    'type'=>array('type'=> 'string','index'=>'not_analyzed'),// 类型
                    'url'=>array('type'=> 'string','index'=>'not_analyzed'),//链接
                    'fromType'=>array('type'=> 'long'),//来源
                    'type'=>array('type'=> 'string','index'=>'not_analyzed'), //推送至类型
                    'checkTime'=>array('type'=> 'date','format'=> 'yyyy-MM-dd HH:mm:ss'),
                    'createTime'=>array('type'=> 'date','format'=> 'yyyy-MM-dd HH:mm:ss'),
                    'status'=>array('type'=> 'long'), //审核状态
                )
            )
        );
    }

    //索引删除
    public  function delete($id){
        if(!$id || !is_numeric($id)){
            return $this->_return(false, '参数错误');
        }
        $notice = TrdGoodsNoticeTable::getInstance()->find($id);
        if(!empty($notice) && $notice->status !=2)
        {
            $connection = Doctrine_Manager::getInstance()->getConnection('trade');
            $sql  = "UPDATE trd_goods_notice SET status=2 WHERE id='{$id}'";
            $statement = $connection->execute($sql);
            $statement->execute();
        }

        $data = array();
        $array = array(
            '_type'=> $this->_type,
            '_id'  => $id,
            'data' => $data
        );

        $es =new tradeElasticSearch();
        return $es->delete($array);
    }

    //索引创建
    public function create($id){
        $notice = TrdGoodsNoticeTable::getInstance()->find($id);
        if(!empty($notice) && $notice->status ==2)
        {
            $connection = Doctrine_Manager::getInstance()->getConnection('trade');
            $sql  = "UPDATE trd_goods_notice SET status=0,checked_at='0000-00-00 00:00:00.000000' WHERE id='{$id}'";
            $statement = $connection->execute($sql);
            $statement->execute();
        }
        return $this->update($id);
    }
}













