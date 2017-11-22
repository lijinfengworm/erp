<?php

class itemKaluliService extends kaluliService{

    private  function redis() {
        self::$_redis =  sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        self::$_redis->select(10);
    }

    public static $_redis ;
    /*
     *通过商品ID获取数据
     *
     **/
    public function executeItemGet()
    {
        $id = $this->getRequest()->getParameter('id',0);
        $isSku = $this->getRequest()->getParameter('isSku',false);
        $isTag = $this->getRequest()->getParameter('isTag',false);
        $isZero = $this->getRequest()->getParameter("isZero",false);

        #验证
        if(!is_numeric($id)) return  $this->error('401','参数不合法');

        #查询
        $return = array();
        $item = kaluliItemTable::getItemById($id,$isTag,$isZero);
        $itemPre = kaluliItemTable::getItemById($id,$isTag,1);

        if($item){
            $return['item'] = $item;
            //商品评论
            $comment = KaluliItemAttrTable::getInstance()->findOneByItemId($id);
            if(!empty($comment)){
                $return['comment_imgs_count'] = $comment->getCommentImgsCount();
                $return['comment_count'] = $comment->getCommentCount();
            }else{
                $return['comment_imgs_count'] = 0;
                $return['comment_count'] = 0;
            }
            if($isSku){
                $itemSku = KaluliItemSkuTable::getSkusByItemId($id);
                if(!$itemSku)
                    return  $this->error('402','没有子商品');
                else
                    $data = $this->getFormatData($itemSku);

                $return['sku'] = $itemSku;
                $return['attr'] = $data['attr'];
                $return['stock'] =  $data['stock'];

            }
        }elseif(empty($item)&&$itemPre){
            return $this->error("403","商品失效");
        }
        else{
            return  $this->error('402','没有数据');
        }

        return $this->success($return);
    }

    /*
   *通过sku ID获取数据
   *
   **/
    public function executeItemSkuGet()
    {
        $id = $this->getRequest()->getParameter('id',0);

        #验证
        if(!is_numeric($id)) return  $this->error('401','参数不合法');

        #查询
        $return = array();
        $sku = kaluliItemSkuTable::getSkuById($id);
        if($sku)
            $return['sku'] = $sku;
        else
            return  $this->error('402','没有数据');

        return $this->success($return);
    }


   /*
   *通过sku ID增减库存
   *
   **/
    public function executeSkuStock()
    {
        $id = $this->getRequest()->getParameter('id','');
        $num = $this->getRequest()->getParameter('num','');
        $type = $this->getRequest()->getParameter('type','');  //array(1=>'下单成功',2=>'付款成功'，3=>'退款'，4=>'未付款取消',5=>'退款取消')

        #验证
        if(!is_numeric($id) || !is_numeric($num) || !is_numeric($type)) return  $this->error('401','参数不合法');

        #查询
        $res = kaluliItemSkuTable::setSkuStockById($id,$num,$type);
        if($res['status'])
           $return = $res;
        else
           return $this->error('502',$res['message']);

        return $this->success($return);
    }

    private function getFormatData($itemSku){
        $return = array('attr' => array(),'stock'=>0);
        if(!$itemSku) return $return;

        $viewData = $viewDetail = $viewAttr = $aliasArr = array();
        $stock = 0;
        $i = 10; #别名标示10起始
        foreach($itemSku as $key => $val){
            $attrs = unserialize($val['attr']);
            $attrs = $attrs['attr'];

            $step = '';
            if($attrs){#有规格
                foreach($attrs as $attr_k => $attr_v){
                    if(!$alias = array_search($attr_k.$attr_v,$aliasArr)){
                        $aliasArr[$i] = $attr_k.$attr_v;
                        $alias = $i;
                    }

                    $viewAttr[$attr_k]['data'][$alias]['name'] = $attr_v;
                    $viewAttr[$attr_k]['data'][$alias]['alias'] = $alias;
                    $viewAttr[$attr_k]['name'] = $attr_k;

                    $step .= $alias.';';
                    $i++;
                }

                $step = rtrim($step,';');
            }else{
                $step = 0;
            }

            $viewDetail[$step]['itemId'] = $val['item_id'];
            $viewDetail[$step]['skuId'] = $val['id'];
            $viewDetail[$step]['stock'] = $val['total_num'];
            $viewDetail[$step]['price'] = $val['price'];
            $viewDetail[$step]['discountPrice'] = $val['discount_price'];
            $viewDetail[$step]['status'] = $val['status'];
            if($val['status'] ==0) {
                $stock += $val['total_num'];
            }
        }

        $viewAttrValues = array_values($viewAttr);
        //加默认选中参数
        foreach($viewAttrValues as $k => $v) {
            if(count($v['data'],0) ==1) {
                $viewAttrValues[$k]['flag'] = 1;
            }
        }
        $viewData['detail'] = $viewDetail;
        $viewData['attr'] = array_values($viewAttrValues);

        $return['attr'] = $viewData;
        $return['stock'] = $stock;

        return $return;
    }

