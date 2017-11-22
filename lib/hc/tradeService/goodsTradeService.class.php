<?php

/**
 * Class goodsTradeService
 * version: 1.0
 */
class goodsTradeService extends tradeService {

    /**
     *获取所有渠道
     * @param int goods_id

      $serviceRequest = new tradeServiceClient();
      $serviceRequest->setMethod('goods.supplier');
      $serviceRequest->setVersion('1.0');
      $serviceRequest->setApiParam('goods_id', $goods_id);
      $serviceRequest->setApiParam('supplier_id', $supplier_id);  //可选
      $response = $serviceRequest->execute();
     */
    public function executeSupplier()
    {
        $v = $this->getRequest()->getParameter('version');
        $goods_id = $this->getRequest()->getParameter('goods_id');
        $supplier_id = $this->getRequest()->getParameter('supplier_id', false);

        if(!$v) return $this->error(404, '丢失版本号');
        if (empty($goods_id) || !is_numeric($goods_id)) {
            return $this->error(501, '缺少商品ID');
        }

        $goodsSupplier = trdGoodsSupplierTable::getByGoodsId($goods_id);

        $info = $ziyin = $tuijian = $qita = array();
        foreach($goodsSupplier as $supplier){
            unset($supplier['unique_id']);
            unset($supplier['update_time']);
            unset($supplier['update_info']);
            unset($supplier['update_error_num']);
            unset($supplier['update_error_info']);
            unset($supplier['comment_update_time']);


            if($supplier['id'] == $supplier_id){
                $info[] = $supplier;
            }elseif($supplier['store'] == '识货团购'){
                $ziyin[] = $supplier;
            }elseif($supplier['store'] == '识货自营'){
                $tuijian[] = $supplier;
            }else{
                $qita[] = $supplier;
            }
        }

        $info = array_merge($info, $ziyin, $tuijian, $qita);
        return $this->success( array(
            'info'  => $info,
            'count' => count($goodsSupplier)
        ));
    }

    /**
     *获取所有评论
     * @param int goods_id

    $serviceRequest = new tradeServiceClient();
    $serviceRequest->setMethod('goods.comment');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('goods_id', $goods_id);
    $serviceRequest->setApiParam('order', $order);
    $serviceRequest->setApiParam('size', $size);
    $serviceRequest->setApiParam('page', $page);
    $response = $serviceRequest->execute();
     */
    public function executeComment()
    {
        $v = $this->getRequest()->getParameter('version');
        $goods_id = $this->getRequest()->getParameter('goods_id');
        $order = $this->getRequest()->getParameter('order','desc');
        $size = $this->getRequest()->getParameter('size',20);
        $page = $this->getRequest()->getParameter('page',1);

        if(!$v) return $this->error(404, '丢失版本号');
        if (empty($goods_id) || !is_numeric($goods_id)) {
            return $this->error(501, '缺少商品ID');
        }

        $goodsSupplierComment = trdGoodsSupplierCommentTable::getByGoodsId($goods_id, $page, $size, $order);
        foreach($goodsSupplierComment as &$comment){
            unset($comment['info']);
            unset($comment['unique_id']);
            unset($comment['updated_at']);

            //晒物
            if($comment['type'] == 1){
                $shaiwuProduct = trdShaiwuProductTable::getInstance()->getShaiwuById($goodsSupplierComment['supplier_id']);
                if($shaiwuProduct){
                    $comment['uid']      = $shaiwuProduct['author_id'];
                    $comment['content']  = $shaiwuProduct['intro'];
                    $comment['nickname'] = $shaiwuProduct['author_name'];
                    $comment['img_attr'] = $shaiwuProduct['img_attr'];
                }else{
                    continue;
                }
            }
        }

        $goods = trdGoodsTable::getInstance()->find($goods_id);

        return $this->success(array(
            'info'  => $goodsSupplierComment,
            'count' => $goods->getComment()
        ));
    }


