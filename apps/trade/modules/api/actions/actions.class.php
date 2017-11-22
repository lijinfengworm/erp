<?php

/**
 * voice actions.
 *
 * @package    HC
 * @subpackage voice
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class apiActions extends sfActions
{
    /**
     * 根据某个订单获取订单详情 通过快递号 临时用
     * @param sfWebRequest $request
     */
//    public function executeGetOrderInfoByMartExpressNumber(sfWebRequest $request)
//    {
//        sfConfig::set('sf_web_debug', false);
//        $mart_express_number = $request->getParameter('mart_express_number');
//        $orderInfo = TrdOrderTable::getInstance()->createQuery()->select('order_number,sum(express_fee) as fee ,sum(price) as price,sum(marketing_fee) as marketing_fee,sum(refund_price) refund_price,sum(refund_express_fee) refund_express_fee,order_time')
//            ->andWhere('mart_express_number = ?', $mart_express_number)
//            ->groupBy('mart_express_number')
//            ->fetchArray();
//        $mainOrderInfo = TrdMainOrderTable::getInstance()->createQuery()->select('coupon_fee')
//            ->andWhere('order_number = ?', $orderInfo[0]['order_number'])
//            ->fetchArray();
////        print_r($orderInfo['order_number']);
//        return $this->renderText(json_encode(array_merge($orderInfo[0],$mainOrderInfo[0])));
//    }

    public function executeEditProductInfo(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        $id = $request->getPostParameter('id');
        $img_path = $request->getPostParameter('img_path');
        $root_id = $request->getPostParameter('root_id');
        $children_id = $request->getPostParameter('children_id');
        $weight = $request->getPostParameter('weight');
        $title = $request->getPostParameter('title');
        if(empty($id) || empty($img_path) || empty($root_id) || empty($weight) || empty($title))
        {
            return $this->renderText(json_encode(array('status'=>500,'msg'=>'params is error')));
        }
        $pro = TrdProductAttrTable::getInstance()->createQuery()->select()->where('id = ?',$id)->fetchOne();
        $pro->setTitle($title);
        $pro->setWeight($weight);
        $pro->setRootId($root_id);
        $pro->setChildrenId($children_id);
        $pro->setImgPath($img_path);
        $pro->save();
        return $this->renderText(json_encode(array('status'=>200,'msg'=>'success')));
    }
    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeVoice(sfWebRequest $request)
    {
        $this->setLayout(false);
        $tagsTable = TrdTagsTable::getInstance();
        $tagItemsTable = TrdTagItemsTable::getInstance();
        $tags_str = urldecode($request->getParameter('tags', ""));
        $show_tags = array();

        $this->show_text = false;
        if ($request->getParameter('show_text')) {
            $this->show_text = true;
        }

        $tag_items = array();

        //先查询对应标签的商品，按照标签顺序依次查询
        if ($tags_str != "") {
            $tags = explode(",", $tags_str);

            //退出循环的tag
            $tags_out = array();
            $offset = 0;

            while (count($tag_items) < 10) {
                if (count($tags) == count($tags_out)) {
                    break;
                }

                foreach ($tags as $tag) {
                    if (in_array($tag, $tags_out) || count($tag_items) >= 10) {
                        continue;
                    }
                    $item = $tagItemsTable->getItemByTagForVoice($tag, $offset, $this->getCurrIds($tag_items));
                    if ($item) {
                        $tag_items[] = $item;
                        $show_tags[$tag] = $tagsTable->byName($tag);

                    } else {
                        $tags_out[] = $tag;
                    }

                }

            }

            $offset++;

        }

        //如果商品不够，补足10个
        if (count($tag_items) < 10) {
            $items = $tagItemsTable->getItemByTagForVoiceDefault(10 - count($tag_items), $this->getCurrIds($tag_items));
            foreach ($items as $item) {
                $tag_items[] = $item;
            }
        }

        $this->items = $tag_items;
        $this->show_tags = $show_tags;
        $this->getResponse()->setTitle($tags_str);
    }

    private function getCurrIds($items)
    {
        $item_all_id_arr = array();
        foreach ($items as $item) {
            if ($item["item_all_id"]) {
                $item_all_id_arr[] = $item["item_all_id"];
            }
        }

        return join(",", $item_all_id_arr);
    }

    //获取app用户信息
    public function executeGetClientUserInfo(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        $type = $request->getParameter('type', 1);
        $key = 'trade_app_client_info_' . $type;
        $key_cache = tradeApiMessageManager::setCacheKey($key);
        $handle = tradeApiMessageManager::getCache();
        $info = $handle->get($key);

        if (!$handle->get($key_cache) || !$info) {
            $info = array();
            $minLastVisit = strtotime('-90 day');
            $query = TrdClientInfoTable::getInstance()->createQuery('t')
                ->select('t.client_str,t.client_token,wp_url')
                ->where('t.status  = ?', 0)
                ->andWhere('t.push_switch = ?', 0)
                ->andWhere('t.last_virst > ?', $minLastVisit);
            if ($type) $query = $query->andWhere('t.type = ?', $type);
            $num = $query->count();
            $data = $query->fetchArray();
            $info = array('total' => $num, 'data' => $data);
            tradeApiMessageManager::setCacheValues($key_cache, $key, $info, 600);
            unset($data);
        }
        return $this->renderText(json_encode(array('status' => 200, 'msg' => 'ok', 'data' => $info)));
    }

    //获取要发送的任务
    public function executeGetClientMessage(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        $type = $request->getParameter('type', 1);
        $message = TrdMessageTable::getInstance()->createQuery()->where('status = ?', 0)->andWhere('type = ?', $type)->andWhere('is_delete = ?', 0)->limit(1)->fetchOne();
        $mess = '';
        if (!empty($message)) {
            $mess = $message->toArray();
        }
        return $this->renderText(json_encode(array('status' => 200, 'msg' => 'ok', 'data' => $mess)));
    }

    //更新已推送完的任务
    public function executeSaveClientMessage(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        $id = $request->getParameter('id', '');
        if (!$id) return $this->renderText(json_encode(array('status' => 500, 'msg' => '参数错误')));
        $msgObj = TrdMessageTable::getInstance()->findOneById($id);
        $msgObj->setStatus(1);
        $msgObj->save();
        return $this->renderText(json_encode(array('status' => 200, 'msg' => 'ok', 'data' => '')));
    }

    //发现频道的数据搜索 给社区js调用
    public function executeSearchFind(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        $w = $this->qihuSafeFilter($request->getParameter('w', ''));
        $limit = $request->getParameter('limit', 2);
        $jsoncallback = $request->getParameter('jsoncallback'); //jsonp格式处理

        if (empty($w))
            return $this->renderText($jsoncallback . '(' . json_encode(array('status' => 1, 'msg' => '关键词参数错误')) . ')');
        if (!is_numeric($limit) || (int)$limit < 1)
            return $this->renderText($jsoncallback . '(' . json_encode(array('status' => 1, 'msg' => '获取数参数错误')) . ')');

        $data = array();
        $info = '';
        $word = explode(' ', trim($w));
        foreach ($word as $k => $v) {
            $info .= '@title ' . $v . ' ';
        }
        $info = trim($info);
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $key = 'trade_api_find_sphinx_' . md5($info) . '_' . $limit;
        $data = $redis->get($key);
        if ($data) return $this->renderText($jsoncallback . '(' . unserialize($data) . ')');
        $mysphinxDb = new tradeSpinxMysql();
        $sort = 'time desc';
        $res = $mysphinxDb->search(0, $limit, $info, $sort);

        $ids = $res["data"];
        $itemAllTable = TrdItemAllTable::getInstance();
        if (isset($ids) && !empty($ids)) { //按sort去mysql中查找
            $sort_new = 'publish_date desc';
            $items = $itemAllTable->getByIds($ids, $sort_new);
        }
        $go_url = sfConfig::get('app_api');
        if (!empty($items)) {
            foreach ($items as $k => $v) {
                $data[$k]['id'] = $v['id'];
                $data[$k]['title'] = $v['title'];
                $data[$k]['price'] = $v['price'];
                $data[$k]['go_url'] = $go_url['go']['url'] . '?url=' . urlencode($v['url']);
                $data[$k]['detail_url'] = "http://www.shihuo.cn/detail/" . $v['id'] . ".html";
                $data[$k]['intro'] = $v['memo'];
                $data[$k]['width'] = 150;
                $data[$k]['height'] = $v['height'] && $v['width'] ? intval(round(150 * ($v['height']) / ($v['width']))) : 116;
                $data[$k]['img_path'] = 'http://shihuo.hupucdn.com' . $v['img_url'].'-S253.jpg';
                $data[$k]['time'] = $v['publish_date'];
            }
            if (count($data) == 1) {
                $data[$k]['id'] = '';
                $data[$k]['title'] = '';
                $data[$k]['price'] = '';
                $data[$k]['go_url'] = '';
                $data[$k]['detail_url'] = "http://www.shihuo.cn";
                $data[$k]['intro'] = '';
                $data[$k]['width'] = 150;
                $data[$k]['height'] = 116;
                $data[$k]['img_path'] = 'http://c' . mt_rand(1, 2) . '.hoopchina.com.cn/images/trade/logo-150.jpg';
                $data[$k]['time'] = '';
            }
        }
        $data = json_encode(array('status' => 0, 'data' => $data, 'msg' => 'ok'));
        $redis->set($key, serialize($data), 600);
        return $this->renderText($jsoncallback . '(' . $data . ')');
    }

    //运动鞋搜索 给装备php调用
    public function executeSearchSports(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        $w = $this->qihuSafeFilter($request->getParameter('w', ''));
        $root_id = $request->getParameter('r', '');
        $children_id = $request->getParameter('c', '');
        $limit = $request->getParameter('limit', 2);
        $jsoncallback = $request->getParameter('jsoncallback'); //jsonp格式处理
        if (empty($w))
            return $this->renderText($jsoncallback . '(' . json_encode(array('status' => 1, 'msg' => '关键词参数错误')) . ')');
        if (!is_numeric($limit) || (int)$limit < 1)
            return $this->renderText($jsoncallback . '(' . json_encode(array('status' => 1, 'msg' => '获取数参数错误')) . ')');

        $data = array();
        $info = '';
        if ($root_id) $info .= '@info R' . $root_id . ' ';
        if ($children_id) $info .= ' @info  C' . $children_id . ' ';
        $word = explode(' ', trim($w));
        foreach ($word as $k => $v) {
            $info .= '@title ' . $v . ' ';
        }
        $info = trim($info);
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $key = 'trade_api_sports_sphinx_' . md5($info) . '_' . $limit;
        //$data = $redis->get($key);
        if ($data) return $this->renderText($data);
        $mysphinxDb = new tradeSpinxMysql();
        $sort = 'time desc';
        $res = $mysphinxDb->search(0, $limit, $info, $sort);

        $ids = $res["data"];
        $itemAllTable = TrdItemAllTable::getInstance();
        if (isset($ids) && !empty($ids)) { //按sort去mysql中查找
            $sort_new = 'publish_date desc';
            $items = $itemAllTable->getByIds($ids, $sort_new);
        }
        $go_url = sfConfig::get('app_api');
        if (!empty($items)) {
            foreach ($items as $k => $v) {
                $data[$k]['id'] = $v['id'];
                $data[$k]['title'] = $v['title'];
                $data[$k]['price'] = $v['price'];
                $data[$k]['go_url'] = $go_url['go']['url'] . '?url=' . urlencode($v['url']);
                $data[$k]['detail_url'] = "http://www.shihuo.cn/detail/" . $v['id'] . ".html";
                $data[$k]['intro'] = $v['memo'];
                $data[$k]['width'] = 122;
                $data[$k]['height'] = $v['height'] && $v['width'] ? intval(round(122 * ($v['height']) / ($v['width']))) : 116;
                $data[$k]['img_path'] = 'http://shihuo.hupucdn.com' . $v['img_url'].'-S253.jpg';
                $data[$k]['time'] = $v['publish_date'];
            }
//            if (count($data) == 1) {
//                $data[$k]['id'] = '';
//                $data[$k]['title'] = '';
//                $data[$k]['price'] = '';
//                $data[$k]['go_url'] = '';
//                $data[$k]['detail_url'] = "http://www.shihuo.cn";
//                $data[$k]['intro'] = '';
//                $data[$k]['width'] = 150;
//                $data[$k]['height'] = 116;
//                $data[$k]['img_path'] = 'http://c' . mt_rand(1, 2) . '.hoopchina.com.cn/images/trade/logo-150.jpg';
//                $data[$k]['time'] = '';
//            }
        }
        $data = json_encode(array('status' => 0, 'data' => $data, 'msg' => 'ok'));
        $redis->set($key, serialize($data), 600);
        return $this->renderText($data);
    }

    //爆料表中淘宝和天猫的商品存入商品id(item_id)
    public function executeSaveBaoliaoItemId(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        $id = $request->getParameter('id', '');
        $info = TrdBaoliaoTable::getInstance()->find($id);
        if ($info && ($info->getMart() == '淘宝' || $info->getMart() == '天猫') && !$info->getItemId()) {
            $itemid = TaobaoUtil::parseItemId($info->getUrl());
            if ($itemid) {
                $info->setItemId($itemid);
                $info->save();
            }
        }
        exit;
    }

    //更新运动鞋数据到表中
    public function executeUpdateAttr(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        TrdAttrGroupTable::updateAttrSql();
        exit;
    }

    public function qihuSafeFilter($post)
    {
        $post = trim($post);
        $post = strip_tags($post, ""); //清除HTML等代码
        $post = str_replace("\t", "", $post); //去掉制表符号
        $post = str_replace("\r\n", "", $post); //去掉回车换行符号
        $post = str_replace("\r", "", $post); //去掉回车
        $post = str_replace("\n", "", $post); //去掉换行
        $post = str_replace("'", "", $post); //去掉单引号
        $post = str_replace('/', "", $post); //去掉/
        $post = str_replace('\\', "", $post); //去掉\
        return $post;
    }

    public function executeHaitaoOrders($request)
    {
        sfConfig::set('sf_web_debug', false);
        $orders = Doctrine::getTable('TrdHaitaoOrder');
        $res = $orders->getOrders();
        $info = array();
        if (count($res)) {
            $i = 1;
            foreach ($res as $v) {
                $info[$i++] = array(
                    'id' => $v->getId(),
                    'order_number' => $v->getOrderNumber(),
                    'title' => $v->getTitle(),
                    'attr' => $v->getAttr(),
                    'price' => $v->getPrice(),
                    'count' => $v->getNumber(),
                );
            }
        }
        echo json_encode($info);
        exit;
    }

    public function executeOrder($request)
    {
        sfConfig::set('sf_web_debug', false);
        $ids = trim($request->getParameter('ids', ''), ' ,');
        $orders = Doctrine::getTable('TrdHaitaoOrder');
        $res = $orders->getOrderById($ids);
        $info = array();
        if (count($res)) {
            $i = 1;
            foreach ($res as $v) {
                $info[$i++] = array(
                    'id' => $v->getId(),
                    'order_number' => $v->getOrderNumber(),
                    'title' => $v->getTitle(),
                    'attr' => $v->getAttr(),
                    'price' => $v->getPrice(),
                    'count' => $v->getNumber(),
                );
            }
        }
        echo json_encode($info);
        exit;
    }

    public function executeAddedtocart($request)
    {
        sfConfig::set('sf_web_debug', false);
        $id = $request->getParameter('id', '');
        $orders = Doctrine::getTable('TrdHaitaoOrder');
        $orders->setPluginAdded($id);
        echo 'added';
        exit;
    }

    public function executeGetSneakerItemsByAttr($request)
    {
        sfConfig::set('sf_web_debug', false);
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $key = 'trade_sneakers_index_options';
        $options = unserialize($redis->get($key)); //获取配置信息
        $attr = $request->getParameter('attr', '');
        $items = Doctrine::getTable('TrdItemAll');
        $shoe_key = 'trade_shoe_brand_type';
        if (isset($options['selbrand']) && !empty($options['selbrand'])){
            $brand_key = implode('_',$options['selbrand']);
            $shoe_key .= '_b_'.$brand_key;
        }
        if (isset($options['selfunction']) && !empty($options['selfunction'])){
            $type_key = implode('_',$options['selfunction']);
            $shoe_key .= '_f_'.$type_key;
        }
        $data = $redis->get($shoe_key);
        if ($data) {
            $res = unserialize($data);
        } else {
            $res = $this->getShoeBrandTypeData($options,$items);
            $redis->set($shoe_key, serialize($res), 600);
        }
        $attr_array = explode('-',$attr);
        if (ltrim($attr_array[0],'G') == 1){
            $k_key = 'brand_data';
        } else {
            $k_key = 'type_data';
        }
        $attr_id = ltrim($attr_array[1],'A');
        $items = $res[$k_key][$attr_id];
        echo json_encode($items);
        exit;
    }

    /**
     *
     *  运动鞋首页品牌和分类数据获取存储到缓存
     */
    private function getShoeBrandTypeData($options,$items){
        $attribute = Doctrine::getTable('TrdAttribute');
        $return  = $brand = $type = $itemsbybrand = $itemsbyType = $b_ids= array();
        if (isset($options['selbrand']) && !empty($options['selbrand'])){
            foreach ($options['selbrand'] as $k=>$v){
                if ($k == 1){
                    $itemsbybrand = $brand[$v] = $items->getItemsByField("G1-A".$v);
                } else {
                    $brand[$v] = $items->getItemsByField("G1-A".$v);
                }
                if (isset($brand[$v]) && !empty($brand[$v])){
                    foreach ($brand[$v] as $kk=>$vv){
                        array_push($b_ids,$vv['id']);
                    }
                }
            }
        }
        $return['brand_data'] = $brand;
        $return['itemsbybrand'] = $itemsbybrand;
        $return['brands'] = $attribute->getBrands(array_values($options['selbrand'])); //后台设置的品牌集合

        if (isset($options['selfunction']) && !empty($options['selfunction'])){;
            foreach ($options['selfunction'] as $k=>$v){
                if ($k == 1){
                    $itemsbyType = $type[$v] = $items->getItemsByField("G2-A".$v,$b_ids);
                } else {
                    $type[$v] = $items->getItemsByField("G2-A".$v,$b_ids);
                }
            }
        }
        $return['type_data'] = $type;
        $return['itemsbyType'] = $itemsbyType;
        $return['types'] = $attribute->getBrands(array_values($options['selfunction'])); //后台设置的分类集合
        return $return;
    }

    public function executeGetYesterdayHottestSneakerByField($request)
    {
        sfConfig::set('sf_web_debug', false);
        $field = $request->getParameter('field', '');
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');

        $item = Doctrine::getTable('TrdItemAll');
        $key = 'yesterdayHottestSneaker' . $field;
        $data = $item->getYesterdayHottestByField($field);
        if ($data) {
            $info = explode('-', $data['theField']);
            $count = $data['ct'];
            $id = substr($info[1], 1);
            $name = Doctrine::getTable('TrdAttribute')->find($id)->getName();
            $redis->set($key, serialize(array('id' => $id, 'name' => $name, 'ct' => $count, 'attr' => $data['theField'])));
            echo '成功。';
        } else {
            echo '失败。';
            return;
        }
        exit;
    }

    /**
     * @param $request
     */
    public function executeGetChildrenMenu(sfWebRequest $request) //更具一级导航获的id获取相应的二级导航
    {
        sfConfig::set('sf_web_debug', false);
        $rid = $request->getParameter('rid');
        $menu_children_array = array();
        if ($rid) {
            $menuTable = TrdMenuTable::getInstance();
            $children_menus = $menuTable->getChildrenMenu('', $rid);
            foreach ($children_menus as $children) {
                $menu_children_array[$children->getId()] = $children->getName();
            }
        }
        echo json_encode($menu_children_array);
        exit;
    }


    /**
     *检测线上消息队列数量
     * @param sfWebRequest $request
     * @return type
     */
    public function executeGetRabbmitMqTotal(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://192.168.1.58:15672/api/overview");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_USERPWD, "admin:admin");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $return = curl_exec($ch);
        curl_close($ch);
        if (!$return) {
            header("Status: 404 Not Found");
            return sfView::NONE;
        } else {
            $res = json_decode($return, 1);
            if ($res['queue_totals']['messages'] > 300) {
                header("Status: 500 Internal Server Error");
                return sfView::NONE;
            }
        }
        exit;
    }

    /**
     *
     * 发现好货图片同步到七牛
     */
    public function executeFindPicSyncToQiniu(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        $id = $request->getParameter('id');
        $item = TrdItemAllTable::getInstance()->find($id);
        if ($item && $item->getStatus()==0 && $item->getIsHide() == 0) {
            $newpath = sfConfig::get('app_img_dir_web').str_replace('.jpg', '_300.jpg', $item->getImgUrl());
            if(!file_exists($newpath) ) {
                exit(0);
            }
            //上传到七牛
            $qiniuObj = new tradeQiNiu();
            $qiuniu_name = ltrim($item->getImgUrl(),'/');
            $qiuniu_path = $qiniuObj->uploadFile($qiuniu_name,$newpath);
            if ($item->getPicCollect()){
                $picCollect = json_decode($item->getPicCollect(),true);
                foreach ($picCollect as $k=>$v){
                    $newpath = sfConfig::get('app_img_dir_web').str_replace('.jpg', '_300.jpg', $v);
                    $qiuniu_name = ltrim($v,'/');
                    $qiniuObj->uploadFile($qiuniu_name,$newpath);
                }
            }
            exit;
        }

    }




    //START 1111
    private static $uid = 0;
    private static $userName = null;
    private static $ip = null;
    private static $validate = true;
    private static $redis = null;
    private static $ua = null;
    private static $goodsArray = array('id', 'name', 'pic', 'price', 'link');
    private static $classifiedGoodsArray = array('id', 'classification', 'name', 'pic','ori_price', 'price', 'link');
    private static $endingPiontsMinutes = '00';
    private static $endingPiontsHoursDelay = array(
        '2015-06-17' => array(24),
        '2015-06-18' => array(24),
        '2015-06-19' => array(24),
        '2015-06-20' => array(24),
    );
    private static $endingPionts = array(
        '2015-06-17' => array('00'),
        '2015-06-18' => array('00'),
        '2015-06-19' => array('00'),
        '2015-06-20' => array('00'),
    );

    private static $adminIds = array(
        18908851,
        16178073,
        19116766,
        4040451,
        17337327,
        19449010
    );
    private static $flipRestGoodsQuantity = 6;
    private static $flipClassifiedGoodsQuantity = 20;
    private static $flipGoodsQuantity = 9;
    private static $flipSteps = 3;
    private static $defaultPCChancesTotal = 5;
    private static $defaultSHAREChancesTotal = 5;
    private static $defaultAPPChancesTotal = 8;

    private static $endingPiontsPresents = array(
        1 => array(
            1 => array('normal' => array('红包500元', '红包200元', '红包100元'), 'range' => array('1-20' => '海淘优惠券30元', '21-50' => '淘海优惠券20元' , '51-100' => '海淘优惠券10元'))
        ),
        2 => array(
            1 => array('normal' => array('红包500元', '红包200元', '红包100元'), 'range' => array('1-20' => '海淘优惠券30元', '21-50' => '海淘优惠券20元' , '51-100' => '海淘优惠券10元'))
        ),
        3 => array(
            1 => array('normal' => array('红包500元', '红包200元', '红包100元'), 'range' => array('1-20' => '海淘优惠券30元', '21-50' => '海淘优惠券20元' , '51-100' => '海淘优惠券10元'))
        ),
        4 => array(
            1 => array('normal' => array('红包500元', '红包200元', '红包100元'), 'range' => array('1-20' => '海淘优惠券30元', '21-50' => '海淘优惠券20元' , '51-100' => '海淘优惠券10元'))
        )
    );

    private static $redisKeyPrototype = array(
        'goodsInfo' => 'trade_api_flip_goodsInfo{0}',
        'goods' => 'trade_api_flip_goodsId',
        'classifiedGoodsInfo' => 'trade_api_flip_classified{0}_goodsInfo{1}',
        'classifiedGoods' => 'trade_api_flip_classified{0}_goodsId',
        'classification' => 'trade_api_flip_classification',
        'randClassification' => 'trade_api_flip_randClassification{0}',
        'restRandGoods' => 'trade_api_flip_restRandGoods',
        'userRandGoods' => 'trade_api_flip_user{0}_randGoods',
        'dayRoundUsers' => 'trade_api_flip_d{0}_r{1}',
        'dayRoundUsersInfo' => 'trade_api_flip_day{0}_round{1}_users{2}_info',
        'dayRoundRequestIp' => 'trade_api_flip_day{0}_roundound{1}_requestIp_{2}',
        'dayRoundUserRequestTime' => 'trade_api_flip_day{0}_round{1}_user{2}_requestTime',
        'dayRoundUserUAChancesTotal' => 'trade_api_flip_day{0}_round{1}_user{2}_ua{3}_chancesTotal',
        'dayRoundUserUAChancesUsed' => 'trade_api_flip_day{0}_round{1}_user{2}_ua{3}_chancesUsed',
        'dayRoundUserChoices' => 'trade_api_flip_day{0}_round{1}_user{2}_choices',
        'dayRoundUserScore' => 'trade_api_flip_day{0}_round{1}_user{2}_score',
        'dayRoundUserLowScore' => 'trade_api_flip_day{0}_round{1}_user{2}_lowScore',
        'dayRoundLowScore' => 'trade_api_flip_day{0}_round{1}_lowScore',
        'dayRoundLowScoreRank' => 'trade_api_flip_day{0}_round{1}_lowScoreRank',
        'dayRoundRank' => 'trade_api_flip_day{0}_round{1}_rank',
        'dayRoundUserPlatform' => 'trade_api_flip_day{0}_round{1}_user{2}_platform',
        'dayRoundUserRankDetail' => 'trade_api_flip_day{0}_round{1}_user{2}_rankDetail',
        'banUser' => 'trade_api_flip_banUser',
        'banUserIP' => 'trade_api_flip_banUserIP{0}',
    );
    //1111
    public function executeUserRequestList(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', FALSE);
        header("Content-type: text/html; charset=utf-8");
        $this->_initial();

        if (in_array(self::$uid, self::$adminIds)) {
            $day = $request->getParameter('day', NULL);
            $round = $request->getParameter('round', NULL);

            if ($day && $round)
                $dayRoundInfo = array('day' => $day, 'round' => $round);
            else
                $dayRoundInfo = $this->_getDayAndRoundInfo();
            $dayRoundUsersKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUsers'], array($dayRoundInfo['day'], $dayRoundInfo['round']));

            $dayRoundUsers = self::$redis->LRANGE($dayRoundUsersKey,-30,-1);
            $dayRoundUsers = array_reverse($dayRoundUsers);
            $m = 0;
            foreach(self::$endingPionts as $day => $hours){
                $m++;
                $counts = count($hours);
                for($i=1;$i<=$counts;$i++){
                    echo '<a href="UserRequestList?day=' . $m . '&round=' . $i . '"><input type="button" value="' . "第{$m}天第{$i}轮" . '"></a>';
                }
            }
            echo '<h3>' . "第{$dayRoundInfo['day']}天  第{$dayRoundInfo['round']}轮" . '  参与次数'. self::$redis->LLEN($dayRoundUsersKey).'</h3>
                        <table width="600" border="1"><tr>
                          <th>用户ID</th>
                          <th>用户名</th>
                          <th>IP</th>
                          <th>屏蔽用户</th>
                         </tr>';
            if ($dayRoundUsers) {
                foreach ($dayRoundUsers as $dayRoundUser) {

                    $dayRoundUsersArray = json_decode($dayRoundUser,1);

                    $username = $dayRoundUsersArray['uname'];
                    $ip = $dayRoundUsersArray['ip'];
                    $uid = $dayRoundUsersArray['uid'];
                    $dayRoundRequestIpKey = self::keyFormat(self::$redisKeyPrototype['dayRoundRequestIp'], array($dayRoundInfo['day'], $dayRoundInfo['round'], $ip));
                    $ipCount = self::$redis->get($dayRoundRequestIpKey);

                    echo <<<EOF
                            <tr><td><a href="http://my.hupu.com/{$dayRoundUser}" target="_blank">{$uid}</a></td>
                            <td>{$username}</td>
                            <td>{$ip} count:{$ipCount}</td>
                            <td><a href="banUser?uip={$ip}" target="_blank">屏蔽IP</a></td></tr>
EOF;
                }
            }
            echo '</table>';
        }
        exit();
    }

    public function executeBanUser(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', FALSE);
        $this->_initial();
        $uid = $request->getParameter('uid', NULL);
        $uip = $request->getParameter('uip', NULL);
        $time = $request->getParameter('time', 300);
        if (in_array(self::$uid, self::$adminIds)) {
//            if ($uid) {
//                $banUserKey = self::keyFormat(self::$redisKeyPrototype['banUser']);
//                self::$redis->sAdd($banUserKey, $uid);
//                echo '屏蔽成功';
//            }
            if ($uip) {
                $banUserIPKey = self::keyFormat(self::$redisKeyPrototype['banUserIP'],$uip);
                self::$redis->setex($banUserIPKey, $time,60);
                echo '屏蔽成功';
            }
        } else
            echo '没有权限';
        return sfView::NONE;
    }

    public function executeGetSearchedGoodsInfo(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', FALSE);
        $this->_initial();
        $keyword = $request->getParameter('keyword', NULL);

        if (!empty($keyword)) {
            $goodsInfo = file_get_contents("http://www.shihuo.cn/shihuo/checkcvs/?keyword=%22$keyword%22&page=0");
            if ($goodsInfo) {
                self::jsonOutput($goodsInfo);
            }
        }
        self::jsonOutput(array());
    }

    public function executeGetRoundLeftTime(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', FALSE);
        $this->_initial(TRUE, FALSE);

        $timeLeft = NULL;
        $gameDay = date('Y-m-d');
        if (in_array($gameDay, array_keys(self::$endingPionts))) {
            $i = 0;
            foreach(self::$endingPionts[$gameDay] as $hour){
                $gameHour = date('H:i');
                if($gameHour >= $hour.':'.self::$endingPiontsMinutes && $gameHour <= $hour+self::$endingPiontsHoursDelay[$gameDay][$i].':'.self::$endingPiontsMinutes){
                    $timeLeft = strtotime(date('Y-m-d '.($hour+self::$endingPiontsHoursDelay[$gameDay][$i]).':'.self::$endingPiontsMinutes.':00')) - time();
                    break;
                }
                $i++;
            }
        }
        $this->timeLeft = $timeLeft;
        $dayRoundInfo = $this->_getDayAndRoundInfo();
        $result = array('day' => $dayRoundInfo['day'], 'round' => $dayRoundInfo['round'], 'timeLeft' => $timeLeft);
        echo json_encode($result);
        return sfView::NONE;
    }

    public function executeFlushAllGoods(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', FALSE);
        die();
        $this->_initial();
        if (in_array(self::$uid, self::$adminIds)) {
            $goodsKey = self::keyFormat(self::$redisKeyPrototype['goods']);
            $goods = self::$redis->sMembers($goodsKey);
            foreach ($goods as $goods_v) {
                $goodsInfoKey = self::keyFormat(self::$redisKeyPrototype['goodsInfo'], $goods_v);
                self::$redis->del($goodsInfoKey);
            }
            self::$redis->del($goodsKey);
            echo '清除成功！';
        } else
            echo '没有权限！';
        return sfView::NONE;
    }

    public function executeFlushClassfiedGoods(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', FALSE);
        die();
        $this->_initial();
        if (in_array(self::$uid, self::$adminIds)) {
            $classificationKey = self::keyFormat(self::$redisKeyPrototype['classification']);
            $classification = self::$redis->hLen($classificationKey);
            for ($i = 1; $i <= $classification; $i++) {
                $goodsKey = self::keyFormat(self::$redisKeyPrototype['classifiedGoods'], $i);
                $goods = self::$redis->sMembers($goodsKey);
                foreach ($goods as $goods_v) {
                    $goodsInfoKey = self::keyFormat(self::$redisKeyPrototype['classifiedGoodsInfo'], array($i, $goods_v));
                    self::$redis->del($goodsInfoKey);
                }
                self::$redis->del($goodsKey);
            }
            self::$redis->del($classificationKey);
            echo '清除成功！';
        } else
            echo '没有权限！';
        return sfView::NONE;
    }

    public function executePostMyChoice(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', FALSE);
        $this->_initial();
        $goodsInfo = array();
        $choiceId = $request->getParameter('choiceId', NULL);
        $choiceStep = $request->getParameter('choiceStep', NULL);
        $dayRoundInfo = $this->_getDayAndRoundInfo();

        if ($dayRoundInfo['day'] && $dayRoundInfo['round']) {
//            $dayRoundUsersInfoKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUsersInfo'], array($dayRoundInfo['day'], $dayRoundInfo['round'], self::$uid));
//            self::$redis->hSet($dayRoundUsersInfoKey, 'id', self::$uid);
//            self::$redis->hSet($dayRoundUsersInfoKey, 'uname', self::$userName);
//			self::$redis->setex($dayRoundUsersInfoKey,86400);

            $dayRoundRequestIpKey = self::keyFormat(self::$redisKeyPrototype['dayRoundRequestIp'], array($dayRoundInfo['day'], $dayRoundInfo['round'],self::$ip));
            $nowIpCount = self::$redis->INCR($dayRoundRequestIpKey);

            if($nowIpCount > 90)
            {
                $timeout = self::$redis->ttl($dayRoundRequestIpKey);
                if($timeout == -1)
                {
                    self::$redis->EXPIRE($dayRoundRequestIpKey,120);
                }
                self::jsonOutput('操作太快了，请休息一会儿', TRUE); exit();
            }
            //添加用户访问记录
            $dayRoundUsersKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUsers'], array($dayRoundInfo['day'], $dayRoundInfo['round']));
            self::$redis->rPush($dayRoundUsersKey, json_encode(array('uid'=>self::$uid,'uname'=>self::$userName,'ip'=>self::$ip)));


//           $dayRoundUserRequestTimeKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserRequestTime'], array($dayRoundInfo['day'], $dayRoundInfo['round'], self::$uid));
//trade_api_flip_goodsInfo3891
//           self::$redis->rPush($dayRoundUserRequestTimeKey, time());


            if (in_array($choiceId, range(0, self::$flipGoodsQuantity - 1)) && in_array($choiceStep, range(1, self::$flipSteps))) {
                //
                $chanceInfo = $this->_getOrSaveChanceInfo();

                if ($chanceInfo['dayRoundUserUAChancesUsed'] < $chanceInfo['dayRoundUserUAChancesTotal']) {
                    //uid对应的某一天的某一轮的某一次游戏的选择id
                    $dayRoundUserChoicesKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserChoices'], array($dayRoundInfo['day'], $dayRoundInfo['round'], self::$uid));

                    $choicesSize = self::$redis->lSize($dayRoundUserChoicesKey);

                    if ($choiceStep != $choicesSize + 1)
                        self::jsonOutput('非法请求', TRUE);

                    if ($choicesSize < self::$flipSteps) {
                        //用户九个随机商品的id
                        $userRandGoodsKey = self::keyFormat(self::$redisKeyPrototype['userRandGoods'], self::$uid);
                        $userRandGoodsKeySize = self::$redis->lLen($userRandGoodsKey);
                        if (intval($userRandGoodsKeySize) <= 0)
                            self::jsonOutput('非法请求，用户洗牌未完成', TRUE);

                        //拿到用户选择的商品
                        $goodsInfoId = self::$redis->lGet($userRandGoodsKey, $choiceId);

                        for ($i = 0; $i < $userRandGoodsKeySize; $i++) {
                            $keyTmp = self::$redis->lRange($dayRoundUserChoicesKey, $i, $i);
                            if (isset($keyTmp[0]) && $keyTmp[0] == $goodsInfoId)
                                self::jsonOutput('商品重复', TRUE);
                        }

                        $goodsInfoKey = self::keyFormat(self::$redisKeyPrototype['goodsInfo'], $goodsInfoId);
                        $goodsInfo = self::$redis->hMGet($goodsInfoKey, self::$goodsArray);

                        self::$redis->rPush($dayRoundUserChoicesKey, $goodsInfoId);

                        // 验证是否是第三次翻牌
                        $choicesSize = self::$redis->lSize($dayRoundUserChoicesKey);

                        if ($choicesSize == 3) {
                            $goodsTotalValue = 0;
                            $goodsInfoIds = self::$redis->lRange($dayRoundUserChoicesKey, 0, -1);

                            //计算总价
                            foreach ($goodsInfoIds as $v) {
                                $goodsInfoKey = self::keyFormat(self::$redisKeyPrototype['goodsInfo'], array($v));
                                $goodsTotalValue += self::$redis->hGet($goodsInfoKey, 'price');
                            }

                            //当天单轮当前用户当次玩的最低价总和
                            $dayRoundUserScoreKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserScore'], array($dayRoundInfo['day'], $dayRoundInfo['round'], self::$uid, $chanceInfo['dayRoundUserUAChancesUsed'] + 1));
                            $this->_getOrSaveKV($dayRoundUserScoreKey, NULL, $goodsTotalValue, TRUE);

                            //当天单轮当前用户目前玩的最低价总和
                            $dayRoundUserLowScoreKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserLowScore'], array($dayRoundInfo['day'], $dayRoundInfo['round'], self::$uid));
                            $dayRoundUserLowScore = $this->_getOrSaveKV($dayRoundUserLowScoreKey);
                            if (is_null($dayRoundUserLowScore) || $dayRoundUserLowScore > $goodsTotalValue)
                                $dayRoundUserLowScore = $this->_getOrSaveKV($dayRoundUserLowScoreKey, NULL, $goodsTotalValue, TRUE);

                            $dayRoundLowScoreKey = self::keyFormat(self::$redisKeyPrototype['dayRoundLowScore'], array($dayRoundInfo['day'], $dayRoundInfo['round']));
                            $dayRoundLowScore = $this->_getOrSaveKV($dayRoundLowScoreKey);
                            if (!$dayRoundLowScore || $dayRoundLowScore > $goodsTotalValue)
                                $this->_getOrSaveKV($dayRoundLowScoreKey, NULL, $goodsTotalValue, TRUE);

                            //排行榜
                            $dayRoundRankKey = self::keyFormat(self::$redisKeyPrototype['dayRoundRank'], array($dayRoundInfo['day'], $dayRoundInfo['round']));
                            self::$redis->zAdd($dayRoundRankKey, $dayRoundUserLowScore, self::$uid);
                            $myRankTmp = self::$redis->zRank($dayRoundRankKey, self::$uid);
                            if(is_numeric($myRankTmp))
                                $myRank = $myRankTmp + 1;
                            $rank = self::$redis->zRange($dayRoundRankKey, 0, 14);

                            $dayRoundUserRankDetailKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserRankDetail'], array($dayRoundInfo['day'], $dayRoundInfo['round'], self::$uid));
                            self::$redis->hSet($dayRoundUserRankDetailKey, 'uname', self::$userName);
                            self::$redis->hSet($dayRoundUserRankDetailKey, 'score', $dayRoundUserLowScore);
                            self::$redis->hSet($dayRoundUserRankDetailKey, 'present', self::getPresent($dayRoundInfo['day'], $dayRoundInfo['round'], $myRank));

                            //已用次数加1
                            $this->_getOrSaveChanceInfo(NULL, '+1');

                            $randGoodsKey = self::keyFormat(self::$redisKeyPrototype['userRandGoods'], self::$uid);
                            self::$redis->del($randGoodsKey);
                            self::$redis->del($dayRoundUserChoicesKey);

                            $goodsInfoTmp = array();
                            $goodsInfoTmp['goodsInfo'] = $goodsInfo;
                            $goodsInfoTmp['myRank'] = $myRank;
                            $goodsInfoTmp['myGameScore'] = $goodsTotalValue;
                            $goodsInfoTmp['myLowScore'] = $dayRoundUserLowScore;
                            $chanceInfo = $this->_getOrSaveChanceInfo();
                            $goodsInfoTmp['myChance'] = $chanceInfo['dayRoundUserUAChancesTotal'] -  $chanceInfo['dayRoundUserUAChancesUsed'];

                            if ($rank)
                                foreach ($rank as $k => $v) {
                                    $rankDayRoundUserRankDetailKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserRankDetail'], array($dayRoundInfo['day'], $dayRoundInfo['round'], $v));
                                    $goodsInfoTmp['totalRank'][$k + 1] = array('uname' => self::$redis->hGet($rankDayRoundUserRankDetailKey, 'uname'), 'score' => self::$redis->hGet($rankDayRoundUserRankDetailKey, 'score'), 'present' => self::$redis->hGet($rankDayRoundUserRankDetailKey, 'present'));
                                }
                            $goodsInfo = $goodsInfoTmp;
                        }else{

                        }
                    }
                }else
                    self::jsonOutput('游戏次数为0', TRUE);
            } else
                self::jsonOutput('参数错误', TRUE);
        } else
            self::jsonOutput('游戏未开放', TRUE);
        self::jsonOutput($goodsInfo);
    }

    public function executeUserRankInfo(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', FALSE);
        $this->_initial();

        $dayRoundInfo = $this->_getDayAndRoundInfo();
        $dayRoundRankKey = self::keyFormat(self::$redisKeyPrototype['dayRoundRank'], array($dayRoundInfo['day'], $dayRoundInfo['round']));
        $rank = self::$redis->zRange($dayRoundRankKey, 0, 14);

        $info = array();
        if ($rank){
            $i = 0;
            foreach ($rank as $v) {
                $i++;
                $rankDayRoundUserRankDetailKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserRankDetail'], array($dayRoundInfo['day'], $dayRoundInfo['round'], $v));
                $info['totalRank'][] = array('uname' => self::$redis->hGet($rankDayRoundUserRankDetailKey, 'uname'), 'score' => self::$redis->hGet($rankDayRoundUserRankDetailKey, 'score'), 'present' => self::getPresent($dayRoundInfo['day'], $dayRoundInfo['round'],$i));
            }
        }

        $dayRoundRankKey = self::keyFormat(self::$redisKeyPrototype['dayRoundRank'], array($dayRoundInfo['day'], $dayRoundInfo['round']));
        $myRank = self::$redis->zRank($dayRoundRankKey, self::$uid) + 1;

        $dayRoundUserLowScoreKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserLowScore'], array($dayRoundInfo['day'], $dayRoundInfo['round'], self::$uid));
        $dayRoundUserLowScore = $this->_getOrSaveKV($dayRoundUserLowScoreKey);

        $info['myId'] = self::$userName;
        $info['myRank'] = $dayRoundUserLowScore ? $myRank : null;
        $info['myLowScore'] = $dayRoundUserLowScore;
        $info['myPresent'] = $dayRoundUserLowScore ? self::getPresent($dayRoundInfo['day'], $dayRoundInfo['round'], $myRank) : '';

        self::jsonOutput($info);
    }

    public function executeUserRankList(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', FALSE);
        header("Content-type: text/html; charset=utf-8");
        $this->_initial();

        $result = array();
        if (in_array(self::$uid, self::$adminIds)) {
            $i = 0;
            foreach (self::$endingPionts as $day => $hours) {
                $i++;
                $j = 0;
                foreach ($hours as $hour){
                    $j++;
                    $dayRoundInfo = array('day' => $i, 'round' => $j);
                    $dayRoundRankKey = self::keyFormat(self::$redisKeyPrototype['dayRoundRank'], array($dayRoundInfo['day'], $dayRoundInfo['round']));
                    $rank = self::$redis->zRange($dayRoundRankKey, 0, 100);

                    $info = array();
                    if ($rank)
                        foreach ($rank as $k => $v) {
                            $rankDayRoundUserRankDetailKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserRankDetail'], array($dayRoundInfo['day'], $dayRoundInfo['round'], $v));
                            $info[] = array('uname' => self::$redis->hGet($rankDayRoundUserRankDetailKey, 'uname'), 'score' => self::$redis->hGet($rankDayRoundUserRankDetailKey, 'score'), 'present' => self::getPresent($dayRoundInfo['day'], $dayRoundInfo['round'], $k + 1));
                        }
                    $result[$day.' '.$hour.':'.self::$endingPiontsMinutes] = $info;
                }
            }
        }
        foreach($result as $k => $v){
            echo "<div style=\"width:200px;height:25px;font-weight: bold;color: red;\">{$k}</div>
            <table>
                <tr>
                    <th style=\"border:1px solid black;width:200px;\">用户名</th>
                    <th style=\"border:1px solid black;width:100px;\">最低价格</th>
                    <th style=\"border:1px solid black;width:100px;\">奖品</th>
                </tr>";

            foreach ($v as $v_key => $v_value) {
                echo "
                    <tr>
                        <td style=\"border:1px solid black;\">{$v_value['uname']}</td>
                        <td style=\"border:1px solid black;\">{$v_value['score']}</td>
                        <td style=\"border:1px solid black;\">{$v_value['present']}</td>
                    </tr>";
            }

            echo "</table><hr/>";

        }
        die();
        self::jsonOutput($result);
    }

    public function executePostShareingStatus(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', FALSE);
        $this->_initial();

        $platformStatus = array();
        $dayRoundInfo = $this->_getDayAndRoundInfo();
        if ($dayRoundInfo['day'] && $dayRoundInfo['round']) {
            $dayRoundUserPlatformKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserPlatform'], array($dayRoundInfo['day'], $dayRoundInfo['round'], self::$uid));
            $onPC = self::$redis->sIsMember($dayRoundUserPlatformKey, 'PC');
            $onAPP = self::$redis->sIsMember($dayRoundUserPlatformKey, 'APP');
            $onSHARE = self::$redis->sIsMember($dayRoundUserPlatformKey, 'SHARE');

            $isDoubleShare = $onSHARE;

            if (!$onPC) {
                $this->_getOrSaveChanceInfo(self::$defaultPCChancesTotal);
                self::$redis->sAdd($dayRoundUserPlatformKey, 'PC');
            }
            if (!$onSHARE) {
                $this->_getOrSaveChanceInfo("+" . self::$defaultSHAREChancesTotal);
                self::$redis->sAdd($dayRoundUserPlatformKey, 'SHARE');
            }

            $onPC = self::$redis->sIsMember($dayRoundUserPlatformKey, 'PC');
            $onAPP = self::$redis->sIsMember($dayRoundUserPlatformKey, 'APP');
            $onSHARE = self::$redis->sIsMember($dayRoundUserPlatformKey, 'SHARE');
            $platformStatus = array('onPC' => $onPC,
                'onAPP' => $onAPP,
                'onSHARE' => $onSHARE,
            );
            $chanceInfo = $this->_getOrSaveChanceInfo();
            $platformStatus['isDoubleShare'] = $isDoubleShare;
            $platformStatus['chanceLeft'] = self::$uid?$chanceInfo['dayRoundUserUAChancesTotal'] -  $chanceInfo['dayRoundUserUAChancesUsed']:0;
        }

        self::jsonOutput($platformStatus);
    }

    public function executeMyPlatformStatus(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', FALSE);
        $this->_initial();

        $platformStatus = array();

        $dayRoundInfo = $this->_getDayAndRoundInfo();
        if ($dayRoundInfo['day'] && $dayRoundInfo['round']) {
            $dayRoundUserPlatformKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserPlatform'], array($dayRoundInfo['day'], $dayRoundInfo['round'], self::$uid));
            $this->_getUserAgent();
            if (!self::$redis->sIsMember($dayRoundUserPlatformKey, self::$ua)) {
                self::$redis->sAdd($dayRoundUserPlatformKey, self::$ua);
                $this->_getOrSaveChanceInfo();
            }
            $platformStatus = array('onPC' => self::$redis->sIsMember($dayRoundUserPlatformKey, 'PC'),
                'onAPP' => self::$redis->sIsMember($dayRoundUserPlatformKey, 'APP'),
                'onSHARE' => self::$redis->sIsMember($dayRoundUserPlatformKey, 'SHARE'),
            );
        }

        if (empty($platformStatus))
            self::jsonOutput("游戏未开放", TRUE);
        self::jsonOutput($platformStatus);
    }

    public function executeGamePage(sfWebRequest $request){
        sfConfig::set('sf_web_debug', FALSE);

        self::$validate = false;
        $this->_getCommonPageInfo();
        if($request->getParameter('temp') == 'bbs')
        {
            $this->temp = 'bbs';
            $this->setTemplate('gameBBsPage');
        }else{
            $this->temp = 'shihuo';
        }
    }

    public function executeGamePageMobile(sfWebRequest $request){
        sfConfig::set('sf_web_debug', FALSE);
        self::$validate = false;
        $this->_getCommonPageInfo(true);

        $this->jumpurl = 'http://www.shihuo.cn/618/mobile';
    }

    public function executeFlushForTesting(sfWebRequest $request){
        sfConfig::set('sf_web_debug', FALSE);
        die();
        if (in_array(self::$uid, self::$adminIds))
            self::$redis->flushAll();
        exit('Done');
    }

    private function _getCommonPageInfo($app = false){
        error_reporting(0);
        ini_set('dispaly_errors', 0);
        $this->_initial();
        $dayRoundInfo = $this->_getDayAndRoundInfo();

        $platformStatus = array();
        $dayRoundUserPlatformKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserPlatform'], array($dayRoundInfo['day'], $dayRoundInfo['round'], self::$uid));
        $this->_getUserAgent($app);
        if (!self::$redis->sIsMember($dayRoundUserPlatformKey, self::$ua)) {
            self::$redis->sAdd($dayRoundUserPlatformKey, self::$ua);
            $this->_getOrSaveChanceInfo();
        }
        $platformStatus = array('onPC' => self::$redis->sIsMember($dayRoundUserPlatformKey, 'PC'),
            'onAPP' => self::$redis->sIsMember($dayRoundUserPlatformKey, 'APP'),
            'onSHARE' => self::$redis->sIsMember($dayRoundUserPlatformKey, 'SHARE'),
        );

        $this->platformStatus = $platformStatus;
        $this->isLogin = self::$uid>0;
        $this->uid = self::$uid;
        $this->uname = self::$userName;

        $dayRoundLowScoreKey = self::keyFormat(self::$redisKeyPrototype['dayRoundLowScore'], array($dayRoundInfo['day'], $dayRoundInfo['round']));
        $this->dayRoundLowScore = $this->_getOrSaveKV($dayRoundLowScoreKey);

        $date = date("Y-m-d");
        $time = date("H:i");
        $result = array();
        $i = $gameDayMobile = 0;
        $gameDay = null;

        foreach (self::$endingPionts as $day => $hours) {
            if($day == date('Y-m-d'))
                $gameDay = $i+1;
            if ($date > $day) {
                $gameDayMobile += count($hours);
                $result['days'][$i] = range(1, count($hours));
            } elseif ($date == $day) {
                $result['days'][$i] = array();
                $j = 0;
                foreach ($hours as $hour){
                    if ($time >= $hour . ":" . self::$endingPiontsMinutes && $time <= $hour+self::$endingPiontsHoursDelay[$day][$j] . ":" . self::$endingPiontsMinutes){
                        $result['days'][$i][] = ++$j;
                        $gameDayMobile ++;
                    }
                }
            }
            $i++;
        }
        $this->dayRound = $result['days'];
        $this->gameDay = $gameDay;
        $this->gameDayMobile = $gameDayMobile;

        $timeLeft = NULL;
        $gameDay = date('Y-m-d');
        $this->nextTime = array();
        if (in_array($gameDay, array_keys(self::$endingPionts))) {
            $i = 0;

            foreach(self::$endingPionts[$gameDay] as $hour){

                $gameHour = date('H:i');
                if($gameHour >= $hour.':'.self::$endingPiontsMinutes && $gameHour <= $hour+self::$endingPiontsHoursDelay[$gameDay][$i].':'.self::$endingPiontsMinutes){
                    $timeLeft = strtotime(date('Y-m-d '.($hour+self::$endingPiontsHoursDelay[$gameDay][$i]).':'.self::$endingPiontsMinutes.':00')) - time();

                    break;
                }
                $i++;
            }


            foreach(self::$endingPionts as $all_day=>$all_hours)
            {
                foreach($all_hours as $this_hours)
                {
                    if(time() < strtotime($all_day.' '.$this_hours.':00:00'))
                    {
                        $this->nextTime = strtotime($all_day.' '.$this_hours.':00:00');
                        break 2;
                    }
                }
            }
        }

        $this->timeLeft = $timeLeft;
        $this->isOver = $timeLeft ? 0 : 1;
        if ($this->isOver){//如果结束 调取上一次的排行榜信息
            $dayRoundInfo = $this->getPreRoundInfo();
        }

        $dayRoundRankKey = self::keyFormat(self::$redisKeyPrototype['dayRoundRank'], array($dayRoundInfo['day'], $dayRoundInfo['round']));

        $rank = self::$redis->zRange($dayRoundRankKey, 0, 14);
        $rankInfo = array();

        if ($rank){
            $i = 0;
            foreach ($rank as $v) {
                $rankDayRoundUserRankDetailKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserRankDetail'], array($dayRoundInfo['day'], $dayRoundInfo['round'], $v));
                $rankInfo['totalRank'][$i] = array('uname' => self::$redis->hGet($rankDayRoundUserRankDetailKey, 'uname'), 'score' => self::$redis->hGet($rankDayRoundUserRankDetailKey, 'score'), 'present' => self::getPresent($dayRoundInfo['day'], $dayRoundInfo['round'],++$i));
            }
        }
        $this->rankInfo = $rankInfo;

        $dayRoundUserRankDetailKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserRankDetail'], array($dayRoundInfo['day'], $dayRoundInfo['round'], self::$uid));
        $this->dayRoundUserLowScore = self::$redis->hGet($dayRoundUserRankDetailKey, 'score');

        $this->myRank = null;
        $myRankTmp = self::$redis->zRank($dayRoundRankKey, self::$uid);
        if(is_numeric($myRankTmp))
            $this->myRank = $myRankTmp + 1;
        if($this->myRank && $this->dayRoundUserLowScore)
            $this->myPresent = self::getPresent($dayRoundInfo['day'], $dayRoundInfo['round'], $this->myRank);
        else
            $this->myPresent = null;


        $cid = 1;
        //类别必须从1开始
        $myGoods = $myGoodsId = array();
        $randClassificationKey = self::keyFormat(self::$redisKeyPrototype['classifiedGoods'], $cid);

        do {
            $goodsId = self::$redis->sRandMember($randClassificationKey);
            if (!$goodsId)
                break;
            if (!in_array($goodsId, $myGoodsId)) {
                $myGoodsId[] = $goodsId;
                $goodsInfoKey = self::keyFormat(self::$redisKeyPrototype['classifiedGoodsInfo'], array($cid, $goodsId));
                $goodsInfoResult = self::$redis->hMget($goodsInfoKey, self::$classifiedGoodsArray);
                $myGoods[] = $goodsInfoResult;
            }

        } while (count($myGoodsId) < self::$flipClassifiedGoodsQuantity);
        $this->classifiedGoodsInfo = $myGoods;

        $classification = array();
        $classificationKey = self::keyFormat(self::$redisKeyPrototype['classification']);
        $classificationCount = self::$redis->hLen($classificationKey);
        for($i=1;$i<=$classificationCount;$i++){
            $classification[] = self::$redis->hGet($classificationKey, $i);
        }
        $this->classification = $classification;

        $chanceInfo = $this->_getOrSaveChanceInfo();
        $this->chanceInfo = self::$uid?$chanceInfo['dayRoundUserUAChancesTotal'] -  $chanceInfo['dayRoundUserUAChancesUsed']:0;
    }

    //获取上一轮的信息
    private function getPreRoundInfo(){
        $time = time();
        if ($time > strtotime('2015-06-17 00:00:00') && $time < strtotime('2015-06-18 00:00:00')){
            return array('day'=>1,'round'=>1);
        } else if ($time > strtotime('2015-06-18 00:00:00') && $time < strtotime('2015-06-19 00:00:00')){
            return array('day'=>2,'round'=>1);
        } else if ($time > strtotime('2015-06-19 00:00:00') && $time < strtotime('2015-06-20 00:00:00')){
            return array('day'=>3,'round'=>1);
        } else if ($time > strtotime('2015-06-20 00:00:00')){
            return array('day'=>4,'round'=>1);
        }
    }

    public function executeDayRound() {
        $this->_initial();


        $dayRoundInfo = $this->_getDayAndRoundInfo();
        var_dump($dayRoundInfo);
        die();
    }

    private function _getOrSaveChanceInfo($total = NULL, $used = NULL) {
        $dayRoundInfo = $this->_getDayAndRoundInfo();
        $dayRoundUserUAChancesTotal = $dayRoundUserUAChancesUsed = NULL;
        self::$ua || $this->_getUserAgent();
        if ($dayRoundInfo['day'] && $dayRoundInfo['round']) {
            //获取用户游戏机会总数，第一次访问时有相应计算
            $dayRoundUserUAChancesTotalKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserUAChancesTotal'], array($dayRoundInfo['day'], $dayRoundInfo['round'], self::$uid, self::$ua));
            $dayRoundUserUAChancesTotal = $this->_getOrSaveKV($dayRoundUserUAChancesTotalKey);
            if (!$dayRoundUserUAChancesTotal) {
                //拿到ua，增加相应游戏次数
                $defaultChancesTotalBase = 'default' . self::$ua . 'ChancesTotal';
                $dayRoundUserUAChancesTotal = self::$$defaultChancesTotalBase;
                $this->_getOrSaveKV($dayRoundUserUAChancesTotalKey, NULL, intval($dayRoundUserUAChancesTotal), TRUE);

                //记录玩过的相应的ua平台
                $dayRoundUserPlatformKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserPlatform'], array($dayRoundInfo['day'], $dayRoundInfo['round'], self::$uid));
                self::$redis->sAdd($dayRoundUserPlatformKey, self::$ua);
            }
            if (!is_null($total)) {
                if (false !== strpos($total, '+') || false !== strpos($total, '-')) {
                    $dayRoundUserUAChancesTotal += $total;
                    $this->_getOrSaveKV($dayRoundUserUAChancesTotalKey, NULL, intval($dayRoundUserUAChancesTotal), TRUE);
                } else
                    $this->_getOrSaveKV($dayRoundUserUAChancesTotalKey, NULL, intval($total), TRUE);
            }

            $dayRoundUserUAChancesUsedKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserUAChancesUsed'], array($dayRoundInfo['day'], $dayRoundInfo['round'], self::$uid, self::$ua));
            $dayRoundUserUAChancesUsed = $this->_getOrSaveKV($dayRoundUserUAChancesUsedKey);
            if (!$dayRoundUserUAChancesUsed) {
                $dayRoundUserUAChancesUsed = 0;
                $this->_getOrSaveKV($dayRoundUserUAChancesUsedKey, NULL, intval($dayRoundUserUAChancesUsed), TRUE);
            }
            if (!is_null($used)) {
                if (false !== strpos($used, '+') || false !== strpos($used, '-')) {
                    $dayRoundUserUAChancesUsed += $used;
                    $this->_getOrSaveKV($dayRoundUserUAChancesUsedKey, NULL, intval($dayRoundUserUAChancesUsed), TRUE);
                } else
                    $this->_getOrSaveKV($dayRoundUserUAChancesUsedKey, NULL, intval($used), TRUE);
            }
        }

        return array('dayRoundUserUAChancesTotal' => $dayRoundUserUAChancesTotal, 'dayRoundUserUAChancesUsed' => $dayRoundUserUAChancesUsed);
    }

    private function _getOrSaveKV($key, $func = NULL, $paramsOrValue = NULL, $isSave = FALSE, $expires = 12960000 /* 5*30*24*3600 */) {
        $result = NULL;
        $result = self::$redis->get($key);
        if (!$result || $isSave) {
            if ($func)
                $result = call_user_func($func,$paramsOrValue);
            else
                $result = $paramsOrValue;
            self::$redis->set($key, $result, $expires);
        }

        return $result;
    }

    private function _getUserInfo() {
        $platform = 'shihuo.cn';
        $passportClient = new PassportClient($platform);
        if ($passportClient->iflogin()) {
            $userInfo = $passportClient->userinfo();
            self::$uid = $userInfo['uid'];
            self::$userName = $userInfo['username'];
            self::$ip = FunBase::get_client_ip();
        }
    }


    public function executeUserInfo() {
        $platform = 'hupu.com';
        $platform = 'shihuo.cn';
        $passportClient = new PassportClient($platform);
        if ($passportClient->iflogin()) {
            $userInfo = $passportClient->userinfo();
            self::$uid = $userInfo['uid'];
            self::$userName = $userInfo['username'];
            self::$ip = FunBase::get_client_ip();
        }
        var_dump($_SERVER);
        die();
    }
    private function _getUserAgent($app = false) {
        $ua = isset($_REQUEST['APPSource']) || $app ? 'APP' : 'PC';
        self::$ua = $ua;
    }

    private function _initial($withRedis = true, $withUserInfo = true, $withUA = true) {
        date_default_timezone_set("Asia/Shanghai");

        if ($withRedis)
            self::$redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        if ($withUserInfo) {
            $this->_getUserInfo();
            if ((is_null(self::$uid) || is_null(self::$userName)) && self::$validate) {
                echo json_encode(array('status' => 'error', 'message' => '用户未登陆'));
                exit();
            }
            if ($withRedis) {
//                $banUserKey = self::keyFormat(self::$redisKeyPrototype['banUser']);
////                if (self::$redis->sIsMember($banUserKey, self::$uid)) {
////                    echo json_encode(array('status' => 'error', 'message' => '操作太快了。休息下吧'));
////                    exit();
////                }

                $banUserIPKey = self::keyFormat(self::$redisKeyPrototype['banUserIP'],self::$ip);
                if (self::$redis->EXISTS($banUserIPKey)) {
                    self::$redis->EXPIRE($banUserIPKey,60*60);
                    echo json_encode(array('status' => 'error', 'message' => '操作太快了。休息下吧。稍后再玩'));
                    exit();
                }
            }
        }
        if ($withUA)
            $this->_getUserAgent();
    }

    public function executeGenerateRandomGoodsInfo(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', FALSE);
        $this->_initial();

        ////刷新用户翻牌物品
        $goodsKey = self::keyFormat(self::$redisKeyPrototype['goods']);
        $randGoodsKey = self::keyFormat(self::$redisKeyPrototype['userRandGoods'], self::$uid);
        $myGoods = $myGoodsId = array();
        self::$redis->del($randGoodsKey);

        $dayRoundInfo = $this->_getDayAndRoundInfo();

        //刷新用户翻牌状态
        $dayRoundUserChoicesKey = self::keyFormat(self::$redisKeyPrototype['dayRoundUserChoices'], array($dayRoundInfo['day'], $dayRoundInfo['round'], self::$uid));
        self::$redis->del($dayRoundUserChoicesKey);
        do {
            $goodsId = self::$redis->sRandMember($goodsKey);
            if (!$goodsId)
                break;
            if (!in_array($goodsId, $myGoodsId)) {
                self::$redis->rPush($randGoodsKey, $goodsId);
                $myGoodsId[] = $goodsId;
                $goodsInfoKey = self::keyFormat(self::$redisKeyPrototype['goodsInfo'], $goodsId);
                $goodsInfoResult = self::$redis->hMget($goodsInfoKey, self::$goodsArray);
                $myGoods[] = $goodsInfoResult;
            }
        } while (count($myGoodsId) < self::$flipGoodsQuantity);

        self::jsonOutput('Done');
    }

    public function executeRestRandomGoodsInfo(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', FALSE);
        $this->_initial();

        $goodsKey = self::keyFormat(self::$redisKeyPrototype['goods']);
        $randGoodsKey = self::keyFormat(self::$redisKeyPrototype['restRandGoods'], self::$uid);
        $myGoods = $myGoodsId = array();
        self::$redis->del($randGoodsKey);
        do {
            $goodsId = self::$redis->sRandMember($goodsKey);
            if (!$goodsId)
                break;
            if (!in_array($goodsId, $myGoodsId)) {
                self::$redis->rPush($randGoodsKey, $goodsId);
                $myGoodsId[] = $goodsId;
                $goodsInfoKey = self::keyFormat(self::$redisKeyPrototype['goodsInfo'], $goodsId);
                $goodsInfoResult = self::$redis->hMget($goodsInfoKey, self::$goodsArray);
                $myGoods[] = $goodsInfoResult;
            }
        } while (count($myGoodsId) < self::$flipRestGoodsQuantity);

        self::jsonOutput($myGoods, empty($myGoods));
    }

    public function executeClassifiedRandomGoodsInfo(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', FALSE);
        self::$validate = false;
        $this->_initial();

        $cid = $request->getParameter('cid', NULL);
        //类别必须从1开始
        $myGoods = $myGoodsId = array();
        $randClassificationKey = self::keyFormat(self::$redisKeyPrototype['classifiedGoods'], $cid);
        do {
            $goodsId = self::$redis->sRandMember($randClassificationKey);
            if (!$goodsId)
                break;
            if (!in_array($goodsId, $myGoodsId)) {
                $myGoodsId[] = $goodsId;
                $goodsInfoKey = self::keyFormat(self::$redisKeyPrototype['classifiedGoodsInfo'], array($cid, $goodsId));
                $goodsInfoResult = self::$redis->hMget($goodsInfoKey, self::$classifiedGoodsArray);
                $myGoods[] = $goodsInfoResult;
            }
        } while (count($myGoodsId) < self::$flipClassifiedGoodsQuantity);

        // FunBase::myDebug($myGoods);
        return $this->renderText(json_encode($myGoods)) ;
    }

    public function executeSyncGoodsInfoFromFile(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', FALSE);
        $this->_initial();
        echo <<<EOF
<form method="post" enctype="multipart/form-data">
<label for="file">文件:</label>
<input type="file" name="goodsExcel" />
<br />
<input type="submit" name="submit" value="上传" />
</form>
<br />
EOF;
        $msg = '';

        if (in_array(self::$uid, self::$adminIds)) {
            if (isset($_FILES["goodsExcel"])) {
                if ($_FILES["goodsExcel"]["error"] > 0) {
                    $msg .= "Error: " . $_FILES["goodsExcel"]["error"] . "<br />";
                } elseif (!$_FILES["goodsExcel"]["tmp_name"]) {
                    $msg .= "Error: 临时文件创建失败" . "<br />";
                } else {
                    $fp = fopen($_FILES["goodsExcel"]["tmp_name"], 'r');
                    while ($line = fgetcsv($fp)) {
                        if (count($line) == 7)
                            $this->syncGoodsInfo($line, 7);
                        elseif (count($line) == 5)
                            $this->syncGoodsInfo($line, 5);
                        elseif (count($line) == 2)
                            $this->syncGoodsInfo($line, 2);
                    }
                    fclose($fp);
                }
            }
        }
        echo $msg;
        return sfView::NONE;
    }

    private function syncGoodsInfo($goodsInfo, $counts = 5) {
        $msg = '';
        switch ($counts) {
            case 7:
            case 5:
                $isClassified = $counts == 7;
                $fields = $isClassified ? self::$classifiedGoodsArray : self::$goodsArray;
                $goodsKeyType = $isClassified ? 'classifiedGoods' : 'goods';
                $goodsInfoKeyType = $isClassified ? 'classifiedGoodsInfo' : 'goodsInfo';

                //所有商品
                $goodsKey = self::keyFormat(self::$redisKeyPrototype[$goodsKeyType], $goodsInfo[1]);
                self::$redis->sAdd($goodsKey, $goodsInfo[0]);
                //商品详情
                $goodsInfoArray = $isClassified ? array($goodsInfo[1], $goodsInfo[0]) : $goodsInfo[0];
                $goodsInfoKey = self::keyFormat(self::$redisKeyPrototype[$goodsInfoKeyType], $goodsInfoArray);
                $goodsInfoFields = array();
                for ($i = 0; $i < $counts; $i++){
                    if($isClassified && $i == 6){
                        $goodsInfo[$i] = "http://go.hupu.com/u?url=".urlencode($goodsInfo[$i]);
                    }
                    $goodsInfoFields[$fields[$i]] = $goodsInfo[$i];
                    echo $goodsInfo[$i];
                }
                self::$redis->hMset($goodsInfoKey, $goodsInfoFields);
                $msg .= 'id:' . $goodsInfo[0] . ' Success<br />';
                break;
            case 2:
                $classificationKey = self::keyFormat(self::$redisKeyPrototype['classification']);
                self::$redis->hSet($classificationKey, $goodsInfo[0], $goodsInfo[1]);
                $msg .= 'id:' . $goodsInfo[1] . ' Success<br />';
                break;
        }
        echo $msg;
    }

    private static function keyFormat($raw, $replaces = array()) {
        if ($replaces) {
            if (!is_array($replaces))
                $replaces = array($replaces);
            foreach ($replaces as $k => $v)
                $raw = str_replace('{' . $k . '}', $v, $raw);
        }
        return $raw;
    }

    private function _getDayAndRoundInfo($timestamp = NULL) {
        $round = $day = NULL;

        $timestamp = $timestamp ? $timestamp : time();
        $date = date("Y-m-d", $timestamp);
        $time = date("H:i", $timestamp);
        $endingPoints = array_keys(self::$endingPionts);
        if (in_array($date, $endingPoints)) {
            $day = array_search($date, $endingPoints) + 1;
            $timeArray = self::$endingPionts[$date];
            for ($i = 0; $i < count($timeArray); $i++) {
                $end = $timeArray[$i] + self::$endingPiontsHoursDelay[$date][$i];
                $end = (string) ($end < 10 ? "0$end" : $end);

                if ($time >= $timeArray[$i] . ":" . self::$endingPiontsMinutes && $time <= $end . ":" . self::$endingPiontsMinutes) {
                    $round = $i + 1;
                    break;
                }
            }
        }
        return array('day' => $day, 'round' => $round);
    }

    public static function getPresent($day, $round, $myRank) {
        if(is_numeric($day) && is_numeric($round)){
            /*  if ($myRank < 4)
                  return self::$endingPiontsPresents[$day][$round]['normal'][$myRank - 1];
              else {*/
            foreach (self::$endingPiontsPresents[$day][$round]['range'] as $key => $value) {
                $points = explode('-', $key);
                if ($myRank >= $points[0] && $myRank <= $points[1])
                    return self::$endingPiontsPresents[$day][$round]['range'][$key];
            }
            // }
        }
        return '';
    }

    public static function jsonOutput($param, $isError = FALSE) {
        if ($isError) {
            $param = $param ? $param : '没有任何数据';
            echo json_encode(array('status' => 'error', 'result' => $param));
        } else
            echo json_encode(array('status' => 'success', 'result' => $param));
        exit();
    }
    //end 1111翻牌游戏

    //识货团购数据同步到搜索表
    public function executeSyncGrouponToSearch(sfWebRequest $request) {
        $id = $request->getParameter('id', 0);
        $obj = TrdGrouponTable::getInstance()->find($id);
        if (!$obj){
            return $this->renderText(json_encode(array('status' => 500, 'msg' => 'ok')));
        }
        if ($obj->getStatus() != 6 || $obj->getDeletedAt() != null){
            Doctrine_Query::create()
                ->delete()
                ->from('TrdGrouponProduct u')
                ->where('u.id = ?', $id)
                ->execute();
            return $this->renderText(json_encode(array('status' => 500, 'msg' => 'ok')));
        }
        $product = TrdGrouponProductTable::getInstance()->find($id);
        $api_url = sfConfig::get('app_javaapi');
        if (!$product) {
            $product = new TrdGrouponProduct();
            $product->setId($id);
        }
        $product->setTitle($obj->getTitle());
        $product->setBrandId($obj->getBrandId());
        $product->setAttendCount($obj->getAttendCount());
        $product->setDiscount($obj->getDiscount());
        $product->setCategoryId($obj->getCategoryId());
        $product->setStartTime($obj->getStartTime());
        $product->setEndTime($obj->getEndTime());
        $product->setRank($obj->getRank());
        $brandObj = Doctrine_Query::create()//品牌
        ->select('t.id, t.name')
            ->from('TrdBrand t')
            ->where('t.id = ?', $obj->getBrandId())
            ->fetchOne();
        $brand = $brandObj ? $brandObj->getName(): '';
        $typeObj = Doctrine_Query::create()//类型
        ->select('t.id, t.name')
            ->from('TrdGrouponCategory t')
            ->where('t.id = ?', $obj->getCategoryId())
            ->fetchOne();
        $type = $typeObj ? $typeObj->getName(): '';

        $json['brand'] = $brand;
        $json['type'] = $type;
        $product->setAttrCollect(json_encode($json));
        $a = $product->save();
        return $this->renderText(json_encode(array('status' => 200, 'msg' => 'ok')));
    }

    //识货运动鞋垃圾数据清理
    public function executeDeleteRubbishData(sfWebRequest $request) {
        $id = $request->getParameter('id', 0);
        $item = TrdItemAllTable::getInstance()->find($id);
        if (!$item){
            return $this->renderText(json_encode(array('status' => 500, 'msg' => 'ok')));
        }
        if ($item && $item->getStatus()==0 && $item->getIsHide() == 0) {
            if ($item->getRootId() == 1 && $item->getChildrenId() == 8) {
                if (!$item->getAttrCollect()) return $this->renderText(json_encode(array('status' => 500, 'msg' => 'ok')));
                $attr = explode(',',$item->getAttrCollect());
                if (count($attr) != 3) return $this->renderText(json_encode(array('status' => 500, 'msg' => 'ok')));
                $mid_brand = explode('-',$attr[0]);
                $brand = ltrim($mid_brand[1],'A');
                if ($brand == 29 || $brand>1000){
                    $item->setIsHide(1);
                    $item->save();
                    return $this->renderText(json_encode(array('status' => 200, 'msg' => 'ok')));
                }

                $mid_type = explode('-',$attr[1]);
                $type = ltrim($mid_type[1],'A');
                if ($type>991){
                    $item->setIsHide(1);
                    $item->save();
                    return $this->renderText(json_encode(array('status' => 200, 'msg' => 'ok')));
                }

            }
        }
        return $this->renderText(json_encode(array('status' => 500, 'msg' => 'ok')));
    }

    //识货下线代购商品
    public function executeOfflineDaigouProduct(sfWebRequest $request) {
        sfConfig::set('sf_web_debug',false);
        $id = $request->getParameter('id');
        $item = TrdNewsTable::getInstance()->find($id);
        if ($item && !$item->getIsDelete()){
            $time = strtotime($item->getCreatedAt());
            if ($item->getIsShopping() ==1 && $time>strtotime('2014-11-20 00:00:00')){
                $item->setIsShopping(0);
                $item->save();
            }
        }
        return $this->renderText(json_encode(array('status' => 200, 'msg' => 'ok')));
    }

    //识货更新旧的代购商品
    public function executeUpdateDaigouProduct(sfWebRequest $request) {
        sfConfig::set('sf_web_debug',false);
        set_time_limit(0);
        $id = $request->getParameter('id');
        $item = TrdProductAttrTable::getInstance()->find($id);
        if ($item && $item->getNewsId()){
            $youhui = TrdNewsTable::getInstance()->find($item->getNewsId());
            if ($youhui && $youhui->getIsDelete()==0){
                $item->setTitle($youhui->getTitle());
                $item->setIntro($youhui->getIntro());
                $item->setMemo($youhui->getText());
                $item->setUrl($youhui->getOrginalUrl());
                $item->setRootId($youhui->getRootId());
                $item->setChildrenId($youhui->getChildrenId());
                $item->setCreatedAt($youhui->getPublishDate());
                $item->setLastCrawlDate($item->getLastCrawlDate()-600);
                if($item->getContent()){
                    $attr = json_decode($item->getContent(),true);
                    $rate = 6.25;
                    if (isset($attr['content'][0])){
                        $exchange = $attr['content'][0]['price']/100;
                        $price = ceil($exchange*$rate)/100;
                        $item->setExchange($exchange);
                        $item->setPrice($price);
                        $img = str_replace('.SS80', '', $attr['content'][0]['img']).'_SS400_.jpg';
                        $url_detail = parse_url($img);
                        $qiniu_name = str_replace('_SS400_.jpg','',ltrim($url_detail['path'],'/'));
                        $qiniu_name = "images/I/".time().md5($qiniu_name).'.jpg';
                        $qiniuObj = new tradeQiNiu();
                        $qiniu_url = $qiniuObj->uploadRemoteImage($img,$qiniu_name);
                        if ($qiniu_url) $item->setImgPath($qiniu_url);
                        $item->save();
                        $youhui->setProductId($id);
                        $youhui->save();
                    }
                }
            }
        }
        return $this->renderText(json_encode(array('status' => 200, 'msg' => 'ok')));
    }

    //识货下线抓取不到属性的商品
    public function executeUpdateNewsProduct(sfWebRequest $request) {
        sfConfig::set('sf_web_debug',false);
        set_time_limit(0);
        $id = $request->getParameter('id');
        $item = TrdProductAttrTable::getInstance()->find($id);
        if ($item && $item->getNewsId()){
            $youhui = TrdNewsTable::getInstance()->find($item->getNewsId());
            if ($youhui && $youhui->getIsDelete()==0){
                if($item->getShowFlag() == 0 || $youhui->getIsShopping() == 0 || $item->getExchange()>$item->getPrice() || $item->getExchange()==0){
                    $youhui->setProductId(NULL);
                    $youhui->save();
                    $item->setShowFlag(0);
                    $item->save();
                }
            }
        }
        return $this->renderText(json_encode(array('status' => 200, 'msg' => 'ok')));
    }

    //识货更新旧的代购商品
    public function executeUpdateOrderPayStatus(sfWebRequest $request) {
        sfConfig::set('sf_web_debug',false);
        $id = $request->getParameter('id');
        $orderObj = TrdHaitaoOrderTable::getInstance()->find($id);
        $paystatus = 0;
        if ($orderObj && $orderObj->getPayStatus()==0){
            switch ($orderObj->getStatus())
            {
                case '0':
                    $paystatus = 0;
                    break;
                case '1':
                case '2':
                case '3':
                case '4':
                case '5':
                case '11':
                case '12':
                case '6':
                case '7':
                    $paystatus = 1;
                    break;
                case '9':
                    $paystatus = 4;
                    break;
                case '10':
                    $paystatus = 4;
                    break;
                default:
                    $paystatus = 0;
                    break;
            }
        }else{
            return $this->renderText(json_encode(array('status' => 200, 'msg' => 'ok')));
        }
        if($orderObj->getStatus() == 10) $orderObj->setStatus(9);
        $orderObj->setPayStatus($paystatus);
        $orderObj->save();
        return $this->renderText(json_encode(array('status' => 200, 'msg' => 'ok')));
    }

    //识货更新代购商品
    public function executeUpdateAllDaigouProduct(sfWebRequest $request) {
        sfConfig::set('sf_web_debug',false);
        set_time_limit(0);
        $id = $request->getParameter('id');
        $item = TrdProductAttrTable::getInstance()->find($id);
        if ($item){
            $result = TrdNewsTable::getInstance()->createQuery()->select('*')->where('product_id = ?',$id)->execute();
            foreach ($result as $youhui) {
                if ($item->getStatus() == 1 || $item->getShowFlag() == 0){
                    $youhui->setProductId(0);
                    $youhui->setProductStartDate(0);
                    $youhui->setProductEndDate(0);
                    $youhui->save();
                } else {
                    $youhui->setProductStartDate($item->getStartDate());
                    $youhui->setProductEndDate($item->getEndDate());
                    $youhui->save();
                }
            }
        }
        $item->setPublishDate($item->getCreatedAt());
        $item->save();
        return $this->renderText(json_encode(array('status' => 200, 'msg' => 'ok')));
    }

    //获取所有预报的英文
    public function executeGetHaitaoForecastProductGroup(){
        sfConfig::set('sf_web_debug', FALSE);
        header("Content-type: text/html; charset=utf-8");
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $data = $redis->hGetAll('shihuo.haitao.forecast.ProductGroup');
        foreach ($data as $k=>$v){
            echo $v.'<br>';
        }
        exit;
    }

    //代购商品相同的订单合并
    public function executeUpdateOrderProductId(sfWebRequest $request){
        sfConfig::set('sf_web_debug',false);
        set_time_limit(0);
        $id = $request->getParameter('id');
        $order = TrdOrderTable::getInstance()->find($id);
        if($order){
            if($order->getProductId() && $order->getGid()){
                $goods = TrdHaitaoGoodsTable::getInstance()->createQuery('m')->select('*')->where('m.id = ?',$order->getGid())->limit(1)->fetchOne();
                if($goods){
                    if($goods->getStatus() == 0){
                        if($order->getProductId() != $goods->getProductId()){
                            $order->set('product_id',$goods->getProductId());
                            $order->save();
                        }
                    } else {//没货了
                        $product = TrdProductAttrTable::getInstance()->find($order->getProductId());
                        if($product && $product->getName()){
                            $productObj = TrdProductAttrTable::getInstance()->createQuery()->where('name = ?',$product->getName())->andWhere('status = ?',0)->andWhere('show_flag = ?',1)->execute();
                            if(count($productObj)>0){
                                foreach($productObj as $k=>$v){
                                    if($v->getId() == $order->getProductId()) continue;
                                    $goodsObj = TrdHaitaoGoodsTable::getInstance()->createQuery('m')->select('*')->where('m.product_id = ?',$v->getId())->andWhere('status = ?',0)->limit(1)->fetchOne();
                                    if($goodsObj){
                                        $order->set('product_id',$v->getId());
                                        $order->save();
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }

            }
        }
        return $this->renderText(json_encode(array('status' => 200, 'msg' => 'ok')));
    }

    //识货代购商品运费转重量
    public function executeDaigouProductSaveWeight(sfWebRequest $request) {
        sfConfig::set('sf_web_debug',false);
        set_time_limit(0);
        $id = $request->getParameter('id');
        $item = TrdProductAttrTable::getInstance()->find($id);
        if ($item){
            $weight = $item->getWeight();
            $business_weight = $item->getBusinessWeight();
            if(empty($weight) & empty($business_weight)){
                if($item->getFreight() >= 46){
                    $weight = round(($item->getFreight()/40)-0.7,2)+0.02;
                    $item->setWeight($weight);
                    $item->save();
                }
            }
        }
        return $this->renderText(json_encode(array('status' => 200, 'msg' => 'ok')));
    }


    /**
     * ask 回复增加积分
     */
    public function executeAskReply(sfWebRequest $request)
    {
        $messageJson = $request->getParameter("message");
        $token = $request->getParameter("token");
        $myToken = md5("shihuohupu123");

        if($myToken == $token)
        {
            $message = json_decode($messageJson,true);
            $hupuUid = $message['uid'];
            $action    = $message['action'];
            $actionid = isset($message['actionid'])?$message['actionid']:0;
            $trdAccount = TrdAccountTable::getByHupuId($hupuUid);
            $hupuUname = $trdAccount->getHupuUsername();

            $category = 9;
            $stime = date("Y-m-d 00:00:00");
            $etime = date("Y-m-d H:i:s",strtotime("+1 day",strtotime($stime)));
            $categorys = array();
            $categorys[] = $category;
            $userTodayIG = TrdAccountHistoryTable::getSumIGByCateTime($hupuUid,$categorys,$stime,$etime);
            $history = TrdAccountHistoryTable::getHistoryByActionid($hupuUid,$actionid);

            if($userTodayIG['integral'] <40 && empty($history) )
            {
                if($category == 9 )
                {
                    $integral =2 ;
                }

                $beforeIntegral = $trdAccount->getIntegral();
                $beforeGold     = $trdAccount->getGold();
                $trdAccount->setIntegral($trdAccount->getIntegral()+$integral);
                $trdAccount->setIntegralTotal($trdAccount->getIntegralTotal()+$integral);
                $afterIntegral = $trdAccount->getIntegral();
                $afterGlod     = $trdAccount->getGold();
                $trdAccount->save();

                $trdAccountHistory = new TrdAccountHistory();
                $trdAccountHistory->setHupuUid($hupuUid);
                $trdAccountHistory->setHupuUsername($hupuUname);
                $trdAccountHistory->setCategory($category);
                $trdAccountHistory->setType(0);
                $trdAccountHistory->setExplanation("问答回复加积分");
                $trdAccountHistory->setActionid($actionid);
                $trdAccountHistory->setIntegral($integral);
                $trdAccountHistory->setGold(0);

                $trdAccountHistory->setBeforeIntegral($beforeIntegral);
                $trdAccountHistory->setBeforeGold($beforeGold);
                $trdAccountHistory->setAfterIntegral($afterIntegral);
                $trdAccountHistory->setAfterGold($afterGlod);

                $trdAccountHistory->save();
            }
            exit('ok');
        }
    }

    /**
     * 获取需要更新重量的product
     */
    public function executeGetNeedUpdateProuct(sfWebRequest $request){
        set_time_limit(0);
        ini_set('memory_limit', '128M');
        $product = TrdProductAttrTable::getInstance()
            ->createQuery('t')
            ->select('*')
            ->where('t.status  = ?', 0)
            ->andWhere('t.goods_id  != "" and t.goods_id is not null')
            ->andWhere('t.business_weight  = "" or t.business_weight is null')
            ->andWhere('t.weight  = "" or t.weight is null')
            ->fetchArray();
        if(count($product)>0){
            $goods_id_arr = array();
            foreach($product as $k=>$v){
                array_push($goods_id_arr,$v['goods_id']);
            }
            $goods = TrdHaitaoGoodsTable::getInstance()
                ->createQuery('t')
                ->select('*')
                ->whereIn('t.id', $goods_id_arr)
                ->andWhere('t.status = ?',0)
                ->fetchArray();
            if(count($goods)>0){
                foreach($product as $kk=>$vv){
                    foreach($goods as $m=>$n){
                        if($vv['goods_id'] == $n['id'] && $vv['id'] == $n['product_id']){
                            echo $vv['id'].',';
                        }
                    }
                }
            }
            exit('over');
        }
    }

    /**
     * 获取需要更新重量的product
     */
    public function executeSyncMainIsComment(sfWebRequest $request){
        $id = $request->getParameter("id");
        if(!$id) return false;
        $result = TrdOrderTable::getInstance()->createQuery()->select('*')->where('id = ?',$id)->andWhere('status = 2')->andWhere('is_comment = 0')->execute();
        if (count($result)>0) {
            $mainObj = TrdMainOrderTable::getInstance()->findOneByOrderNumber($result[0]->getOrderNumber());
            $mainObj->setStatus(6);
            $mainObj->save();
        }
        return $this->renderText(json_encode(array('status' => 200, 'msg' => 'ok')));
    }


    /**
     * 收藏action
     */
    public function  executeCollection(sfWebRequest $request) {
        header('Access-Control-Allow-Origin: http://m.shihuo.cn');
        header('Access-Control-Allow-Credentials: true');
        sfConfig::set('sf_web_debug',false);

        /* 判断来源 */
        $refer = $request->getReferer();
        if ($refer && !preg_match("/shihuo.cn/",$refer)) return FunBase::ajaxReturn(array('status'=>0,'info'=>'非法请求！'));

        /* 获取参数  */
        $_id = (int)$request->getParameter("id");
        $_type = (string)$request->getParameter("type");
        if(empty($_id) || empty($_type)) return FunBase::ajaxReturn(array('status'=>0,'info'=>'非法请求！'));

        /* 调用收藏服务  */
        $serviceRequest = new tradeServiceClient();
        $serviceRequest->setMethod('global.user.add.collection');
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setApiParam('collection_id', $_id);
        $serviceRequest->setApiParam('type', $_type);
        $serviceRequest->setUserToken($request->getCookie('u'));
        $return = $serviceRequest->execute();
        if( false === $return->hasError() ) {
            return FunBase::ajaxReturn(array('status'=>1,'info'=>'收藏成功！'));
        } else {
            $_code = (int)$return->getStatusCode();
            //已经收藏过了
            if($_code == 401) return FunBase::ajaxReturn(array('status'=>2,'info'=>'您已经收藏过该商品了！'));
            return FunBase::ajaxReturn(array('status'=>0,'info'=>$return->getError()));
        }
        /* 返回   */
        return FunBase::ajaxReturn(array('status'=>1,'info'=>'收藏成功！'));
    }


    /**
     * 根据淘宝id获取淘宝店铺信息
     */
    public function executeGetDetailByTid(sfWebRequest $request){
        sfConfig::set('sf_web_debug',false);

        //安全判断
        if (!$request->isMethod('POST')){//判断请求方式
            return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'非法请求')));
        }
        $refer = $request->getReferer();
        if ($refer && !preg_match("/http:\/\/www.shihuo.cn/",$refer)){//判断来源
            return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'非法请求')));
        }

        $tid = $request->getParameter("tid");
        if(!$tid) return $this->renderText(json_encode(array('status' => 1, 'msg' => '参数不合法')));

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $key = 'trade_taobao_tmall_shop_tttid_'.$tid;
        $data = $redis->get($key);
        if($data) return $this->renderText(json_encode(array('status' => 0, 'data'=>unserialize($data), 'msg' => 'ok')));

        try {
            $url = "http://hws.m.taobao.com/cache/wdetail/5.0/?id=".$tid."&ttid=2013@taobao_h5_1.0.0&exParams={}";
            $info = tradeCommon::getContents($url,array(),5);
            if ($info){
                $taobaoInfo = json_decode($info,true);
                if (!isset($taobaoInfo['ret'][0]) || (isset($taobaoInfo['ret'][0]) && $taobaoInfo['ret'][0] == 'SUCCESS::调用成功')){
                    unset($taobaoInfo['data']['seller']['nick']);
                    unset($taobaoInfo['data']['seller']['creditLevel']);
                    unset($taobaoInfo['data']['seller']['goodRatePercentage']);
                    unset($taobaoInfo['data']['seller']['shopId']);
                    unset($taobaoInfo['data']['seller']['weitaoId']);
                    unset($taobaoInfo['data']['seller']['fansCount']);
                    unset($taobaoInfo['data']['seller']['fansCountText']);
                    unset($taobaoInfo['data']['seller']['bailAmount']);
                    unset($taobaoInfo['data']['seller']['picUrl']);
                    unset($taobaoInfo['data']['seller']['starts']);
                    unset($taobaoInfo['data']['seller']['actionUnits']);
                    $evaluateInfo[0] = $taobaoInfo['data']['seller']['evaluateInfo'][2];
                    $evaluateInfo[1] = $taobaoInfo['data']['seller']['evaluateInfo'][1];
                    $evaluateInfo[2] = $taobaoInfo['data']['seller']['evaluateInfo'][0];
                    $taobaoInfo['data']['seller']['evaluateInfo'] = $evaluateInfo;
                    $taobaoInfo['data']['seller']['type'] = $taobaoInfo['data']['seller']['type'] == 'B' ? '天猫' : '淘宝';
                    $redis->set($key,serialize($taobaoInfo['data']['seller']),3600*24*30);
                    return $this->renderText(json_encode(array('status' => 0, 'data'=>$taobaoInfo['data']['seller'], 'msg' => 'ok')));
                } else {
                    throw new Exception('系统繁忙，请稍后再试', 1);
                }
            } else {
                throw new Exception('系统繁忙，请稍后再试', 1);
            }
            var_dump($info);die;
        } catch (Exception $e) {
            return $this->renderText(json_encode(array('status' => $e->getCode(), 'msg' => $e->getMessage())));
        }

    }

    # 手套图片接口
    public function executeTaobaoImageUpload(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);

        $tag1 = false;
        $tag2 = false;
        $tag  = "";
        if (!isset($_FILES["upfile"]) || !is_uploaded_file($_FILES["upfile"]["tmp_name"]) || $_FILES["upfile"]["error"] != 0) {
            $tar1 = true;
            $tag = "imgfile";
        }

        if (!isset($_FILES["imgFile"]) || !is_uploaded_file($_FILES["imgFile"]["tmp_name"]) || $_FILES["imgFile"]["error"] != 0) {
            $tar1 = true;
            $tag = "upfile";
        }

        if($tag1 && $tag2)
        {
            echo "ERROR:非法上传";
            exit(0);
        }


        if($tag=="imgfile"){

            try
            {
                $key = 'shihuo_shoutao_sessionkey';
                $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
                $redis->select(1);
                $sessionKey = $redis->get($key);
                if(empty($sessionKey))
                {
                    throw new Exception('没有权限，还未授权');
                }
                else
                {
                    $size = filesize($_FILES["imgFile"]["tmp_name"]);
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $ext = array_search(
                        $finfo->file($_FILES['imgFile']['tmp_name']),
                        array(
                            'jpg' => 'image/jpeg',
                            'png' => 'image/png',
                            'gif' => 'image/gif',
                        ),true);
                    $imgInfo = getimagesize($_FILES["imgFile"]["tmp_name"]);
                    if( empty($imgInfo[0]) || empty($imgInfo[1]) )
                    {
                        throw new Exception('图片宽高有问题');
                    }

                    $width = $imgInfo[0];
                    $height = $imgInfo[1];
                    if($width<500)
                    {
                        throw new Exception('宽不能小于500');
                    }

                    if($height<500)
                    {
                        throw new Exception('高不能小于500');
                    }

                    $sf_root_dir = sfConfig::get('sf_app_dir').'/lib/shoutao/TopSdk.php';
                    include($sf_root_dir);

                    $c = new ShoutaoClient;
                    $req = new PictureUploadRequest;
                    $req->setPictureCategoryId(0);
                    //附件上传的机制参见PHP CURL文档，在文件路径前加@符号即可
                    $req->setImg('@'.$_FILES["imgFile"]["tmp_name"]);
                    $new_file_name = 'ucditor/'.date('Ymd').'/'.md5_file($_FILES['imgFile']['tmp_name']).time().'.'.$ext;
                    $req->setImageInputTitle($new_file_name);
                    $req->setClientType("client:computer");
                    $resp = $c->execute($req,$sessionKey);

                    if(!empty($resp->picture) && $resp->picture->status == '0')
                    {
                        $info['url'] = $resp->picture->picture_path;
                        $info['error'] = 0;
                    }
                    else
                    {
                        throw new Exception('图片上传失败');
                    }
                }

            }
            catch(Exception $e)
            {
                $info['message'] = $e->getMessage();
                $info['error'] = 1;
            }
        }
        unlink($_FILES["imgFile"]["tmp_name"]);
        return $this->renderText(json_encode($info));
    }


    //监测消息队列发送失败条数
    public function executeGetMqTemporaryLength(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', FALSE);
        header("Content-type: text/html; charset=utf-8");

        $redis = new tradeUpdateRedisList('trade_mq_temporary_message');
        $length = $redis->getListLength();
        if ($length > 100) {
            echo "trade错误：".$length."<br>";
            exit();
        }
        $redis = new tradeUpdateRedisList('kaluli_mq_temporary_message');
        $length = $redis->getListLength();
        if ($length > 100) {
            echo "kaluli错误：".$length."<br>";
            exit();
        }
        echo $length;
        return true;
    }

    //代购商品信息转化
    public function executeDaigouProductSwitch(sfWebRequest $request)
    {
        set_time_limit(0);
        sfConfig::set('sf_web_debug', FALSE);
        header("Content-type: text/html; charset=utf-8");
        $id = $request->getParameter('id');

        if ($id) {
            $ids = array($id);
        } else {
            $ids = array("115627","16541","79552","109293","108942","107284","105675","18648","96376","30438","46450","30847","91548","19872","89822","56546","85164","83941","38900","79222","59599","75750","73853","62620","17295","27681","66953","61309","60923","60181","59748","59057","28649","35716","56975","56613","56872","56350","56331","55726","55717","55675","55666","55164","53949","26973","51593","50589","29928","5496","27884","42628","23498","36086","33436","32913","27814","27774","27673","27624","27499","27183","27517","27526","43678");
        }

        foreach ($ids as $v){
            $product = TrdProductAttrTable::getInstance()->find($v);
            if ($product && $product->getNewsId()){
                $news = TrdNewsTable::getInstance()->find($product->getNewsId());
                if ($news) {
                    $product->set('title',$news->getTitle());
                    $product->set('intro',$news->getIntro());
                    $product->set('memo',$news->getText());
                    $product->save();
                }
            }
        }
        exit('success');
    }


    # XML SITEMAP
    public function executeXmlSiteMapByMonth(sfWebRequest $request)
    {
        header('Content-Type: text/xml');
        //   $date = $request->getParameter('date');
        //   $firstDay = $lastDay = '';
        //    if(empty($date)) goto End;

        $now = time();
        $today = date('Ymd',$now);
        $lastDay =  strtotime($today);
        $firstDay =  strtotime("{$today} -1 month");


        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(5);

        $youhuiKey = "shihuo_xml_date_youhui_";
        $faxianKey = "shihuo_xml_date_faxian_";

        $dom=new DomDocument('1.0', 'utf-8');
        //  创建一个XML文档并设置XML版本和编码。。

        //  创建根节点
        $article = $dom->createElement('urlset');
        $dom->appendchild($article);

        //  创建属性节点
        $attr = $dom->createAttribute('xmlns');
        $attr->value="http://www.sitemaps.org/schemas/sitemap/0.9";
        $article->appendchild($attr);
        $attr1 = $dom->createAttribute('xmlns:xsi');
        $attr1->value="http://www.w3.org/2001/XMLSchema-instance";
        $article->appendchild($attr1);
        $attr2 = $dom->createAttribute('xsi:schemaLocation');
        $attr2->value="http://www.sitemaps.org/schemas/sitemap/0.9
                    http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd";
        $article->appendchild($attr2);
        $root = $dom -> documentElement;//获得根节点(root)

        while($firstDay<$lastDay)
        {
            $currentDay = date('Ymd',$firstDay);
            $youhui_data = $redis->scard($youhuiKey.$currentDay);
            $find_data = $redis->scard($faxianKey.$currentDay);

            if($youhui_data>0 || $find_data>0)
            {
                $index = $dom->createElement('url');
                $loc = $dom->createElement('loc');
                $newsloc = $dom->createTextNode('http://www.shihuo.cn/api/xmlSiteMap?date='.$currentDay);
                $loc->appendChild($newsloc);

                $index->appendChild($loc);
                $root->appendChild($index);
            }

            $firstDay += 86400;
        }
        $content = $dom->saveXML();
        unset($dom);
        echo $content;
        exit;

    }


    # XML SITEMAP
    public function executeXmlSiteMap(sfWebRequest $request)
    {
        header('Content-Type: text/xml');

        $date = $request->getParameter('date');
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(5);

        $youhuiKey = "shihuo_xml_date_youhui_";
        $faxianKey = "shihuo_xml_date_faxian_";
        $youhui_data = $redis->SMEMBERS($youhuiKey.$date);
        $find_data = $redis->SMEMBERS($faxianKey.$date);

        if (!empty($youhui_data)){
            foreach ($youhui_data as $k=>$v){
                $data[] =  'http://www.shihuo.cn/youhui/'.$v.'.html';
            }
        }
        if (!empty($find_data)){
            foreach ($find_data as $k=>$v){
                $data[] =  'http://www.shihuo.cn/detail/'.$v.'.html';
            }
        }

        $dom=new DomDocument('1.0', 'utf-8');
        //  创建一个XML文档并设置XML版本和编码。。

        //  创建根节点
        $article = $dom->createElement('urlset');
        $dom->appendchild($article);

        //  创建属性节点
        $attr = $dom->createAttribute('xmlns');
        $attr->value="http://www.sitemaps.org/schemas/sitemap/0.9";
        $article->appendchild($attr);
        $attr1 = $dom->createAttribute('xmlns:xsi');
        $attr1->value="http://www.w3.org/2001/XMLSchema-instance";
        $article->appendchild($attr1);
        $attr2 = $dom->createAttribute('xsi:schemaLocation');
        $attr2->value="http://www.sitemaps.org/schemas/sitemap/0.9
                    http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd";
        $article->appendchild($attr2);
        $root = $dom -> documentElement;//获得根节点(root)
        if (!empty($data)) {
            foreach ($data as $vv) {
                $index = $dom->createElement('url');
                $loc = $dom->createElement('loc');
                $newsloc = $dom->createTextNode($vv);
                $loc->appendChild($newsloc);

                $priority = $dom->createElement('priority');
                $newspriority = $dom->createTextNode('1.0');
                $priority->appendChild($newspriority);

                $changefreq = $dom->createElement('changefreq');
                $newschangefreq = $dom->createTextNode('daily');
                $changefreq->appendChild($newschangefreq);

                $index->appendChild($loc);
                $index->appendChild($priority);
                $index->appendChild($changefreq);
                $root->appendChild($index);
            }
        }
        $content = $dom->saveXML();
        unset($dom);
        echo $content;
        exit;
    }

    /**
     * passport 获取手机验证码
     * @param sfWebRequest $request
     * @return sfView
     */
    public function executeGetPassportIdentifyingCode(sfWebRequest $request){
        header('Access-Control-Allow-Origin: http://m.shihuo.cn');
        header('Access-Control-Allow-Credentials: true');
        sfConfig::set('sf_web_debug', false);
        $mobile = $request->getParameter('mobile');
        if(!$mobile){
            return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'联系人手机号码不可为空')));
        }
        if($mobile && !preg_match("/^1[0-9]{10}$/",$mobile)){
            return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'联系人手机号码格式有误')));
        }
        $passport = new tradePassportApi();
        $res = $passport->getContent("/ucenter/sendMobileCode.api", array('mobile'=>$mobile));
        $result = json_decode($res, 1);
        if (isset($result['code']) && $result['code'] == 1000){
            return $this->renderText(json_encode(array('status'=>0, 'data'=>'', 'msg'=>'success')));
        }
        return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'系统异常，请稍后再试')));
    }

    /**
     * passport 手机短信一键登录
     * @param sfWebRequest $request
     * @return sfView
     */
    public function executeGetPassportUserInfo(sfWebRequest $request){
        header('Access-Control-Allow-Origin: http://m.shihuo.cn');
        header('Access-Control-Allow-Credentials: true');
        sfConfig::set('sf_web_debug', false);
        $mobile = $request->getParameter('mobile');
        $authcode = $request->getParameter('authcode');
        if(!$mobile || !$authcode){
            return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'联系人手机号码和验证码必填')));
        }
        if($mobile && !preg_match("/^1[0-9]{10}$/",$mobile)){
            return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'联系人手机号码格式有误')));
        }
        if($authcode && !preg_match("/^[0-9]{6}$/", $authcode)){
            return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'验证码格式有误')));
        }
        $expire = strtotime("1 years");
        $passport = new tradePassportApi();
        $res = $passport->getContent("/ucenter/mobileAutoLogin.api", array('mobile'=>$mobile, 'authcode'=>$authcode), 1);
        preg_match('/Set-Cookie: u=(.*)\;Path=\/\;[\s\S]*Set-Cookie\: ua=(\d+)\;Path=\/\;/U', $res, $matches);
        if (isset($matches[1]) && isset($matches[2])){
            setcookie('u', $matches[1], $expire, '/', 'shihuo.cn', null, true);
            setcookie('ua', $matches[2], $expire, '/', 'shihuo.cn');
            return $this->renderText(json_encode(array('status'=>0, 'data'=>'', 'msg'=>'success')));
        }
        return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'系统异常，请稍后再试')));
    }

    public function executeHotDaigou(sfWebRequest $request)
    {
        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(2);
        $hot_daigou_key = 'trade.kefu.hotDaigou';
        $daigou_arr = unserialize($redis->get($hot_daigou_key));
        if (!$daigou_arr) {
            $daigou_obj = TrdProductAttrTable::getInstance()->getProductByHits(6);
            $daigou_arr = array();
            foreach($daigou_obj as $k => $v){
                $daigou_arr[$k]['id'] = $v->getId();
                $daigou_arr[$k]['img_path'] = $v->getImgPath();
                $daigou_arr[$k]['title'] = $v->getTitle();
                $daigou_arr[$k]['price'] = $v->getPrice();
                if ($v->getGoodsId()) {
                    $daigou_arr[$k]['goods_id'] = $v->getGoodsId();
                    $daigou_arr[$k]['url'] = 'http://www.shihuo.cn/haitao/buy/' . $v->getId() . '-' . $v->getGoodsId() . '.html';
                } else {
                    $daigou_arr[$k]['goods_id'] = 0;
                    $daigou_arr[$k]['url'] = 'http://www.shihuo.cn/haitao/buy/' . $v->getId() . '.html';
                }
            }

            $redis->set($hot_daigou_key, serialize($daigou_arr), 600);
        }
        $this->products = $daigou_arr;
    }

    //临时用
    public function executeGetProductAttrAAAAAAAAAA(sfWebRequest $request)
    {
        $id = $request->getParameter('id');
        if (empty($id)){
            return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'faild')));
        }
        $obj = TrdProductAttrTable::getInstance()->find($id);
        if ($obj){
            return $this->renderText(json_encode(array('status'=>0, 'data'=>array('title'=>$obj->getTitle(),'img_path'=>$obj->getImgPath(),'purchase_flag'=>$obj->getPurchaseFlag()), 'msg'=>'success')));
        }
        return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'faild')));
    }

    public function executeSaveProductAttrAAAAAAAAAA(sfWebRequest $request)
    {
        $id = $request->getParameter('id');
        $img_path = $request->getParameter('img');
        if (empty($id) || empty($img_path)){
            return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'faild')));
        }
        $obj = TrdProductAttrTable::getInstance()->find($id);
        if ($obj && (!$obj->get('img_path') || preg_match("/haitao\/product\/yijiangou/", $obj->get('img_path')))){
            $obj->set('img_path', $img_path);
            $obj->save();
            return $this->renderText(json_encode(array('status'=>0, 'data'=>'', 'msg'=>'success')));
        }
        return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'faild')));
    }

    public function executeGetProductAttrByNameAAAAAAAAAA(sfWebRequest $request)
    {
        $name = $request->getParameter('name');
        if (empty($name)){
            return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'faild')));
        }
        $obj = TrdProductAttrTable::getInstance()->findOneByName($name);
        if ($obj){
            return $this->renderText(json_encode(array('status'=>0, 'data'=>array('id'=>$obj->getId(), 'title'=>$obj->getTitle(),'img_path'=>$obj->getImgPath(),'purchase_flag'=>$obj->getPurchaseFlag()), 'msg'=>'success')));
        }
        return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'faild')));
    }

    public function executeGetProductAttrByNamesAAAAAAAAAA(sfWebRequest $request)
    {
        header("Content-type: text/html; charset=utf-8");
        sfConfig::set('sf_web_debug', false);
        $name = $request->getParameter('name');
        if (empty($name)){
            return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'faild')));
        }
        $obj = TrdProductAttrTable::getInstance()->createQuery('m')
            ->select('*')
            ->whereIn('name', $name)
            ->execute();
        if ($obj){
            $res = array();
            foreach ($obj as $k => $v){
                if (isset($res[$v->getName()]) && $v->getPurchaseFlag() == 0 && $v->getStatus() == 0 && $v->getShowFlag() == 1){
                    $res[$v->getName()]['id'] = $v->getId();
                    $res[$v->getName()]['name'] = $v->getName();
                    $res[$v->getName()]['title'] = $v->getTitle();
                    $res[$v->getName()]['img_path'] = $v->getImgPath();
                    $res[$v->getName()]['purchase_flag'] = $v->getPurchaseFlag();
                    $res[$v->getName()]['status'] = $v->getStatus();
                } elseif(!isset($res[$v->getName()])) {
                    $res[$v->getName()]['id'] = $v->getId();
                    $res[$v->getName()]['name'] = $v->getName();
                    $res[$v->getName()]['title'] = $v->getTitle();
                    $res[$v->getName()]['img_path'] = $v->getImgPath();
                    $res[$v->getName()]['purchase_flag'] = $v->getPurchaseFlag();
                    $res[$v->getName()]['status'] = $v->getStatus();
                }
            }
            foreach ($name as $v){
                if(!isset($res[$v])){
                    $res[$v] = array();
                }
            }

            return $this->renderText(json_encode(array('status'=>0, 'data'=>$res, 'msg'=>'success')));
        }
        return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'faild')));
    }

    //获取商品top500
    public function executeGetProductAttrTopAAAAAAAAAA(sfWebRequest $request)
    {
        header("Content-type: text/html; charset=utf-8");
        sfConfig::set('sf_web_debug', false);
        $limit = $request->getParameter('limit',120);
        $category = $request->getParameter('category', '男运动鞋');
        $query = TrdAmzProductTable::getInstance()->createQuery('m')
            ->select('*')
            ->where('shid > 0')
            ->andWhere('category LIKE ?', '%'.$category.'%')
            ->andWhere('cnname is not null')
            ->orderBy('shid asc')
            //->orderBy('id asc')
            ->limit($limit);
        $list = $query->execute();
        $ids = array();
        foreach($list as $v){
            if($v->get('shid') != 99999999) array_push($ids, $v->get('shid'));
        }

        //
        //$ids = array(54617,5681,5279,54472,5010,4574,33188,47173,4587,20529,54604,97231,119294,80653,20533,72713,48040,25923,108888,91688,85172,54222,86203,73585,55467,90092,107072,79753,99752,45487,44836,87983,89814,4586,72004,152471,179816,179825,179806,179808,179810,179821,179907,179814,179819,179817,179765,179803,179801,179766,179770,179774,179777,179780,179783,179785,179787,179790,179792,179794,179796,179798,179905,179828,179853,179882,179856,179880,179859,179861,179877,179898,179874,179871,179868,179867,179885,179851,179888,179833,179834,179838,179839,179862,179902,179842,179844,179846,179848,179895,179893,179864,179762,179630,179660,179663,179666,179668,179673,179675,179679,179681,179682,179685,179687,179688,179658,179657,179633,179634,179635,179637,179639,179641,179642,179645,179648,179650,179652,179655,179690,179691,179725,179728,179731,179733,179736,179739,179741,179744,179747,179749,179752,179754,179722,179720,179695,179697,179700,179701,179703,179705,179707,179709,179712,179715,179716,179717,179761,180079,180120,180121,180123,180125,180127,180130,180132,180134,180136,180137,180139,180141,180117,180115,180082,180086,180089,180093,180095,180097,180099,180103,180107,180110,180112,180114,180142,180145,180172,180174,180176,180178,180180,180182,180184,180186,180188,180189,180192,180194,180171,180169,180147,180149,180150,180152,180153,180155,180156,180158,180160,180162,180166,180167,180330,180077,179909,179946,179949,179953,179955,179958,179962,179964,179969,179972,179975,179977,179979,179944,179941,179912,179913,179916,179920,179922,179924,179927,179930,179932,179933,179936,179937,179982,179985,180032,180035,180040,180044,180046,180050,180054,180061,180064,180066,180069,180072,180030,180027,179990,179993,179996,179998,180001,180003,180006,180008,180011,180013,180020,180023,180074,179628,179074,179161,179171,179176,179180,179187,179191,179194,179203,179210,179215,179221,179223,179158,179151,179087,179098,179101,179113,179120,179123,179132,179136,179140,179143,179146,179148,179226,179229,179281,179283,179286,179288,179294,179297,179301,179302,179312,179316,179319,179320,179279,179274,179232,179235,179239,179241,179244,179251,179257,179259,179264,179267,179270,179272,179322,179059,7595,53853,57713,68888,72449,76413,80360,85041,90096,90179,91728,93459,101534,53164,51750,16337,16707,25066,25412,25627,36967,38205,42744,44959,45295,47365,47762,101556,102158,151227,151239,151262,151279,151280,152448,152463,158230,158376,160549,169418,177903,147985,129913,103978,104604,108210,108294,108990,111043,115525,120506,121526,122980,123123,129281,179027,179480,179521,179523,179526,179529,179532,179537,179539,179542,179545,179549,179551,179553,179518,179512,179482,179484,179487,179489,179491,179493,179496,179499,179502,179506,179508,179510,179555,179556,179591,179593,179598,179601,179604,179607,179609,179614,179615,179616,179618,179620,179587,179586,179559,179562,179565,179567,179569,179571,179573,179576,179578,179581,179583,179584,179623,179477,179325,179380,179382,179386,179389,179392,179393,179395,179397,179401,179403,179405,179408,179377,179375,179327,179331,179334,179338,179343,179351,179358,179363,179366,179371,179372,179373,179410,179413,179447,179449,179451,179452,179453,179456,179459,179462,179464,179466,179469,179471,179444,179442,179416,179418,179420,179422,179424,179427,179429,179430,179433,179435,179437,179440,179475);

        $content = '';
        if ($ids){
            $objProduct = tradeCommon::getContents('http://www.shihuo.cn/api/getProductAttrTop100AAAAAAAAAA',array('ids'=>$ids),10,'POST');
            $result = json_decode($objProduct, true);
            if (!$result['status']){
                foreach($result['data'] as $k=>$v){
                    $title = $v['title'];
                    $text = FunBase::base64ForQiniu($title);
                    if($v['goods_id']){
                        $url = 'http://www.shihuo.cn/haitao/buy/'.$v['id'].'-'.$v['goods_id'].'.html';
                    } else {
                        $url = 'http://www.shihuo.cn/haitao/buy/'.$v['id'].'.html';
                    }
                    $img = $v['img_path'].'?imageView2/1/w/300/h/300';
                    $content .= "<p><a target='_blank' href=".$url.">".($k+1).'、'.$title."</a></p>";
                    $price = round($v['price']*0.8);
                    $mid_content = $title."_shihuoflag_".$url."_shihuoflag_".$img."_shihuoflag_".$price."_shihuoflag_美国亚马逊";
                    $content .= '<p><img class="trade_editor_test" title="'.$mid_content.'" src="http://shihuo.hupucdn.com/youhuiIndex/201507/3010/ab48017fd3aea39b84d9df27ea13c51b.png?watermark/2/text/'.$text.'/font/5b6u6L2v6ZuF6buR/dx/150/dy/30/gravity/NorthWest"/></p>';
                }
            }
        }
        echo $content;die;
        exit();
    }

    //获取商品
    public function executeGetProductAttrTop100AAAAAAAAAA(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        $ids =$request->getParameter('ids', array());
        if (empty($ids)){
            return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'faild')));
        }
        $list = TrdProductAttrTable::getObjByIds($ids);
        $res= array();
        $i = 0;
        if (count($list) > 0){
            foreach ($list as $k=>$v){
                $attr = json_decode($v->getContent(), true);
                if (count($attr['attr']) < 3 && $v->getImgPath()){
                    $res[$i]['id'] = $v->getId();
                    $res[$i]['goods_id'] = $v->getGoodsId();
                    $res[$i]['title'] = $v->getTitle();
                    $res[$i]['img_path'] = $v->getImgPath();
                    $res[$i]['price'] = $v->getPrice();
                    if ($i == 100){
                        return $this->renderText(json_encode(array('status'=>0, 'data'=>$res, 'msg'=>'success')));
                    }
                    $i++;
                }

            }
        }
        if($res){
            return $this->renderText(json_encode(array('status'=>0, 'data'=>$res, 'msg'=>'success')));
        }
        return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'faild')));

    }

    //获取商品
    public function executeGetProductAttrSwitchAAAAAAAAAA(sfWebRequest $request)
    {
        ini_set('memory_limit', '128M');
        set_time_limit(0);
        sfConfig::set('sf_web_debug', false);
        $ids =$request->getParameter('ids', '');
        if (empty($ids)){
            return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'faild')));
        }
        $ids_arr = explode(',', $ids);
        $list = TrdProductAttrTable::getObjByIds($ids_arr);
        $res= array();
        foreach($list as $k=>$v){
            if($v->getGoodsId()){
                $goods_info = TrdHaitaoGoodsTable::getInstance()->createQuery('m')->select('*')->where('m.product_id = ?',$v->getId())->andWhere('m.status = 0')->limit(1)->fetchOne();
                if (!$goods_info) {
                    $goods_info = TrdHaitaoGoodsTable::getInstance()->createQuery('m')->select('*')->where('m.id = ?', $v->getGoodsId())->andWhere('m.status = 0')->limit(1)->fetchOne();
                    if ($goods_info && $goods_info->getProductId()) {
                        echo $goods_info->getProductId().',';
                    }
                }
            }
        }
        exit();
    }

    //临时用
    public function executeGetProductAttrUrlAAAAAAAAAA(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        //$id = $request->getParameter('id');
        $limit = $request->getParameter('limit', 2);
//        if (empty($id)){
//            return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'faild')));
//        }
        $status = tradeCommon::getLock('shihuo.product.attr.activity.AAAAAAAAAA', 10);//获取锁
        if ($status[0]['status'] < 1) {
            return $this->renderText(json_encode(array('status' => 1, 'data' => null, 'msg' => '系统异常')));
        }
        $key = 'shihuo_haitao_activity_list';
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $id = $redis->get($key);
        if (!$id) $id = 0;
        $obj = TrdProductAttrTable::getInstance()->createQuery()->select('*')->where('id > ?',$id)->andWhere('status = ?', 0)->andWhere('name <> ""')->andWhere('business = ?', '美国亚马逊')->andWhere('purchase_flag = ?', 0)->orderBy('id asc')->limit($limit)->execute();;
        $res = array();
        if(count($obj) > 0){
            foreach($obj as $k=>$v){
                if ($v->getName()){
                    $res[$k]['url'] = 'http://www.amazon.com/dp/'.$v->getName().'/?psc=1';
                    $res[$k]['id'] = $v->getId();
                    $redis->set($key, $v->getId());
                }

            }
        }
        tradeCommon::releaseLock('shihuo.product.attr.activity.AAAAAAAAAA');//释放锁
        return $this->renderText(json_encode(array('status'=>200, 'data'=>$res, 'msg'=>'success')));
    }

    //临时用
    public function executeSaveProductAttrUrlAAAAAAAAAA(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        $data = $request->getParameter('data');
        if (empty($data)){
            return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'faild')));
        }
        $content = json_decode($data ,true);

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');

        foreach($content as $k=>$v){
            if(empty($v['set'])) return $this->renderText(json_encode(array('status'=>1, 'data'=>'', 'msg'=>'不存在集合id')));
            $key_add = 'shihuo_haitao_activity_add_'.$v['set'];
            $key_delete = 'shihuo_haitao_activity_delete_'.$v['set'];
            if ($v['type'] == 1){
                $flag = FunBase::existsActivitySet($v['set'],$v['id']);
                if(!$flag) $redis->SADD($key_add, $v['id']);
            } elseif($v['type'] == 2) {
                $flag = FunBase::existsActivitySet($v['set'],$v['id']);
                if($flag) $redis->SADD($key_delete, $v['id']);
            }

        }
        return $this->renderText(json_encode(array('status'=>0, 'data'=>'', 'msg'=>'success')));
    }

    public function executeGetProductAttrIdsAAAAAAAAAA(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        $type = $request->getParameter('type');
        $set = $request->getParameter('set');
        if (empty($set)) exit('集合id必须存在');
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $key = !$type ? 'shihuo_haitao_activity_add_'.$set : 'shihuo_haitao_activity_delete_'.$set;
        $data = $redis->SINTER($key);
        $content = '';
        if ($data){
            foreach($data as $k=>$v){
                $content .= $v.',';
            }
        }
        echo $content;
        exit();
        var_dump($data);die;
    }

    public function executeGetProductAttrActivitySwitchAAAAAAAAAA(sfWebRequest $request)
    {
        header("Content-type: text/html; charset=utf-8");
        sfConfig::set('sf_web_debug', false);
        $type = $request->getParameter('type');
        $set = $request->getParameter('set');
        if (empty($set)) exit('集合id必须存在');
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $key = !$type ? 'shihuo_haitao_activity_add_'.$set : 'shihuo_haitao_activity_delete_'.$set;
        $data = $redis->SINTER($key);
        $content = '';
        if ($data){
            foreach($data as $k=>$v){
                $content .= $v.',';
            }
        }

        if ($content){
            $content = rtrim($content,',');
            $url = !$type ? 'http://www.shihuo.cn/api/joinActivitySet?set_id='.$set.'&operate=add' : 'http://www.shihuo.cn/api/joinActivitySet?set_id='.$set.'&operate=delete';
            $res =  tradeCommon::getContents($url,array('goods_id'=>$content),10,'post');
            $result = json_decode($res, true);
            if ($result['msg'] == 'success'){
                foreach($data as $v){
                    $redis->SREM($key,$v);
                }
            } else {
                exit('faild');
            }
        }
        exit('success');
    }


    public function executeGetItem(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        try
        {
            $item_id = $request->getParameter('item_id');
            if(empty($item_id))
            {
                throw new Exception('缺少参数',-1);
            }

            $curlData = tradeCommon::getContents('http://hws.m.taobao.com/cache/wdetail/5.0/?id='.$item_id,array(),10,'get');
            $data = json_decode($curlData,true);

            if($data['ret'][0] != 'SUCCESS::调用成功')
            {
                throw new Exception('获取失败',-2);
            }
            $data = array(
                'title' => $data['data']['itemInfoModel']['title'],
                'pic' => $data['data']['itemInfoModel']['picsPath'][0],
            );
            $return = array(
                'code'=> 0,
                'data'=>$data,
            );
        }
        catch(Exception $e)
        {
            $return = array(
                'code'=> $e->getCode(),
                'msg'=>$e->getMessage(),
            );
        }
        return $this->renderText(json_encode($return));
    }

    public function executeUpdateSkuGoodsId(sfWebRequest $request)
    {
        $productObj = TrdProductAttrTable::getInstance()->createQuery()
            ->select('*')->whereIn('business', TrdProductAttrTable::$zhifa_business_arr)
            ->andWhere('goods_id is null')->limit(20)->execute();
        if (count($productObj) > 0){
            foreach ($productObj as $k=>$v){
                $goodsObj = TrdHaitaoGoodsTable::getInstance()->createQuery()
                    ->select('*')->where('product_id = ?', $v->getId())->andWhere('status = 0')->fetchOne();
                if ($goodsObj){
                    $v->setGoodsId($goodsObj->getid());
                    $v->save();
                }
            }
        }
        exit();
    }
}