    # 随机热门推荐
    public function executeHotItem()
    {
        try
        {
            $num = $this->request->getParameter("num");
            if(empty($num)) $num = 6;
            $data = KaluliItemTable::getRandItem(rand(0,8),$num);
            if(empty($data))
            {
                throw new Exception('数据为空',-1);
            }
            return $this->success(array('list'=>$data));
        }
        catch(Exception $ex)
        {
            return $this->error($ex->getCode(), $ex->getMessage());
        }
    }

    # 足迹
    public function executeHistoryScan()
    {
        try
        {
            $uid = $this->user->getAttribute('uid');
            if(empty($uid))
            {
                throw new Exception('没登陆',-1);
            }

            $itemId  = $this->request->getParameter("id");
            if(empty($itemId))
            {
                throw new Exception('参数为空',-1);
            }

            $history = json_decode(base64_decode(sfContext::getInstance()->getRequest()->getCookie('item_history')));

            if(empty($history) || !is_array($history))
            {
                $history = array();
            }
            else
            {
                # 在最近浏览中
                if(  in_array($itemId,$history) )
                {

                    $index = array_search($itemId,$history);
                    unset($history[$index]);
                }
                elseif( count($history) >= 4 )
                {
                    array_pop($history);
                }
            }

            array_unshift($history,$itemId);

            sfContext::getInstance()->getResponse()->setCookie('item_history',base64_encode(json_encode($history)),time()+86400*100);

            return $this->success();
        }
        catch(Exception $ex)
        {
            return $this->error($ex->getCode(), $ex->getMessage());
        }

    }

    # 获取商品销量
    public function executeGetSales()
    {
        try
        {
            $product_id = $this->request->getParameter("product_id");
            $page = $this->request->getParameter("page",1);
            $pageSize = $this->request->getParameter("pageSize",20);
            if(empty($product_id))
            {
                throw new Exception('缺少参数',-2);
            }
            $data = KaluliItemAttrTable::getInstance()->findOneBy('item_id',$product_id);
            if(empty($data))
            {
                throw new Exception('商品不存在为空',-1);
            }
            if($page<1) $page = 1;
            $offset = ($page-1)*$pageSize;

            $cacheTime = 1;
            $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
            $redis->select(1);
            $logKey = 'kaluli_pro_sales_' . $product_id . '_p' . $page . '_ps' . $pageSize;
            $return = unserialize($redis->get($logKey));
            if (!$return)
            {
                $logs = KllItemTradelogTable::getLog($product_id, $offset, $pageSize);
                if (empty($logs))
                {
                    throw new Exception('日志为空', -3);
                }
                $logPageNum = ceil(KllItemTradelogTable::getLogCount($product_id) / $pageSize);
                $return = array('list' => $logs, 'pageCountNum' => $logPageNum);
                $redis->set($logKey, serialize($return), $cacheTime);
            }
            return $this->success($return);
        }
        catch(Exception $ex)
        {
            return $this->error($ex->getCode(), $ex->getMessage());
        }
    }

    public function executeIsHaitao(){
        try {
            $skuId = $this->getRequest()->getParameter("skuId");
            $isHaitao = 0;
            if (empty($skuId)) {
               throw new Exception("缺少参数",-1);
            }
            $sku = kaluliItemSkuTable::getSkuById($skuId);
            if($sku){
                $storeHouse = $sku['storehouse_id'];
                if($storeHouse == 10 || $storeHouse == 20 || $storeHouse == 16 || $storeHouse == 5) {
                    $isHaitao = 1;
                }elseif($storeHouse == 19){
                    $isHaitao = 2;
                }
            }

            return $this->success($isHaitao);
        }catch(Exception $ex) {
            return $this->error($ex->getCode(),$ex->getMessage());
        }
    }

