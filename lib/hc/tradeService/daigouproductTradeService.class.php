<?php

/**
 * Class daigouproductTradeService
 * version:1.0
 */
class daigouproductTradeService extends tradeService
{

    private $prefix_cdn = "http://shihuoproxy.hupucdn.com/";

    /**
     *
     * 代购获取详情内容
     * @return array
     *
     */
    public function executeDetailGet()
    {
        $product_id = $this->getRequest()->getParameter('product_id', '');
        $goods_id = $this->getRequest()->getParameter('goods_id', '');
        $platform = $this->getRequest()->getParameter('platform', 'pc');
        $from = $this->getRequest()->getParameter('from');
        $flag = $this->getRequest()->getParameter('flag', false);//flag=false,如果商品删除则返回错误，flag=true,仍然返回相关的信息
        if (empty($product_id)) {
            return $this->error(400, '参数错误');
        }
        $product_info = TrdProductAttrTable::getInstance()->find($product_id);
        if (!$product_info && ($flag || (!$flag &$product_info& (!$product_info->getShowFlag() || $product_info->getStatus() == 1)))) {
            return $this->error(401, '不存在该条记录');
        }
        $soldOut = false;

        if($goods_id){//指定获取某个商品
            $goodsInfo = TrdHaitaoGoodsTable::getInstance()->createQuery('m')->select('*')->where('m.id = ?',$goods_id)->andWhere('m.product_id = ?',$product_id)->limit(1)->fetchOne();
            if ($goodsInfo && $goodsInfo->getStatus() == 1) {
                $soldOut = true;
            }
        }
        if(empty($goods_id) || !$goodsInfo) {//随机获取商品
            $goodsInfo = TrdHaitaoGoodsTable::getInstance()->createQuery('m')->select('*')->where('m.product_id = ?',$product_id)->andWhere('m.status = 0')->limit(1)->fetchOne();
            if (!$goodsInfo) {
                if($product_info->getGoodsId()){
                    $goodsInfo = TrdHaitaoGoodsTable::getInstance()->createQuery('m')->select('*')->where('m.id = ?',$product_info->getGoodsId())->andWhere('m.status = 0')->limit(1)->fetchOne();
                    if ($goodsInfo && $goodsInfo->getProductId()){
                        $product_id = $goodsInfo->getProductId();
                        $product_info = TrdProductAttrTable::getInstance()->find($product_id);
                        if (!$product_info && ($flag || (!$flag && (!$product_info->getShowFlag() || $product_info->getStatus() == 1)))) {
                            return $this->error(401, '不存在该条记录');
                        }
                    }
                }
            }
            if (!$goodsInfo) {return $this->error(401, '不存在该条记录');}
            $goods_id = $goodsInfo->getId();
        }

        if (!$goodsInfo) {
            return $this->error(401, '不存在该条记录');
        } elseif ($goodsInfo->getStatus() == 1) {
            $status = 405;
        } else {
            $status = 200;
        }
        $goods_attr = json_decode($goodsInfo->getAttr(), true);

        //获取图片集合
        foreach ($goods_attr['ImageSets']['ImageSet'] as $k => $v) {
            if (preg_match('/images-amazon.com/', $v['LargeImage']['URL'])) {
                $pictures[$k]['image'] = $this->prefix_cdn . $this->url_base64_encode($v['LargeImage']['URL'] . '_SS500_.jpg');
            } else {
                $pictures[$k]['image'] = $this->prefix_cdn . $this->url_base64_encode($v['LargeImage']['URL']);
            }
        }

        if ($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'JPY') {
            $rate = TrdHaitaoCurrencyExchangeTable::getRate('jpy');
            $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate * 100) / 100;
        } elseif($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'USD') {
            $rate = TrdHaitaoCurrencyExchangeTable::getRate();
            $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate) / 100;
        } else {
            $rate = 1;
            $price = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'];
        }

        //获取价格
        $exchange = $goods_attr['Offers']['Offer']['OfferListing']['Price']['FormattedPrice'];//外币假
        $exchange = preg_replace("/([$]|￥|,| )/",'',$exchange);
        $name = $goods_attr['ASIN'];
        $attr_val = array();
        if (isset($goods_attr['VariationAttributes']['VariationAttribute']) && !empty($goods_attr['VariationAttributes']['VariationAttribute'])) {
            foreach ($goods_attr['VariationAttributes']['VariationAttribute'] as $k => $v) {
                $attr_val[$k]['Name'] = $v['Name'];
                $attr_val[$k]['Value'] = $v['Value'];
            }
        }
        $content = json_decode($product_info->getContent(), true);
        $attr = $platform == 'pc' ? $this->getFormatData($content, $product_id, $attr_val, $from) : '';

        //计算美国运费
        $usa_freight = 0;
        if ($product_info->getBusiness() == '6pm'){
            $usa_freight = ceil(4.95 * $rate * 100) / 100;
        }

        $limit = $product_info->getLimits();
        //limit 判断
        if (substr($goodsInfo->getGoodsId(), 0, 2) == 'cn'){
            if ($goodsInfo->getTotalNum() < $limit){
                $limit = $goodsInfo->getTotalNum();
            }
        }
        if ($limit == 0) $soldOut = true;

        //是否是识货自营
        $is_self_business = in_array($product_info->getBusiness(), TrdProductAttrTable::$zhifa_business_arr) ? true : false;
        $return = array(
            'soldOut' => $soldOut,
            'product_id' => $product_id,
            'goods_id' => $goods_id,
            'title' => $product_info->getTitle(),
            'original_cost' => $product_info->getOriginalCost(),
            'original_price' => ceil($product_info->getOriginalCost() * $rate * 100) / 100,
            'attr' => $attr,
            'business' => $product_info->getBusiness(),
            'limit' => $limit,
            'freight' => $product_info->getDetailFreight(),
            'usa_freight' => $usa_freight,//美国本土运费
            'shaiwu_count' => $product_info->getShaiwuCount(),
            'comment_count' => $product_info->getCommentCount(),
            'comment_count_img' => $product_info->getCommentCountImg(),
            'collect_count' => $product_info->getCollectCount(),
            'exchange' => $exchange,
            'price' => $price,
            'url' => $product_info->getUrl(),
            'tags_attr' => json_decode($product_info->getTagsAttr(), true),
            'pictures' => $pictures,
            'name' => $name,
            'praise' => $product_info->getPraise(),
            'intro' => $product_info->getIntro(),
            'memo' => $product_info->getMemo(),
            'purchase_flag' => $product_info->getPurchaseFlag(),
            'attr_val' => $attr_val,
            'merchant' => $goods_attr['Offers']['Offer']['Merchant']['Name'],
            'last_crawl_date' => $product_info->getLastCrawlDate(),
            'currency_code' => $goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'],
            'status' => !$product_info->getShowFlag() || $product_info->getStatus() == 1 ? 1 : 0,
            'is_self_business' => $is_self_business,//是否是自营 true or false
        );

        return $this->success($return, $status, '获取详情成功');
    }

    /**
     * 更新海淘商品的属性
     * @param int product_id
     * @param int time 大于{time}内需要更新
     * @param int timeOut  curl 超时时间
     * @return array
     */
    public function executeUpdateProductInfo()
    {
        $v = $this->getRequest()->getParameter('version');
        $product_id = $this->getRequest()->getParameter('product_id');
        $time = (int)$this->getRequest()->getParameter('time', 900);
        $timeOut = (int)$this->getRequest()->getParameter('timeOut', 5);

        if (!$product_id) {
            return $this->error(400, '参数错误');
        }
        $product_info = TrdProductAttrTable::getInstance()->find($product_id);
        if (!$product_info) {
            return $this->error(400, '参数错误');
        }

        if ($product_info->getLastCrawlDate() + $time > time()) {
            return $this->error(401, '暂无更新');
        }

        if (in_array($product_info->getBusiness(), TrdProductAttrTable::$zhifa_business_arr)){
            //在抓取前更新时间 相同商品的更新量
            $product_info->setLastCrawlDate(time());
            $product_info->save();
            return $this->success();
        }
        $item_url = $product_info->getUrl();
        $web_prefix = tradeCommon::getDaigouPrefix($item_url);
        if (!$web_prefix) {
            return $this->error(402, '暂不支持该网站抓取');
        }

        //在抓取前更新时间 相同商品的更新量
        $product_info->setLastCrawlDate(time());
        $product_info->save();

        //抓取时间太长 关闭数据库连接不然会超时
        TrdProductAttrTable::getInstance()->getConnection()->close();

        $urls = tradeCommon::getHaitaoRemoteIp($item_url);
        $data = $this->curl($urls, $timeOut);

        //记录抓取日志
        $message = array(
            'message'=>"海淘商品抓取1",
            'param'=>$data,
            'res' => array(),
            'order_number'=>'haitao' . $product_id,
        );
        tradeLog::info('productUpdate', $message);

        if ($data['info']['http_code'] == 200) {
            $content = json_decode($data['result'], true);
        } else {
            $product_info = TrdProductAttrTable::getInstance()->find($product_id);
            $product_info->setShowFlag(0);
            $product_info->save();
            return $this->error(402, '暂不支持该网站抓取');
        }
        if (!isset($content) && empty($content)) {
            $product_info = TrdProductAttrTable::getInstance()->find($product_id);
            $product_info->setShowFlag(0);
            $product_info->save();
            return $this->error(402, '暂不支持该网站抓取');
        }
        $res = $this->saveProductAttr($content, $product_id, $web_prefix);
        if ($res['status']) {
            return $this->error(403, $res['msg']);
        } else {
            return $this->success();
        }
    }

    /**
     * 获取最新代购商品价格
     * @param int product_id
     * @param int goods_id
     * @param int num
     * @param int time 大于{time}内需要更新
     * @param int timeOut  curl 超时时间
     * @return array
     */
    public function executeGetChangedProduct()
    {
        $product_id = $this->getRequest()->getParameter('product_id');
        $goods_id = $this->getRequest()->getParameter('goods_id');
        $num = (int)$this->getRequest()->getParameter('num', 1);
        $time = (int)$this->getRequest()->getParameter('time', 900);
        $timeOut = (int)$this->getRequest()->getParameter('timeOut', 5);

        if (!$product_id) {
            return $this->error(400, '参数错误');
        }
        $product_info = TrdProductAttrTable::getInstance()->find($product_id);
        if (!$product_info) {
            return $this->error(400, '参数错误');
        }
        if (!$goods_id) {
            return $this->error(400, '参数错误');
        }
        $goodsObj = TrdHaitaoGoodsTable::getInstance()->find($goods_id);
        if (!$goodsObj) {
            return $this->error(400, '参数错误');
        }

        if ($num && !is_numeric($num)) {
            return $this->error(400, '参数错误');
        }

        if (in_array($product_info->getBusiness(), TrdProductAttrTable::$zhifa_business_arr)){
            //在抓取前更新时间 相同商品的更新量
            $product_info->setLastCrawlDate(time());
            $product_info->save();
        }

        if ($product_info->getLastCrawlDate() + $time < time()) {
            $item_url = $product_info->getUrl();

            $web_prefix = tradeCommon::getDaigouPrefix($item_url);
            if (!$web_prefix) {
                return $this->error(401, '暂不支持该网站抓取');
            }

            //在抓取前更新时间 相同商品的更新量
            $product_info->setLastCrawlDate(time());
            $product_info->save();

            //抓取时间太长 关闭数据库连接不然会超时
            TrdProductAttrTable::getInstance()->getConnection()->close();

            $urls = tradeCommon::getHaitaoRemoteIp($item_url);
            $data = $this->curl($urls, $timeOut);

            //记录抓取日志
            $message = array(
                'message'=>"海淘商品抓取2",
                'param'=>$data,
                'res' => array(),
                'order_number'=>'haitao' . $product_id,
            );
            tradeLog::info('productUpdate', $message);

            if ($data['info']['http_code'] == 200) {
                $content = json_decode($data['result'], true);
            } else {
                $product_info = TrdProductAttrTable::getInstance()->find($product_id);
                $product_info->setShowFlag(0);
                $product_info->save();
                return $this->error(401, '暂不支持该商品抓取');
            }
            if (!isset($content) && empty($content)) {
                $product_info = TrdProductAttrTable::getInstance()->find($product_id);
                $product_info->setShowFlag(0);
                $product_info->save();
                return $this->error(401, '暂不支持该商品抓取');
            }
            $result = $this->saveProductAttr($content, $product_id, $web_prefix);
            if ($result['status']) {
                return $this->error(402, $result['msg']);
            }
            $product_info = TrdProductAttrTable::getInstance()->find($product_id);
        }

        $weight = $product_info->getWeight() ? $product_info->getWeight() : $product_info->getBusinessWeight();
        $res = array();
        $res['freight'] = $this->getAllFreight($weight, $num);

        $goodsObj = TrdHaitaoGoodsTable::getInstance()->find($goods_id);
        $goods_attr = json_decode($goodsObj->getAttr(), 1);

        if ($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'JPY') {
            $rate = TrdHaitaoCurrencyExchangeTable::getRate('jpy');
            $dollar = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'];
            $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate * 100) / 100;
        } elseif($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'USD') {
            $rate = TrdHaitaoCurrencyExchangeTable::getRate();
            $dollar = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] / 100;
            $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate) / 100;
        } else {
            $dollar = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'];
            $price = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'];
        }
        $price = (string)$price * $num;
        $res['price'] = $price;
        $res['dollar'] = $dollar;
        $res['status'] = $goodsObj->getStatus() ? false : true;

        return $this->success($res);
    }


    /**
     * 热门代购
     */
    public function executeHotDaigou() {
        $_limit = $this->getRequest()->getParameter('limit', 4);
        $_id    = $this->getRequest()->getParameter('id', 0);
        $_not_id    = $this->getRequest()->getParameter('not_id', array());

        if(!$_id || !is_numeric($_id))  return $this->error(401, 'id为数字');

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(5);
        $key  = 'trade:haitao:detail:hot:daigou:id:'.$_id.':limit:'.$_limit.'v:2';
        $data = unserialize($redis->get($key));

        if (!$data){
            //dace 接口
            $dace_api = sfConfig::get('app_dace_api');
            $res_json = tradeCommon::requestUrl(
                $dace_api['url']."/dace-api/services/shihuo/buyandbuy.json?itemId={$_id}",
                'GET',NULL,NULL,3
            );

            $data_id = $data = array();
            $res_arr = json_decode($res_json, true);
            if(is_array($res_arr)){
                foreach($res_arr as $res_arr_v){
                    if(isset($res_arr_v['idb']) && !in_array($res_arr_v['idb'] ,$data_id))  $data_id[] = $res_arr_v['idb'];
                }
            }

            //获取详细数据
            if($data_id){
                $data = TrdProductAttrTable::getInstance()->getProductByIds($data_id ,array(
                    'arr'=>'true',
                    'select'=>'id,goods_id,img_path,hits,title,price',
                    'order'=>"FIELD(`ID`,".join(",", $data_id) . ")"
                ));
            }

            //小于合并 配置数据
            $key_recommend_res = 'trade_haitao_list_list_recommend8_res';
            if(!$data_recommend_res = unserialize($redis->get($key_recommend_res))){
                $key_recommend_id  = 'trade_haitao_list_list_recommend8';
                $data_recommend_id = unserialize($redis->get($key_recommend_id));
                $data_recommend_id = array_diff($data_recommend_id, $_not_id);
                $data_recommend_res = TrdProductAttrTable::getInstance()->getProductByIds($data_recommend_id,array(
                        'arr'=>'true','select'=>'id,goods_id,img_path,hits,title,price',
                        'order'=>"FIELD(`ID`,".join(",", $data_recommend_id) . ")")
                );

                $redis->set($key_recommend_res, serialize($data_recommend_res) , 600);
            }
            $data = array_merge($data, $data_recommend_res);

            $redis->set($key,  serialize($data), 600*3);
        }

        //去重
        $data_new_id = array();
        foreach($data as $data_k=>$data_v){
            if(in_array($data_v['id'] ,$_not_id) || in_array($data_v['id'] ,$data_new_id)){
               unset($data[$data_k]);
            }else{
                $data_new_id[] = $data_v['id'];
            }
        }
        //匹配给予用户数据
        if(count($data) > $_limit && $_limit > 0) {
            $data = array_slice($data, 0, $_limit);
        }

        return $this->success(array('list'=>$data));
    }

    /**
     * 代购尺码
     */
    public function executeGetBrandSize() {
        $brand_id = $this->getRequest()->getParameter('brand_id');
        if(empty($brand_id)) return $this->error(400, '参数错误');
        //判断redis里面是否有
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(1);
        $_key = TrdDaigouBrandSize::$CACHE_KEY.$brand_id;
        $_data = unserialize($redis->get($_key));
        if(empty($_data)) {
            $_data =  TrdDaigouBrandSizeTable::setBrandSize($brand_id);
        }
        if(empty($_data)) return $this->success(array());
        return $this->success(array('list'=>$_data));
    }



    /**
     * 代购最近浏览
     */
    public function executeRecentlyBrowse() {
        //数量
        $_count = 5;
        $browse_arr = array();
        //当前id
        $product_id = $this->getRequest()->getParameter('product_id');
        //获取最近浏览
        $browse_ids = FunBase::SiteCookie('daigou_recently_browse');

        if(!empty($browse_ids)) $browse_arr = explode(',', $browse_ids);

        //判断是否重复
        if(!in_array($product_id,$browse_arr) && !empty($product_id)) {

            array_push($browse_arr,$product_id);
            while(count($browse_arr) > $_count) {
                array_shift($browse_arr);
            }
            $browse_ids =  implode(',',$browse_arr);
            $browse_arr = explode(',',$browse_ids);
        }
        if(empty($browse_ids)) return $this->success(array('list'=>array()));

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(1);
        $_list = array();
        //获取商品
        foreach($browse_arr as $k=>$v) {
            if($v == $product_id) continue;
            $_key = 'shihuo.daigou.recently_browse.'.$v;
            $_data = $redis->get($_key);
            if (isset($_data) && !empty($_data)){
                $_list[] = unserialize($_data);
            } else {
                $_data = TrdProductAttrTable::getOneById($v,true,null,'id,goods_id,img_path,hits,title,price');
                if(!empty($_data)) {
                    $redis->set($_key, serialize($_data), 300);
                    $_list[] = $_data;
                } else {
                    unset($browse_arr[$k]);
                    $browse_ids =  implode(',',$browse_arr);
                }
            }
        }
        FunBase::SiteCookie('daigou_recently_browse',$browse_ids);
        return $this->success(array('list'=>$_list));
    }



    /**
     * 获取最新代购商品价格
     * @param int product_id
     * @param int goods_id
     * @param int num
     * @param int time 大于{time}内需要更新
     * @param int timeOut  curl 超时时间
     * @return array
     */
    public function executeUpdateCartProductInfo()
    {
        $product_id = $this->getRequest()->getParameter('product_id');
        $data_array = $this->getRequest()->getParameter('data');
        $time = (int)$this->getRequest()->getParameter('time', 900);
        $timeOut = (int)$this->getRequest()->getParameter('timeOut', 20);

        if (!$product_id || empty($data_array)) {
            return $this->error(400, '参数错误');
        }
        if (!isset($data_array['goods_id']) || (isset($data_array['goods_id']) && empty($data_array['goods_id']))) {
            return $this->error(400, '参数错误');
        }

        $product_info = TrdProductAttrTable::getInstance()->find($product_id);
        if (!$product_info) {
            return $this->error(400, '参数错误');
        }
        if (in_array($product_info->getBusiness(), TrdProductAttrTable::$zhifa_business_arr)){
            //在抓取前更新时间 相同商品的更新量
            $product_info->setLastCrawlDate(time());
            $product_info->save();
        }

        $res = array();
        if ($product_info->getLastCrawlDate() + $time > time()) {
            $weight = $product_info->getWeight() ? $product_info->getWeight() : $product_info->getBusinessWeight();
            $res['weight'] = $weight;
            $res['freight'] = empty($weight) ? 0 : $this->getAllFreight($weight, $data_array['number']);


            $goodsObj = TrdHaitaoGoodsTable::getInstance()->find($data_array['goods_id']);
            $old_price = $data_array['price'];
            $goods_attr = json_decode($goodsObj->getAttr(), 1);
            if ($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'JPY') {
                $rate = TrdHaitaoCurrencyExchangeTable::getRate('jpy');
                $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate * 100) / 100;
            } elseif($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'USD') {
                $rate = TrdHaitaoCurrencyExchangeTable::getRate();
                $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate) / 100;
            } else {
                $price = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'];
            }
            $price = (string)$price * $data_array['number'];
            $res['old_price'] = $old_price;
            $res['price'] = $price;
            $res['flag'] = true;
            if (intval($price * 100) == intval($old_price * 100)) {//必须先转为整型再比较，否则浮点数直接比较会出错
                $res['flag'] = false;
            }
            $res['change'] = (intval($price * 100) - intval($old_price * 100)) / 100;
            $res['status'] = $goodsObj->getStatus() ? true : false;
        }
        $item_url = $product_info->getUrl();
        $web_prefix = tradeCommon::getDaigouPrefix($item_url);
        if (!$web_prefix) {
            return $this->error(401, '暂不支持该网站抓取');
        }

        //在抓取前更新时间 相同商品的更新量
        $product_info->setLastCrawlDate(time());
        $product_info->save();

        //抓取时间太长 关闭数据库连接不然会超时
        TrdProductAttrTable::getInstance()->getConnection()->close();

        $urls = tradeCommon::getHaitaoRemoteIp($item_url);
        $data = $this->curl($urls, $timeOut);

        //记录抓取日志
        $message = array(
            'message'=>"海淘商品抓取3",
            'param'=>$data,
            'res' => array(),
            'order_number'=>'haitao' . $product_id,
        );
        tradeLog::info('productUpdate', $message);

        if ($data['info']['http_code'] == 200) {
            $content = json_decode($data['result'], true);
        } else {//$data['error']
            $product_info = TrdProductAttrTable::getInstance()->find($product_id);
            $product_info->setShowFlag(0);
            $product_info->save();
            return $this->error(401, '暂不支持该网站抓取');
        }
        if (!isset($content) || empty($content)) {
            $product_info = TrdProductAttrTable::getInstance()->find($product_id);
            $product_info->setShowFlag(0);
            $product_info->save();
            return $this->error(401, '暂不支持该网站抓取');
        }
        $result = $this->saveProductAttr($content, $product_id, $web_prefix);
        if ($result['status']) {
            //return $this->error(402, $result['msg']);
        }
        $product_info = TrdProductAttrTable::getInstance()->find($product_id);
        $weight = $product_info->getWeight() ? $product_info->getWeight() : $product_info->getBusinessWeight();
        $res['weight'] = $weight;
        $res['freight'] = empty($weight) ? 0 : $this->getAllFreight($weight, $data_array['number']);

        $goodsObj = TrdHaitaoGoodsTable::getInstance()->find($data_array['goods_id']);

        $old_price = $data_array['price'];
        $goods_attr = json_decode($goodsObj->getAttr(), 1);
        if ($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'JPY') {
            $rate = TrdHaitaoCurrencyExchangeTable::getRate('jpy');
            $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate * 100) / 100;
        } elseif($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'USD') {
            $rate = TrdHaitaoCurrencyExchangeTable::getRate();
            $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate) / 100;
        } else {
            $price = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'];
        }
        $price = (string)$price * $data_array['number'];
        $res['old_price'] = $old_price;
        $res['price'] = $price;
        $res['flag'] = true;
        if (intval($price * 100) == intval($old_price * 100)) {//必须先转为整型再比较，否则浮点数直接比较会出错
            $res['flag'] = false;
        }
        $res['change'] = (intval($price * 100) - intval($old_price * 100)) / 100;
        $res['status'] = $goodsObj->getStatus() ? true : false;

        return $this->success($res);
    }

    //处理属性数据
    private function getFormatData($data, $pid, $attr_val, $from = '')
    {
        $return = array();
        if (empty($attr_val)) {
            return $return;
        }
        foreach ($attr_val as $k => $v) {
            foreach ($data[$v['Name']] as $kk => $vv) {
                foreach ($data['content'] as $kkk => $vvv) {
                    if (count($attr_val) == 2) {//如果有两个属性
                        $m = $k == 0 ? 1 : 0;
                        if ($v['Value'] == $vv && $v['Value'] == $vvv[$v['Name']] && $attr_val[$m]['Value'] == $vvv[$attr_val[$m]['Name']]) {//选中的
                            $return[$v['Name']][$kk]['url'] = $this->getUrlParam("http://www.shihuo.cn/haitao/buy/" . $pid . "-" . $vvv['gid'] . ".html",
                                $from);
                            if ($v['Name'] == 'Color') {
                                if (preg_match('/images-amazon.com/', $vvv['img'])) {
                                    $return[$v['Name']][$kk]['img'] = $this->prefix_cdn . $this->url_base64_encode($vvv['img'] . '_SS500_.jpg');
                                } else {
                                    $return[$v['Name']][$kk]['img'] = $this->prefix_cdn . $this->url_base64_encode($vvv['img']);
                                }
                            }
                            $return[$v['Name']][$kk][$v['Name']] = $vvv[$v['Name']];
                            $return[$v['Name']][$kk]['choosed'] = 1;
                            $return[$v['Name']][$kk]['have'] = 1;
                            break;
                        }
                        if ($vv == $vvv[$v['Name']] && $attr_val[$m]['Value'] == $vvv[$attr_val[$m]['Name']]) {//没选中，相同尺寸，可选中其他颜色
                            $return[$v['Name']][$kk]['url'] = $this->getUrlParam("http://www.shihuo.cn/haitao/buy/" . $pid . "-" . $vvv['gid'] . ".html",
                                $from);
                            if ($v['Name'] == 'Color') {
                                if (preg_match('/images-amazon.com/', $vvv['img'])) {
                                    $return[$v['Name']][$kk]['img'] = $this->prefix_cdn . $this->url_base64_encode($vvv['img'] . '_SS500_.jpg');
                                } else {
                                    $return[$v['Name']][$kk]['img'] = $this->prefix_cdn . $this->url_base64_encode($vvv['img']);
                                }
                            }
                            $return[$v['Name']][$kk][$v['Name']] = $vvv[$v['Name']];
                            $return[$v['Name']][$kk]['choosed'] = 0;
                            $return[$v['Name']][$kk]['have'] = 1;
                            break;
                        }
                        if (!isset($return[$v['Name']][$kk]) && $vv == $vvv[$v['Name']]) {//没选中，没有相同尺寸，颜色不可选，但可点击，默认循环第一个
                            $return[$v['Name']][$kk]['url'] = $this->getUrlParam("http://www.shihuo.cn/haitao/buy/" . $pid . "-" . $vvv['gid'] . ".html",
                                $from);
                            if ($v['Name'] == 'Color') {
                                if (preg_match('/images-amazon.com/', $vvv['img'])) {
                                    $return[$v['Name']][$kk]['img'] = $this->prefix_cdn . $this->url_base64_encode($vvv['img'] . '_SS500_.jpg');
                                } else {
                                    $return[$v['Name']][$kk]['img'] = $this->prefix_cdn . $this->url_base64_encode($vvv['img']);
                                }
                            }
                            $return[$v['Name']][$kk][$v['Name']] = $vvv[$v['Name']];
                            $return[$v['Name']][$kk]['choosed'] = 0;
                            $return[$v['Name']][$kk]['have'] = 0;
                        }
                    } else {
                        if (count($attr_val) == 1) {//如果有1个属性
                            $return[$v['Name']][$kkk]['url'] = $this->getUrlParam("http://www.shihuo.cn/haitao/buy/" . $pid . "-" . $vvv['gid'] . ".html",
                                $from);
                            if ($v['Name'] == 'Color') {
                                if (preg_match('/images-amazon.com/', $vvv['img'])) {
                                    $return[$v['Name']][$kkk]['img'] = $this->prefix_cdn . $this->url_base64_encode($vvv['img'] . '_SS500_.jpg');
                                } else {
                                    $return[$v['Name']][$kkk]['img'] = $this->prefix_cdn . $this->url_base64_encode($vvv['img']);
                                }
                            }
                            $return[$v['Name']][$kkk][$v['Name']] = $vvv[$v['Name']];
                            $return[$v['Name']][$kkk]['choosed'] = $vvv[$v['Name']] == $v['Value'] ? 1 : 0;
                            $return[$v['Name']][$kkk]['have'] = 1;
                        }
                    }
                }
                if (count($attr_val) == 1) {//如果只有一个属性 不需要循环多次
                    break;
                }
            }
        }

        return $return;
    }

    private function url_base64_encode($bin)
    {
        $base64 = base64_encode($bin);
        $base64 = str_replace('+', '-', $base64);
        $base64 = str_replace('/', '_', $base64);
        $base64 = str_replace('=', '', $base64);

        return $base64;
    }

    //拼接url
    private function getUrlParam($url, $from)
    {
        if (!$from) {
            return $url;
        }
        $url .= ((strpos($url, '?') !== false) ? '&' : '?');

        return $url . 'from=' . $from;
    }

    /**
     *
     * 保存抓取的远程商品内容
     * @param object $content 属性集合
     * @param int $product_id 商品id
     * @param string $web_prefix 前缀
     * @param boolen $purchaseFlag false 表示更新 true 表示一键购
     * @return array
     */
    private function saveProductAttr($content, $product_id, $web_prefix, $purchaseFlag = false)
    {
        //更新goods表状态
        TrdHaitaoGoodsTable::updateStatusByProductId($product_id);
        $json_string = "";
        $low_price = $low_gid = 0;
        $business_weight = 0;
        if (isset($content['ItemAttributes']['PackageDimensions']['Weight']['Num']) && !empty($content['ItemAttributes']['PackageDimensions']['Weight']['Num'])) {
            $business_weight = $content['ItemAttributes']['PackageDimensions']['Weight']['Num'] / 100;
        }
        if (isset($content['Variations']['VariationDimensions']['VariationDimension']) && !empty($content['Variations']['VariationDimensions']['VariationDimension'])) {
            $VariationDimension = (array)$content['Variations']['VariationDimensions']['VariationDimension'];
            foreach ($VariationDimension as $k => $v) {//存储属性
                if (trim($v)) $json_string['attr'][] = trim($v);
            }
            $json_arr = array();
            foreach ($content['items'] as $k => $v) {//存储属性
                $flag = false;
                $json_attr_str = '';
                if (isset($v['Offers']['Offer']['OfferListing']['IsEligibleForSuperSaverShipping'])
                    && isset($v['Offers']['Offer']['Merchant']['Name'])
                    && ($v['Offers']['Offer']['OfferListing']['IsEligibleForSuperSaverShipping'] == 1
                        || $v['Offers']['Offer']['Merchant']['Name'] == 'Under Armour'
                        || $v['Offers']['Offer']['Merchant']['Name'] == 'Amazon.com'
                        || $v['Offers']['Offer']['Merchant']['Name'] == '6PM'
                        || $v['Offers']['Offer']['Merchant']['Name'] == 'GNC'
                        || $v['Offers']['Offer']['Merchant']['Name'] == 'Levis')
                    && isset($v['Offers']['Offer']['OfferListing']['Price']['Amount'])
                ) {

                    if (isset($v['LargeImage']['URL']) && !empty($v['LargeImage']['URL'])) {//只存储有图片的
                        $json_string['content'][$k]['name'] = $v['ASIN'];
                        if (empty($v['LargeImage']['URL']) && isset($v['ImageSets']['ImageSet'][0]['LargeImage']['URL'])) {
                            $v['LargeImage']['URL'] = $v['ImageSets']['ImageSet'][0]['LargeImage']['URL'];
                        }
                        $json_string['content'][$k]['img'] = isset($v['LargeImage']['URL']) && !empty($v['LargeImage']['URL']) ? $v['LargeImage']['URL'] : 'http://www.shihuo.cn/images/trade/no-image.png';
                        foreach ($v['VariationAttributes']['VariationAttribute'] as $kk => $vv) {
                            if (!isset($vv['Value'])) {
                                $flag = true;
                                unset($json_string['content'][$k]);
                                break;
                            }
                            if (!isset($json_string[$vv['Name']]) || (isset($json_string[$vv['Name']]) && !in_array($vv['Value'],
                                        $json_string[$vv['Name']]))
                            ) {
                                $json_string[$vv['Name']][] = $vv['Value'];
                            }
                            $json_string['content'][$k][$vv['Name']] = $vv['Value'];
                            $json_attr_str .= $vv['Value'];
                        }
                        if ($flag) {
                            continue;
                        }
                        $json_string['content'][$k]['price'] = $v['Offers']['Offer']['OfferListing']['Price']['Amount'];
                        $json_arr_key = md5($json_attr_str);
                        //是否有重复属性的数据判断 取价格大的
                        if (isset($json_arr[$json_arr_key]) && $json_arr[$json_arr_key]['price'] >= $json_string['content'][$k]['price']) {
                            unset($json_string['content'][$k]);
                            break;
                        } else {
                            if (isset($json_arr[$json_arr_key])) {
                                unset($json_string['content'][$json_arr[$json_arr_key]['id']]);
                            }
                            $json_arr[$json_arr_key]['price'] = $json_string['content'][$k]['price'];
                            $json_arr[$json_arr_key]['id'] = $k;
                        }
                        $json_string['content'][$k]['code'] = $v['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'];
                        if (isset($v['ImageSets']['ImageSet']) && !empty($v['ImageSets']['ImageSet'])) {//处理图片
                            $v['ImageSets']['ImageSet'] = $this->_formatImages($v['ImageSets']['ImageSet']);
                            if (count($v['ImageSets']['ImageSet']) > 5) {
                                $v['ImageSets']['ImageSet'] = array_slice($v['ImageSets']['ImageSet'], 0, 5);
                            }
                        } else {
                            $v['ImageSets']['ImageSet'][0]['LargeImage']['URL'] = 'http://www.shihuo.cn/images/trade/no-image.png';
                        }
                        //保存到商品表
                        tradeCommon::getLock('updateHaitaoGood.' . 'usa.amazon.' . $v['ASIN'], 5);
                        $goods = TrdHaitaoGoodsTable::getInstance()->findOneByGoodsId($web_prefix . $v['ASIN']);
                        if (!$goods) {
                            $goods = new TrdHaitaoGoods();
                        }

                        $goods->setProductId($product_id);
                        $goods->setGoodsId($web_prefix . $v['ASIN']);
                        $goods->setTitle($v['ItemAttributes']['Title']);
                        if (!isset($v['ItemAttributes']['UPC'])) {
                            $v['ItemAttributes']['UPC'] = '';
                        }
                        if (!isset($v['ItemAttributes']['EAN'])) {
                            $v['ItemAttributes']['EAN'] = '';
                        }
                        $code = empty($v['ItemAttributes']['UPC']) ? $v['ItemAttributes']['EAN'] : $v['ItemAttributes']['UPC'];
                        $goods->setCode($code);
                        $goods->setStatus(0);
                        $goods->setAttr(json_encode($v));
                        $goods->save();
                        tradeCommon::releaseLock('updateHaitaoGood.' . 'usa.amazon.' . $v['ASIN']);
                        $json_string['content'][$k]['gid'] = $goods->getId();
                        if (!$low_price) {
                            $low_price = $json_string['content'][$k]['price'];
                            $low_gid = $goods->getId();
                            $low_info = $goods->getAttr();
                        } elseif ($low_price > $json_string['content'][$k]['price']) {
                            $low_price = $json_string['content'][$k]['price'];
                            $low_gid = $goods->getId();
                            $low_info = $goods->getAttr();
                        }
                    }
                }
            }
            unset($json_arr);//删除临时数组
            if (!isset($json_string['content'])) {//没有自营商品 下线
                $product_info = TrdProductAttrTable::getInstance()->find($product_id);
                $product_info->setShowFlag(0);
                $product_info->save();

                return array('status' => 1, 'data' => '', 'msg' => 'Amazon did not self-goods');
            }
            sort($json_string['content']);
        }
        if (isset($content['item']) && !empty($content['item'])) {
            if (isset($content['item']['Offers']['Offer']['Merchant']['Name'])
                && ($content['item']['Offers']['Offer']['OfferListing']['IsEligibleForSuperSaverShipping'] == 1
                    || $content['item']['Offers']['Offer']['Merchant']['Name'] == 'Under Armour'
                    || $content['item']['Offers']['Offer']['Merchant']['Name'] == 'Amazon.com'
                    || $content['item']['Offers']['Offer']['Merchant']['Name'] == '6PM'
                    || $content['item']['Offers']['Offer']['Merchant']['Name'] == 'GNC'
                    || $content['item']['Offers']['Offer']['Merchant']['Name'] == 'Levis')
                && isset($content['item']['Offers']['Offer']['OfferListing']['Price']['Amount'])
            ) {

                $json_string['attr'] = '';
                $json_string['content'][0]['name'] = $content['ASIN'];
                if (empty($content['item']['LargeImage']['URL']) && isset($content['item']['ImageSets']['ImageSet'][0]['LargeImage']['URL'])) {
                    $content['item']['LargeImage']['URL'] = $content['item']['ImageSets']['ImageSet'][0]['LargeImage']['URL'];
                }
                $json_string['content'][0]['img'] = isset($content['item']['LargeImage']['URL']) && !empty($content['item']['LargeImage']['URL']) ? $content['item']['LargeImage']['URL'] : 'http://www.shihuo.cn/images/trade/no-image.png';
                $json_string['content'][0]['price'] = $content['item']['Offers']['Offer']['OfferListing']['Price']['Amount'];
                $json_string['content'][0]['code'] = $content['item']['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'];

                if (isset($content['item']['ImageSets']['ImageSet']) && !empty($content['item']['ImageSets']['ImageSet'])) {//处理图片
                    $content['item']['ImageSets']['ImageSet'] = $this->_formatImages($content['item']['ImageSets']['ImageSet']);
                    if (count($content['item']['ImageSets']['ImageSet']) > 5) {
                        $content['item']['ImageSets']['ImageSet'] = array_slice($content['item']['ImageSets']['ImageSet'],
                            0, 5);
                    }
                } else {
                    $content['item']['ImageSets']['ImageSet'][0]['LargeImage']['URL'] = 'http://www.shihuo.cn/images/trade/no-image.png';
                }
                //保存到商品表
                tradeCommon::getLock('updateHaitaoGood.' . 'usa.amazon.' . $content['item']['ASIN'], 5);
                $goods = TrdHaitaoGoodsTable::getInstance()->findOneByGoodsId($web_prefix . $content['item']['ASIN']);
                if (!$goods) {
                    $goods = new TrdHaitaoGoods();
                }
                $goods->setProductId($product_id);
                $goods->setGoodsId($web_prefix . $content['item']['ASIN']);
                $goods->setTitle($content['item']['ItemAttributes']['Title']);
                if (!isset($content['item']['ItemAttributes']['UPC'])) {
                    $content['item']['ItemAttributes']['UPC'] = '';
                }
                if (!isset($content['item']['ItemAttributes']['EAN'])) {
                    $content['item']['ItemAttributes']['EAN'] = '';
                }
                $code = empty($content['item']['ItemAttributes']['UPC']) ? $content['item']['ItemAttributes']['EAN'] : $content['item']['ItemAttributes']['UPC'];
                $goods->setCode($code);
                $goods->setStatus(0);
                $goods->setAttr(json_encode($content['item']));
                $goods->save();
                tradeCommon::releaseLock('updateHaitaoGood.' . 'usa.amazon.' . $content['item']['ASIN']);
                $json_string['content'][0]['gid'] = $goods->getId();
                if (!$low_price) {
                    $low_price = $json_string['content'][0]['price'];
                    $low_gid = $goods->getId();
                    $low_info = $goods->getAttr();
                } elseif ($low_price > $json_string['content'][0]['price']) {
                    $low_price = $json_string['content'][0]['price'];
                    $low_gid = $goods->getId();
                    $low_info = $goods->getAttr();
                }
            } else {
                $product_info = TrdProductAttrTable::getInstance()->find($product_id);
                $product_info->setShowFlag(0);
                $product_info->save();

                return array('status' => 1, 'data' => '', 'msg' => 'Amazon did not self-goods');
            }
        }
        $product_info = TrdProductAttrTable::getInstance()->find($product_id);
        if (!$product_info) {
            $product_info = new TrdProductAttr();
        }
        $product_info->setName($content['ASIN']);
        $LowestPrice = $low_price;
        if ($web_prefix == 'jp.amazon.') {
            $rate = TrdHaitaoCurrencyExchangeTable::getRate('jpy');
            $price = ceil($LowestPrice*$rate*100)/100;
            $product_info->setExchange($LowestPrice);
        } else {
            $rate = TrdHaitaoCurrencyExchangeTable::getRate();
            $price = ceil($LowestPrice*$rate)/100;
            $product_info->setExchange($LowestPrice/100);
        }
        if ($low_gid) {
            $product_info->setGoodsId($low_gid);
        }
        $product_info->setPrice($price);
        $attr = base64_encode(gzcompress(json_encode($json_string)));
        if (strlen($attr) > 65535) {
            return array('status' => 1, 'data' => '', 'msg' => '抓取商品的属性太长');
        }
        $product_info->setContent($attr);
        $product_info->setCrawlFlag(0);
        $product_info->setShowFlag(1);
        $product_info->setLastCrawlDate(time());
        //保存重量
        if ($business_weight) {
            if ($business_weight >= 0.5) {
                $business_weight += 0.6;
            } else {
                $business_weight += 0.4;
            }
            $product_info->setBusinessWeight($business_weight);
        }
        $product_info->save();
        if ($purchaseFlag && $low_info) {
            return array('status' => 0, 'data' => $low_info, 'msg' => 'success');
        }

        return array('status' => 0, 'data' => '', 'msg' => 'success');
    }

    //图片去重
    private function _formatImages($data)
    {
        if (empty($data)) {
            return $data;
        }
        $return = $image = array();
        foreach ($data as $k => $v) {
            $image[] = $v['LargeImage']['URL'];
        }
        $img = array_unique($image);
        foreach ($img as $kk => $vv) {
            $return[$kk]['LargeImage']['URL'] = $vv;
        }

        return $return;
    }

    /**
     *
     * 获取邮费
     */
    private function getAllFreight($weight, $number = 1, $flag = true)
    {
        if ($flag || $number == 1) {//单件计算价格
            $freight = $weight * 40;
            if ($freight < 46) {
                $freight = 46;
            }
            $res = $freight * $number;
        } else {
            $res = $weight * 32 + 16;
            $res = ceil($res * 100) / 100;
        }
        if ($weight > 1 && $weight <= 2) {
            $res += 2;
        } elseif ($weight > 2 && $weight <= 3) {
            $res += 3;
        } elseif ($weight > 3 && $weight <= 4) {
            $res += 4;
        } elseif ($weight > 4 && $weight <= 5) {
            $res += 5;
        } elseif ($weight > 5) {
            $res += 6;
        }
        if ($res < 46) {
            $res = 46;
        }

        return $res;
    }

    /**
     * @purpose: 使用curl并行处理url
     * @return: array 每个url获取的数据
     * @param: $urls array url列表
     * @param: $callback string 需要进行内容处理的回调函数。示例：func(array)
     */
    private function curl($urls = array(), $timeOut = 20)
    {
        $response = array();
        if (empty($urls)) {
            return $response;
        }
        $chs = curl_multi_init();
        $map = array();
        foreach ($urls as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_NOSIGNAL, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip,deflate'));
            curl_setopt($ch, CURLOPT_ENCODING, '');
            curl_multi_add_handle($chs, $ch);
            $map[strval($ch)] = $url;
        }
        do {
            if (($status = curl_multi_exec($chs, $active)) != CURLM_CALL_MULTI_PERFORM) {
                if ($status != CURLM_OK) {
                    break;
                } //如果没有准备就绪，就再次调用curl_multi_exec
                while ($done = curl_multi_info_read($chs)) {
                    $info = curl_getinfo($done["handle"]);
                    $error = curl_error($done["handle"]);
                    $result = curl_multi_getcontent($done["handle"]);
                    $url = $map[strval($done["handle"])];
                    $rtn = compact('info', 'error', 'result', 'url');
                    if ($rtn && empty($error) && $info['http_code'] != 500) {
                        curl_multi_close($chs);

                        return $rtn;
                        break;
                    }
                    $response = $rtn;
                    curl_multi_remove_handle($chs, $done['handle']);
                    curl_close($done['handle']);
                    //如果仍然有未处理完毕的句柄，那么就select
                    if ($active > 0) {
                        curl_multi_select($chs, 0.5); //此处会导致阻塞大概0.5秒。
                    }
                }
            }
        } while ($active > 0); //还有句柄处理还在进行中
        curl_multi_close($chs);

        return $response;
    }

    /*
   *通过sku ID增减库存
   *
   **/
    public function executeSkuStock()
    {
        $id = $this->getRequest()->getParameter('id', '');
        $num = $this->getRequest()->getParameter('num', 1);
        $type = $this->getRequest()->getParameter('type', '');  //array(1=>'下单成功',2=>'付款成功'，3=>'退款'，4=>'未付款取消')

        #验证
        if(!is_numeric($id) || !is_numeric($num) || !is_numeric($type)) return  $this->error('401','参数不合法');

        #查询
        $res = TrdHaitaoGoodsTable::setSkuStockById($id, $num, $type);
        if($res['status'])
            $return = $res;
        else
            return $this->error('502',$res['message']);

        return $this->success($return);
    }
}