    /**
     *保存商品库来源

    $serviceRequest = new tradeServiceClient();
    $serviceRequest->setMethod('goods.save.supplier');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('id', $id);
    $serviceRequest->setApiParam('goods_id', $goods_id);
    $serviceRequest->setApiParam('name', $name);
    $serviceRequest->setApiParam('description', $description);
    $serviceRequest->setApiParam('price', $price);
    $serviceRequest->setApiParam('url', $url);
    $serviceRequest->setApiParam('status', $status);
    $serviceRequest->setApiParam('from_type', $from_type);
    $serviceRequest->setApiParam('from_id', $from_id);
    $serviceRequest->setApiParam('notice', $notice);
    $response = $serviceRequest->execute();
     */
    public function executeSaveSupplier()
    {
        $v = $this->getRequest()->getParameter('version');
        $id = $this->getRequest()->getParameter('id','');
        $goods_id = $this->getRequest()->getParameter('goods_id');
        $name = $this->getRequest()->getParameter('name');
        $description = $this->getRequest()->getParameter('description');
        $price = $this->getRequest()->getParameter('price');
        $url = $this->getRequest()->getParameter('url');
        $status = $this->getRequest()->getParameter('status',false);
        $from_type = $this->getRequest()->getParameter('from_type');
        $from_id = $this->getRequest()->getParameter('from_id', 0);
        $notice = $this->getRequest()->getParameter('notice', false);


        if($id){
            $goodsSupplier = trdGoodsSupplierTable::getInstance()->find($id);
            if(!$goodsSupplier) return $this->error(500, 'id不存在');
        }else{
            $goodsSupplier = new trdGoodsSupplier();
        }

        if($url) {
            $store = TrdGoodsSupplierForm::getStoreName($url);
            $goodsSupplier->setStore($store);
            if($from_type == 6
                && $store == '识货团购'
                && !$id
            ){//手动添加 新增团购来源 默认下架
                $status = 1;
            }
        }

        if($name) $goodsSupplier->setName($name);
        if($goods_id) $goodsSupplier->setGoodsId($goods_id);
        if($from_type) $goodsSupplier->setFromType($from_type);
        if($from_id) $goodsSupplier->setFromId($from_id);
        if($description) $goodsSupplier->setDescription($description);
        if($price) $goodsSupplier->setPrice($price);
        if($url) $goodsSupplier->setUrl($url);
        if($status !== false) $goodsSupplier->setStatus($status);

        $goodsSupplier->save();

        //更新动态
        if($notice){// update
            $serviceRequest = new tradeServiceClient();
            $serviceRequest->setMethod('goods.edit.notice');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('goods_id', $goods_id);
            $serviceRequest->setApiParam('supplier_id', $goodsSupplier->getId());
            $serviceRequest->setApiParam('action', 'update');
            $response = $serviceRequest->execute();
        }

        return $this->success(array(
            'info'  => $goodsSupplier->toArray()
        ));
    }


