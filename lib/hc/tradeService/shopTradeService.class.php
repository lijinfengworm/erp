<?php
/**
 * 推荐店铺服务类
 * Created by PhpStorm.
 * User: gupenghui
 * Date: 2015/8/24
 * Time: 14:33
 */
class shopTradeService extends tradeService
{

    /**
     * 获取人气店铺
     * @return array
     */
    public function executeHotShop()
    {
        $v = $this->getRequest()->getParameter('version');

        $redis = sfContext::getInstance()->getDatabaseConnection('tradePersistenceRedis');
        $hotrank = unserialize($redis->get('hotrank'));
        return $this->success(array('shops' => $hotrank));
    }

    /**
     * 获取店铺信息
     * @param type 2是天猫 3是淘宝 不传是综合
     * @param title 标题或关键词
     * @return array
     */
    public function executeGetShops()
    {
        $v = $this->getRequest()->getParameter('version');
        $page = $this->getRequest()->getParameter('page', 1);
        $pagesize = $this->getRequest()->getParameter('pagesize', 24);
        $type= $this->getRequest()->getParameter('type');
        $shopTitle = $this->getRequest()->getParameter('title');

        if ($pagesize > 100) $pagesize = 100;
        if (!is_numeric($page) || (int) $page < 1) {
            return $this->error(400, '参数错误');
        }
        if (!is_numeric($pagesize) || (int) $pagesize < 1) {
            return $this->error(400, '参数错误');
        }
        if ($type && !in_array($type, array(2, 3))) {
            return $this->error(400, '参数错误');
        }

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $key = 'trade_shops_page_' . $page . '_pagesize_' . $pagesize . '_type_' . $type . '_title_' . $shopTitle;
        $redisData = $redis->get($key);
        if ($redisData) {
            $return = unserialize($redisData);
        } else {
            $totalNum = 0;
            $shopIds = $shops = array();
            if (!empty($shopTitle)) {
                # 关键词走es
                $es = new shopSearch();
                $search['keywords'] = trim($shopTitle);
                $search['pageNo'] = $page;
                $search['pageSize'] = $pagesize;
                if (!empty($type)) {
                    $search['type'] = $type;
                }
                $return = $es->search($search);

                if ($return['status'] === true) {
                    $keytmpData = array();
                    $totalNum = $return['num'];
                    foreach ($return['result'] as $k => $v) {
                        $keytmpData[$v['id']] = $v;
                        $shopIds[] = $v['id'];
                    }
                    if (!empty($shopIds)) {
                        $query = TrdShopInfoTable::getInstance()->createQuery()
                            ->select('id, name, owner_name, logo, link, business, location, level, good, hupu_uid, discount, collect_count')
                            ->whereIn('id',$shopIds);
                        $shops = $query->execute();
                    }
                }
            } else {
                $query = TrdShopInfoTable::getInstance()->createQuery()
                    ->select('count(1) as Num')
                    ->where('status = 0');
                if ($type) {
                    $query->addWhere('shop_category_id = ?', $type);
                }
                $res = $query->fetchOne();
                $totalNum = $res['Num'];

                $offset = ($page - 1) * $pagesize;
                $query = TrdShopInfoTable::getInstance()->createQuery()
                    ->select('id, name, owner_name, logo, link, business, location, level, good, hupu_uid, discount, collect_count')
                    ->where('status = 0')->orderBy('charge desc')->limit($pagesize)
                    ->offset($offset);
                if ($type) {
                    $query->addWhere('shop_category_id = ?', $type);
                }
                $shops = $query->execute();
            }
            $data = array();
            foreach ($shops as $shop) {
                $shopId = $shop->getId();

                $collectFlag = false;
                if ($hupuUid = $this->getUser()->getAttribute('uid')) {
                    if (TrdUserCollectionsTable::getColloectionByUid($hupuUid, $shopId, 'shop')) {
                        $collectFlag = true;
                    }
                }

                $userInfo = $shop->getUserInfo();
                if(preg_match('/^.+\.taobao\.com/', $shop->getLink()) || preg_match('/^.+\.tmall.com/', $shop->getLink())){
                    $isTmall = 1;
                } else {
                    $isTmall = 0;
                }

                # 高亮
                if (!empty($shopTitle) && !empty($keytmpData[$shopId]['name'])) {
                    $name = $keytmpData[$shopId]['name'];
                } else {
                    $name = $shop->getName();
                }
                if (!empty($shopTitle) && !empty($keytmpData[$shopId]['business'])) {
                    $business = $keytmpData[$shopId]['business'];
                } else {
                    $business = $shop->getBusiness();
                }

                $tt = array(
                    'id' => $shopId,
                    'name' => $name,
                    'owner_name' => $shop->getOwnerName(),
                    'logo' => $shop->getLogo() ? $shop->getLogo()  : '/images/trade/shop/logo_thumb.jpg',
                    'link' => $isTmall ? $shop->getLink() : 'http://go.shihuo.cn/u?url=' . $shop->getLink(),
                    'business' => $business,
                    'location' => $shop->getLocation(),
                    'level' => $userInfo['level'],
                    'good' => $shop->getGood(),
                    'discount' => $shop->getDiscount(),
                    'collect_count' => $shop->getCollectCount(),
                    'flag' => $shop->getVerifyStatus(),
                    'isTmall'=> $isTmall,
                    'collect_flag' => $collectFlag ? 1 : 0,
                    'charge' => $shop->getCharge(),
                );
                if (!empty($shopTitle)) {
                    $data[$shopId] = $tt;
                } else {
                    $data[] = $tt;
                }
            }
            # 搜关键字按照佣金排序处理
            if (!empty($shopTitle) && !empty($shopIds)) {
                foreach ($shopIds as $shop_id) {
                    if (!empty($data[$shop_id])) {
                        $tmp[] = $data[$shop_id];
                    }
                }
                $data = $tmp;
            }

            $return = array(
                'total' => $totalNum,
                'data' => $data
            );
            $redis->set($key, serialize($return), 60);
        }
        return $this->success($return);
    }
}