    public function executeGetHotItems(){
        $this->redis();
        $itemId = $this->getRequest()->getParameter("itemId");
        if(empty($itemId)){
            return $this->error("501","缺少参数");
        }
        $redisKey = "kaluli.hotitem.".$itemId;
        $items = json_decode(self::$_redis->get($redisKey),true);
        if(!empty($items)){
            return $this->success($items);
        } else {
            //从限时抢购取出生成数据
            $itemTable = KaluliItemTable::getInstance();
            $date = date("Y-m-d");
            $lastDate = date("Y-m-d",strtotime("-1 day"));
            $sec_one = json_decode(self::$_redis->get('kaluli_limitbuy' . $date), true) ? json_decode(self::$_redis->get('kaluli_limitbuy' . $date), true) : array();
            $sec_two = json_decode(self::$_redis->get('kaluli_limitbuy' . $lastDate), true) ? json_decode(self::$_redis->get('kaluli_limitbuy' . $lastDate), true) : array();
            $infoIds = array();
            if(empty($sec_one) || empty($sec_two)){
                return $this->success();
            }else {
                for ($i = 0; $i < 4; $i++) {
                    $infoIds[] = $sec_one["ID" . $i];
                    $infoIds[] = $sec_two["ID" . $i];
                }

                $infos = $itemTable::getMessage(array("ids" => $infoIds, 'select' => 'id,title,pic,sell_point,discount_price,price,intro', 'arr' => 1));

                $redisInfo = json_encode($infos);
                self::$_redis->set($redisKey, $redisInfo);

                return $this->success($infos);
            }
        }
    }

    public function executeGetHotActivity(){
        $this->redis();
        $activity = self::$_redis->get("kaluli.hotActivity");
        if(empty($activity)){
            $this->error("502","热门活动没有配置");
        }
        $activity = json_decode($activity,true);
        $activitys = array();
        if(!empty($activity)) {
            for ($i = 0; $i < 3; $i++) {
                $info["title"] = $activity["title" . $i];
                $info["link"] = $activity["link" . $i];
                $info["pic"] = $activity["pic" . $i];
                $activitys[] = $info;
            }
        }

        return $this->success(['activitys'=>$activitys]);
    }

    public function executeGetHotItems2() {
        $this->redis();
        $itemId = $this->getRequest()->getParameter("itemId");
        if(empty($itemId)){
            return $this->error("501","缺少参数");
        }
        $redisKey = "kaluli.hotitem.extra.".$itemId;
        $items = json_decode(self::$_redis->get($redisKey),true);
        if(empty($items)) {
            //执行逻辑
            $infos = KaluliItemForm::getScheme($itemId);
            self::$_redis->set($infos);
            $items = json_decode($infos,true);
        }
        if(count($items)< 5) {
            $num = count($items);
        } else {
            $num = 5;
        }
        $keys = array_rand($items,$num);
        foreach($keys as $v) {
            $info[] = $items[$v];
        }

        return $this->success($info);
    }

    /**
     * 获取品牌列表
     * @return array
     */
    public function executeGetItemBrands(){
        $data = KllItemBrandTable::getInstance()->createQuery()->where("status = 1")->fetchArray();
        $brands = array();
        foreach ( $data as $k =>$v ) {
            $brands[$v['id']] = $v['name'];
        }

        return $this->success($brands);
    }

    /**
     * 根据品牌名获取品牌信息
     */
    public function executeGetBrandInfoByName() {
        $name = $this->getRequest()->getParameter("name");
        if(empty($name)){
           return $this->error(500,"参数错误");
        }

        $data = KllItemBrandTable::getInstance()->createQuery()->where("name = ?",$name)->andWhere("status = 1")->fetchOne();
        if(empty($data)) {
           return $this->error(500,"品牌不存在");
        }
        return $this->success($data);
    }

    public function executeGetBrandInfoById(){
        $id = $this->getRequest()->getParameter("id");
        if(empty($id)) {
            return $this->error(500,"参数错误");
        }
        $data = KllItemBrandTable::getInstance()->findOneById($id);
        if(empty($data)) {
            return $this->error(500,"品牌不存在");
        }
        return $this->success($data);
    }

    /**
     * 根据品牌id获取所有商品列表
     */
    public function executeGetItemsByBrand() {
        $brandId = $this->getRequest()->getParameter("id");
        $page    = $this->getRequest()->getParameter("p",1);
        $pageSize = $this->getRequest()->getParameter("pageSize",20);
        if(empty($brandId)){
            return   $this->error(500,"参数错误");
        }
        //获取商品信息
        $bind = array();
        $bind["page"] = $page-1;
        $bind['limit'] = $pageSize;
        $bind['select'] = 'id,title,pic,brand_id,sell_point,intro,price,discount_price,status,created_at';
        $bind['brand_id'] = $brandId;
        $bind['arr'] = true;
        $bind['status_es'] = true;
        $items = KaluliItemTable::getMessage($bind);
        //获取商品总数;
        $countMap = array();
        $countMap[] ="brand_id = ".$brandId;
        $countMap[] = "status = 3";
        $countMap[] = "status_es = 1";
        $count = KaluliItemTable::houtaGetCount($countMap);
        return $this->success(['list'=>$items,'count'=>$count]);
    }

}