   /** 商品列表
     * @params
     * search[pageSize] 每页数 （数字，默认20）
     * search[groups] = array( '分组名1'=>'标签1','分组名2'=>'标签2'); 分组标签
     * search[pageNo] 页数 （数字）
     * search[code] 货号 （字符串）
     * search[rootBrand] 品牌 （字符串或数组）
     * search[childBrand] 系列 （字符串或数组）
     * search[rootCategory] 分类 （字符串或数组）
     * search[childCategory] 二级分类 （字符串或数组）
     * search[type] 运动场景  （字符串或数组）
     * search[price][from] 价格 大于 （数字）
     * search[price][to] 价格 小于 （数字）
     * search[name] 款式名称 （字符串）
     * search[backend] 是否是后台显示 布尔类型
     * search[check] 是否后台完善列表 布尔类型
     * search[hits] 是否人气排序 布尔类型
     * search[aggs] 是否返回菜单  布尔类型
     * search[priceSort] 价格排序  desc 降序  asc 升序
     *
    $serach = array(
            'hits'=>true,
            'groups'=>array( '分组名1'=>'标签1','分组名2'=>'标签2'),
            'childCategory'=>array('跑步鞋','运动鞋'),
            'rootBrand’=>'乔丹'
            'pageNo'=>3,
    );
    * $serviceRequest = new tradeServiceClient();
   $serviceRequest->setMethod('goods.list');
   $serviceRequest->setVersion('1.0');
   $serviceRequest->setApiParam('search', $serach);
   $response = $serviceRequest->execute();
    *
     * @return array
     */
    public function executeList()
    {
        try
        {
            $search = $this->request->getParameter('search',array());
            $search['pageSize'] = (isset($search['pageSize']))?$search['pageSize']:20;
            $search['pageNo'] = (isset($search['pageNo']) && $search['pageNo']>1)?$search['pageNo']-1:0;
            $es = new goodsSearch();
            $data = $es->search($search);
            if( false === $data['status'] )
            {
                throw new Exception('搜索出错啦',-88);
            }

            if(empty($search['backend']) && !empty($data['result']))
            {
                $redisHandle = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
                foreach($data['result'] as $k=>$v)
                {
                    # 前台获取关注数
                    $hit_count_key = 'shihuo_hits_count_action_goodsDetail_id_'. $v['id'];
                    $hit_num = (int)$redisHandle->get($hit_count_key); 
                    $data['result'][$k]['hits'] = $hit_num;
                }
            }

            return $this->success($data);
        }
        catch (Exception $e)
        {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    # 商品详情基本信息
    public function executeDetail()
    {
        try {
            $goodsId = (int)$this->request->getParameter('id');
            $styleId = (int)$this->request->getParameter('styleId','');
            if (empty($goodsId)) {
                throw new Exception('缺少参数', 401);
            }

            $data = TrdGoodsTable::getGoodsDetail($goodsId,$styleId);

            if (empty($data)) {
                throw new Exception('数据不存在', 505);
            }

            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }


    /**
     *浏览数统计

    $serviceRequest = new tradeServiceClient();
    $serviceRequest->setMethod('goods.hits');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('goods_id', $goods_id);
    $serviceRequest->setApiParam('style_id', $style_id);
    $response = $serviceRequest->execute();
     */
    public function executeHits()
    {
        try {
            $goodsId = (int)$this->request->getParameter('goods_id');
            $styleId = (int)$this->request->getParameter('style_id');
            if (empty($goodsId)) {
                throw new Exception('缺少参数', 402);
            }
            if(empty($styleId))
            {
                # 默认款式
                $tmp = TrdGoodsStyleTable::getInstance()->createQuery()->andWhere('goods_id = ?',$goodsId)->andWhere('is_default = 1')->fetchArray();
                if(empty($tmp[0]))
                {
                    throw new Exception('该款式不存在',-1);
                }
                $styleId = $tmp[0]['id'];
            }
            //msg点击数(浏览数)统计
            tradeWebPageHitsCount::getInstance()->tradeMsgHitsCount($styleId, 'goodsDetail', 300);
            $data['hits'] = true;
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     *渠道同步动态接口

    $serviceRequest = new tradeServiceClient();
    $serviceRequest->setMethod('goods.edit.notice');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('goods_id', $goods_id);
    $serviceRequest->setApiParam('supplier_id', $supplier_id);
    $serviceRequest->setApiParam('action', 'delete');  // update,delete
    $response = $serviceRequest->execute();
     */
    public function executeEditNotice()
    {
        try {
            $supplier_id = (int)$this->request->getParameter('supplier_id');
            $goods_id = (int)$this->request->getParameter('goods_id');
            $action = $this->request->getParameter('action');

            if (empty($goods_id) || empty($supplier_id) || !in_array($action,array('delete','update'))){
                throw new Exception('缺少参数', 401);
            }
            $notice = TrdGoodsNoticeTable::getInstance()->createQuery()->andWhere('supplier_id = ?',$supplier_id)->andWhere('goods_id = ?',$goods_id)->fetchOne();
            if($action == 'delete')
            {
                if(!empty($notice))
                {
                    $notice->delete();
                }else{
                    throw new Exception('该动态不存在', 503);
                }
            }
            elseif($action == 'update')
            {
                if(empty($notice))
                {
                    $notice = new TrdGoodsNotice();
                    $notice->supplier_id = $supplier_id;
                }
                $notice->goods_id = $goods_id;
                $notice->save();
            }else{
                throw new Exception('参数错误', 402);
            }
            return $this->success();
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     *获取商品的配色

    $serviceRequest = new tradeServiceClient();
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setMethod('goods.get.styles');
    $serviceRequest->setApiParam('goods_id', $goods_id);
    $response = $serviceRequest->execute();
     */
    public function executeGetStyles()
    {
        try {
            $goodsId = $this->request->getParameter('goods_id');
            if (empty($goodsId)) {
                throw new Exception('缺少参数', 402);
            }
            $styles = TrdGoodsStyleTable::getStylesByFront($goodsId);
            if(empty($styles))
            {
                throw new Exception('没有配色哦', 501);
            }
            $data['list'] = $styles;
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     *获取配色的图片 
    $serviceRequest = new tradeServiceClient();
    $serviceRequest->setMethod('goods.get.style.detail');
    $serviceRequest->setApiParam('id', $id);
    $response = $serviceRequest->execute();
     */
    public function executeGetStyleDetail()
    {
        try {
            $styleId = $this->request->getParameter('id');
            if (empty($styleId)) {
                throw new Exception('缺少参数', 402);
            }
            $tmp = TrdGoodsStyleTable::getStylePic($styleId);
            if(empty($tmp['value']))
            {
                throw new Exception('当前配色不存在', 501);
            }
            $pics = json_decode($tmp['value'],true);
            if(empty($pics)) throw new Exception('配色内容为空', 502);
            $data['list'] = $pics;
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }


    /** 商品动态列表
     * @params
     * search[pageSize] 每页数
     * search[pageNo] 页数
     *  $serviceRequest = new tradeServiceClient();
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setMethod('goods.list.notice');
        $serviceRequest->setApiParam('search', $search);
        type参数列表
        $search['type'] = array(
        1,2,3,4);
        对应
        1=>'篮球',
        2=>'跑步',
        3=>'休闲',
        4=>'其他',
        $response = $serviceRequest->execute();
     * @return array
     */
    public function executeListNotice()
    {
        try
        {
            $search = $this->request->getParameter('search',array());
            $search['pageSize'] = (isset($search['pageSize']))?$search['pageSize']:20;
            $es = new goodsNoticeSearch();
            # 显示已审核通过的
            $search['status'] = 1;
            $search['pageNo'] = (isset($search['pageNo']) && $search['pageNo']>1)?$search['pageNo']-1:0;
            $data = $es->search($search);
            if( false === $data['status'] )
            {
                throw new Exception('数据为空',-88);
            }
            if(!empty($data['result']))
            {
                $ids = array();
                foreach($data['result'] as $k=>$v)
                {
                    if(!in_array($v,$ids))$ids[] = $v['goodsId'];
                }

                if(!empty($ids))
                {
                    $goods = TrdGoodsTable::getGoodsByIds(array_unique($ids));
                }

                # 关联商品名称和场景
                if(!empty($goods))
                {
                    foreach($data['result'] as $k=>$v)
                    {
                        if(!empty($goods[$v['goodsId']]))
                        {
                            $data['result'][$k]['goodsName'] = $goods[$v['goodsId']]['name'];
                            $data['result'][$k]['goodsType'] = explode(',',$goods[$v['goodsId']]['type']);
                            $data['result'][$k]['type'] = 0;
                        }else{
                            unset($data['result'][$k]);
                        }
                    }
                }else{
                    $data['result'] = array();
                }

                # 获取晒物动态
                if(!empty($data['result']))
                {
                    $shaiwuOffset = floor(($search['pageSize']*$search['pageNo'])/5);
                    $shaiwuLimit = floor(($search['pageSize'])/5);
                    $shaiwuNotices = TrdGoodsNoticeShaiwuTable::getList($shaiwuOffset,$shaiwuLimit);
                    if(!empty($shaiwuNotices))
                    {
                        $offset = 5;
                        foreach($shaiwuNotices as $v)
                        {
                            array_splice($data['result'], $offset, 0,array($v));
                            $offset = $offset + 5;
                        }
                    }
                }
            }

            return $this->success($data);
        }
        catch (Exception $e)
        {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     *获取相关商品
    $serviceRequest = new tradeServiceClient();
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setMethod('goods.get.relation.goods');
    $serviceRequest->setApiParam('child_category', $child_category);
    $serviceRequest->setApiParam('goods_id', $goods_id);
    $response = $serviceRequest->execute();
     */
    public function executeGetRelationGoods()
    {
        try
        {
            $childCategory = $this->request->getParameter('child_category');
            $goods_id = $this->request->getParameter('goods_id');
            $pageSize = $this->request->getParameter('pagesize',6);
            if (empty($childCategory) || empty($goods_id)) {
                throw new Exception('缺少参数', 402);
            }
            $es = new goodsSearch();
            $search['notId'] = $goods_id;
            $search['childCategory'] = $childCategory;
            $search['pageSize'] = $pageSize;
            $search['isDefault'] = 1;
            $data = $es->search($search);
            if( false === $data['status'] )
            {
                throw new Exception('数据为空',-88);
            }
            return $this->success($data);
        }
        catch (Exception $e)
        {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }
}