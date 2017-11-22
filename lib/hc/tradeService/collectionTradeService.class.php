<?php

/**
 * Class collectionTradeService
 * version: 1.0
 */
class collectionTradeService extends tradeService {

    /**
     * 获取我的收藏的信息
     * @param type  商品：goods 店铺：shop
     */
    public function executeUserList()
    {
        $v = $this->getRequest()->getParameter('version');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');
        $type = $this->getRequest()->getParameter('type', 'goods');
        $page = $this->getRequest()->getParameter('page', 1);
        $pageSize = $this->getRequest()->getParameter('pageSize', 10);

        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }
        if (!in_array($type, array('goods', 'shop'))) {
            return $this->error(400, '参数错误');
        }
        if ($pageSize > 100) $pageSize = 100;
        if (!is_numeric($page) || (int) $page < 1) {
            return $this->error(400, '参数错误');
        }
        if (!is_numeric($pageSize) || (int) $pageSize < 1) {
            return $this->error(400, '参数错误');
        }

        $data = $this->getMyColloectionDetail($hupuUid, $type, $page, $pageSize);
        return $this->success(array('collection' => $data));
    }

    public function executeUserDelete()
    {
        $v = $this->getRequest()->getParameter('version');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');
        $collectionId = $this->getRequest()->getParameter('collection_id');

        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }
        if (!$collectionId) {
            return $this->error(400, '参数错误');
        }
        $collection = TrdUserCollectionsTable::getColloectionByUidId($hupuUid, $collectionId);
        if (empty($collection)) {
            return $this->error(400, '参数错误');
        }
        try {
            $collection->delete();
            return $this->success();
        } catch (Exception $e) {
            return $this->error(500, '系统错误');
        }
    }

    private function getMyColloectionDetail($hupuUid, $colloectionType = 'all', $page = 1, $pagesize = 10)
    {
        $goods = array();
        $shops = array();

        if ($colloectionType == 'goods' || $colloectionType == 'all') {
            $types = array('youhui', 'haitao', 'shoe', 'find', 'groupon', 'product', 'daigou');
            $myCollections = TrdUserCollectionsTable::getMyColloection($hupuUid, $types, $page, $pagesize);
            foreach ($myCollections as $key => $collection) {
                $type = $collection->getType();
                $collection_id = $collection->getCollectionId();

                if ($type == 'product' || $type == 'daigou') {
                    $ids = array();
                    $ids[] = $collection_id;
                    $objgood = TrdProductAttrTable::getProductByIds($ids);
                    $objgood = $objgood[0];
                    if ($objgood) {
                        $good['cid'] = $collection->getId();
                        $good['pid'] = $objgood->getId();
                        $good['gid'] = $objgood->getGoodsId();
                        $good['image'] = $objgood->getImgPath() . '?imageView2/1/w/160/h/160';
                        $good['title'] = $objgood->getTitle();
                        $good['price'] = $objgood->getPrice();
                        $good['type'] = $type;
                        $good['status'] = ((0 == $objgood->getStatus()) && (0 == $objgood->getPurchaseFlag())) ? 0 : 1;
                        $goods[] = $good;
                    }
                }
                if ($type == 'youhui' || $type == 'haitao') {
                    $objgood = TrdNewsTable::getNewByIdFromApp($collection_id);
                    if ($objgood) {
                        $good['cid'] = $collection->getId();
                        $good['pid'] = $objgood->getId();
                        $good['image'] = $objgood->getImgPath();
                        $good['title'] = $objgood->getTitle();
                        $good['price'] = $objgood->getPrice();
                        $good['type'] = $type;
                        $good['status'] = ($objgood->getIsDelete() || $objgood->getGoodsState()) ? 1 : 0;
                        $goods[] = $good;
                    }
                }
                if ($type == 'shoe' || $type == 'find') {
                    $objgood = TrdItemAllTable::getInstance()->find($collection_id);

                    if ($objgood) {
                        $good['cid'] = $collection->getId();
                        $good['pid'] = $objgood->getId();
                        $good['image'] = trdItemAll::getImgPath( $objgood->getImgUrl(), '160', '160');
                        $good['title'] = $objgood->getTitle();
                        $good['price'] = $objgood->getPrice();
                        $good['type'] = $type;
                        $good['status'] = ($objgood->getStatus() || $objgood->getIsHide()) ? 1 : 0;
                        $goods[] = $good;
                    }
                }

                if ($type == 'groupon') {
                    $objgood = TrdGrouponTable::getInstance()->find($collection_id);
                    $now = date('Y-m-d H:i:s');
                    $status = 0;
                    if (($objgood->getStatus() != 6) || ($objgood->getStartTime() >= $now)
                        || ($objgood->getEndTime() <= $now) || ($objgood->getDeletedAt())) {
                        $status = 1;
                    }
                    if ($objgood) {
                        $attr = unserialize($objgood->getAttr());
                        $good['cid'] = $collection->getId();
                        $good['pid'] = $objgood->getId();
                        $good['image'] = $attr['images_frist'] . '?imageView2/1/w/160/h/160';
                        $good['title'] = $objgood->getTitle();
                        $good['price'] = $objgood->getPrice();
                        $good['type'] = $type;
                        $good['status'] = $status;
                        $goods[] = $good;
                    }
                }
            }
        }

        if($colloectionType == 'shop' || $colloectionType == 'all')
        {
            $types = array();
            $types[] = 'shop';
            $myCollections = TrdUserCollectionsTable::getMyColloection($hupuUid, $types, $page, $pagesize);
            foreach($myCollections AS $key => $collection)
            {
                $collection_id = $collection->getCollectionId();
                $objshop = TrdShopInfoTable::getShopById($collection_id);
                if ($objshop) {
                    $logo = 'http://www.shihuo.cn';
                    if ($objshop->getLogo()) {
                        $logo .= $objshop->getLogo();
                    } else {
                        $logo .= '/images/trade/shop/logo_thumb.jpg';
                    }
                    $shop['cid'] = $collection->getId();
                    $shop['name'] = $objshop->getName();
                    $shop['location'] = $objshop->getLocation();
                    $shop['business'] = $objshop->getBusiness();
                    $shop['owner_name'] = $objshop->getOwnerName();
                    $shop['logo'] = $logo;
                    $shop['type'] = 'shop';
                    $shop['link'] = $objshop->getGoLink();
                    $shops[] = $shop;
                }
            }
        }
        $return = array();
        if ($goods) {
            $return['goods'] = $goods;
        }
        if ($shops) {
            $return['shop'] = $shops;
        }
        return $return;
    }

    private function scanImage($image, $width, $height, $mode = 2)
    {
        if($mode == 2){
            return $image.'?imageView2/'.$mode.'/w/'.intval($width);
        } else {
            return $image.'?imageView2/'.$mode.'/w/'.intval($width).'/h/'.intval($height);
        }
    }
}