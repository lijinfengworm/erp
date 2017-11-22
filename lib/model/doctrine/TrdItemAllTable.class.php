<?php

/**
 * TrdItemAllTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdItemAllTable extends Doctrine_Table
{
    const HIDE = 1;
    const SHOW = 0;

    const STATUS_NORMAL = 0;
    const STATUS_BANNED = 1;
    const STATUS_BANNED_PERMANENT = 2;

    /**
     * Returns an instance of this class.
     *
     * @return object TrdItemAllTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdItemAll');
    }

    //按item_id查找
    public function get_by_itemid($item_id)
    {
        return self::getInstance()->createQuery()
            ->where('item_id = ?', $item_id)
            ->andwhere('is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('status = ?', TrdItemTable::STATUS_NORMAL)
            ->orderby("created_at desc")
            ->fetchOne();
    }

    //按item_all_id查找
    public function get_by_url($url)
    {
        return self::getInstance()->createQuery()
            ->where('url = ?', $url)
            ->andwhere('is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('status = ?', TrdItemTable::STATUS_NORMAL)
            ->orderby("created_at desc")
            ->fetchOne();
    }

    //按url查找
    public function get_by_item_all_id($item_id)
    {
        return self::getInstance()->createQuery()
            ->where('item_all_id = ?', $item_id)
            ->andwhere('is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('status = ?', TrdItemTable::STATUS_NORMAL)
            ->orderby("created_at desc")
            ->fetchOne();
    }

    //统计个数
    public function countAll($type)
    {
        $query = self::getInstance()->createQuery()
            ->from("TrdItemAll as t")
            ->andwhere('is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('is_soldout = 0')
            ->andWhere('status = ?', TrdItemTable::STATUS_NORMAL);
        if ($type != "all") {
            $query->leftJoin("t.Category c")
                ->andWhere("c.name = ?", $type);
        }
        return $query->count();
    }

    public static function getdisplayedItemall(Doctrine_Query $q = null)
    {
        if (is_null($q)) {
            $q = self::getInstance()->createQuery('TrdItemAll ia');
        }
        $q->addWhere('ia.status = 0');
        return $q->execute();
    }

    public function getItemsForRefresh($limit, $id, $large)
    {
        return self::getInstance()
            ->createQuery()
            ->select()
            ->where('id ' . ($large == 1 ? '>' : '<') . ' ?', $id)
            ->addwhere('status = 0')
            ->limit($limit)
            ->addOrderBy('id desc')->execute();
    }

    public function getItemsNoShoeForRefresh($limit, $id, $large)
    {
        return self::getInstance()
            ->createQuery()
            ->select()
            ->where('id ' . ($large == 1 ? '>' : '<') . ' ?', $id)
            ->addwhere('status = 0')
            ->addWhere('category_all_id != ?', 1)
            ->limit($limit)
            ->addOrderBy('id desc')->execute();
    }

    public function getById($id)
    {
        return self::getInstance()->createQuery()
            ->where('id = ?', $id)
            ->andwhere('is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('status = ?', TrdItemTable::STATUS_NORMAL)
            ->orderby("created_at desc")
            ->fetchOne();
    }

    public function toggleAllItemsByUser($userId, $visibility)
    {
        $items = TrdUserItemTable::getInstance()->getByUid($userId);
        $ids = array();
        $item_ids = array();
        foreach ($items as $item) {
            if ($item->getItemAllId()) {
                $ids[] = $item->getItemAllId();
            }
            if ($item->getItemId()) {
                $item_ids[] = $item->getItemId();
            }
        }

        $id_str = join(", ", $ids);
        if (!empty($item_ids)) $item_id_str = join(", ", $item_ids);


        //同步隐藏trd_items中的商品
//        if (!empty($item_ids)){
//            TrdItemTable::getInstance()->createQuery()
//                ->update()
//                ->set('is_hide', '?', $visibility)
//                ->where("id in ($item_id_str)")
//                ->execute();
//        }

        return self::getInstance()->createQuery()
            ->update()
            ->set('is_hide', '?', $visibility)
            ->where("id in ($id_str)")
            ->execute();
    }

    public function toggleAllItemsByShop($shopId, $visibility)
    {
        //同步隐藏trd_items_all中的商品
        TrdItemTable::getInstance()->createQuery()
            ->update()
            ->set('is_hide', '?', $visibility)
            ->where('shop_id = ?', $shopId)
            ->execute();

        return self::getInstance()->createQuery()
            ->update()
            ->set('is_hide', '?', $visibility)
            ->where('shop_id = ?', $shopId)
            ->execute();
    }

    public function showAllItemsByShop($shopId)
    {
        return $this->toggleAllItemsByShop($shopId, self::SHOW);
    }

    public function hideAllItemsByShop($shopId)
    {
        return $this->toggleAllItemsByShop($shopId, self::HIDE);
    }

    public function showAllItemsByUser($userId)
    {
        return $this->toggleAllItemsByUser($userId, self::SHOW);
    }

    public function hideAllItemsByUser($userId)
    {
        return $this->toggleAllItemsByUser($userId, self::HIDE);
    }

    public function banItem($itemId)
    {
        $this->allSyncToSphinx(1, $itemId);
        return Doctrine_Query::create()
            ->update('TrdItemAll t')
            ->set('t.status', self::STATUS_BANNED)
            ->where('t.id = ?', (int)$itemId)
            ->execute();
    }

    public function unbanItem($itemId)
    {
        $return = Doctrine_Query::create()
            ->update('TrdItemAll t')
            ->set('t.status', self::STATUS_NORMAL)
            ->where('t.id = ?', (int)$itemId)
            ->execute();

        $this->allSyncToSphinx(0, $itemId);
        return $return;
    }

    protected function allSyncToSphinx($status, $itemId)
    {
        if ($status) {
            hcRabbitMQPublisher::getInstance('shihuo_item')->publish(new hcAMQPMessage(array('id' => $itemId, 'type' => 1)));
            //hcRabbitMQPublisher::getInstance('shihuo_user')->publish(new hcAMQPMessage(array('type' => 'delete', 'item_id' => $itemId)), 'shihuo_user_product.delete');
        } else {
            hcRabbitMQPublisher::getInstance('shihuo_item')->publish(new hcAMQPMessage(array('id' => $itemId, 'type' => 0)));
            //hcRabbitMQPublisher::getInstance('shihuo_user')->publish(new hcAMQPMessage(array('type' => 'update', 'item_id' => $itemId)), 'shihuo_user_product.update');
        }
        return true;
    }


    public function getByShoeId($id)
    {
        return self::getInstance()->createQuery()
            ->where('shoe_id = ?', $id)
            ->andwhere('is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('status = ?', TrdItemTable::STATUS_NORMAL)
            ->orderby("created_at desc")
            ->fetchOne();
    }

    public function getItemsAll($from = 0, $limit = 1000)
    {
        return self::getInstance()
            ->createQuery()
            ->where('id >= ?', $from)
            ->limit($limit)
            ->execute();
    }

    function getByIds($ids, $sort = 'publish_date desc', $limit = null)
    {
        $query = self::getInstance()
            ->createQuery()
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->select('t.id, t.name, t.shoe_id, t.title, t.name, t.mart, t.baoliao_id, t.price, t.url, t.root_id, t.children_id, t.memo, t.shop_id, t.is_recommend, t.rank, t.freight_payer, t.height, t.width, t.hupu_username, t.publish_date, t.created_at, t.img_url, t.sold_count,t.like_count,t.tag_collect,t.heat,t.pic_collect')
            ->from('TrdItemAll t')
            ->where("id in (" . join(",", $ids) . ") ")
            ->andWhere('t.is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('t.status = ?', TrdItemTable::STATUS_NORMAL)
            ->andWhere('t.is_soldout = 0')
            ->orderBy($sort);

        if($limit){
            $query = $query->limit($limit);
        }
        return $query->execute();
    }

    function getByIdsObj($ids, $sort = 'publish_date desc')
    {
        return self::getInstance()
            ->createQuery()
            ->select('t.id, t.name, t.shoe_id, t.title, t.name, t.mart, t.baoliao_id, t.price, t.url, t.root_id, t.children_id, t.memo, t.shop_id, t.is_recommend, t.rank, t.freight_payer, t.height, t.width, t.hupu_username, t.publish_date, t.created_at, t.img_url, t.sold_count,t.like_count,t.tag_collect,t.heat,t.pic_collect')
            ->from('TrdItemAll t')
            ->Where("id in (" . join(",", $ids) . ") ")
            ->andwhere('t.is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('t.status = ?', TrdItemTable::STATUS_NORMAL)
            ->andWhere('t.is_soldout = 0')
            ->orderBy($sort)
            ->execute();
    }

    //TODO:: For SearchService服务化
    public function getObjByIds($ids)
    {
        return self::getInstance()
            ->createQuery()
            ->select('t.id, t.name, t.shoe_id, t.title, t.name, t.mart, t.baoliao_id, t.price, t.url, t.root_id, t.children_id, t.memo, t.shop_id, t.is_recommend, t.rank, t.freight_payer, t.height, t.width, t.hupu_username, t.publish_date, t.created_at, t.img_url, t.sold_count,t.like_count,t.tag_collect,t.heat,t.pic_collect')
            ->from('TrdItemAll t')
            ->Where("id in (" . join(",", $ids) . ") ")
            ->andwhere('t.is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('t.status = ?', TrdItemTable::STATUS_NORMAL)
            ->andWhere('t.is_soldout = 0')
            ->orderBy("FIELD(`ID`,".trim(join(",", $ids)) . ')')
            ->execute();
    }

    function getByBaoliaoIds($ids, $sort = 'publish_date desc')
    {
        return self::getInstance()
            ->createQuery()
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->select('t.id, t.name, t.shoe_id, t.title, t.name, t.mart, t.baoliao_id, t.price, t.url, t.memo, t.shop_id, t.is_recommend, t.rank, t.freight_payer, t.height, t.width, t.hupu_username, t.publish_date, t.created_at, t.img_url, t.sold_count,t.like_count,t.click_count')
            ->from('TrdItemAll t')
            ->where('t.is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('t.status = ?', TrdItemTable::STATUS_NORMAL)
            ->andWhere("baoliao_id in (" . join(",", $ids) . ") ")
            ->orderBy($sort)
            ->execute();
    }

    function getByAllBaoliaoIds($ids, $sort = 'publish_date desc')
    {
        return self::getInstance()
            ->createQuery()
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->select('t.id, t.name, t.shoe_id, t.title, t.name, t.mart, t.baoliao_id, t.price, t.url, t.memo, t.shop_id, t.is_recommend, t.rank, t.freight_payer, t.height, t.width, t.hupu_username, t.publish_date, t.created_at, t.img_url, t.sold_count,t.like_count,t.click_count')
            ->from('TrdItemAll t')
            ->andWhere("baoliao_id in (" . join(",", $ids) . ") ")
            ->orderBy($sort)
            ->execute();
    }

    public static function getHiddenByFinds($page = 1, $page_size = 10, $rootid, $childrenid)
    {
        $query = self::getInstance()->createQuery('t')
            ->where('t.status = 0')
            ->andWhere('t.is_hide = ?', 0)
            ->offset(($page - 1) * $page_size)
            ->limit($page_size)
            ->orderBy('t.id desc');
        if ($rootid) $query = $query->andWhere('t.root_id = ?', $rootid);
        if ($childrenid) $query = $query->andWhere('t.children_id = ?', $childrenid);
        return $query->execute();
    }

    public function getVerifiedGoods($limit = 21)
    {
        return self::getInstance()
            ->createQuery()
            ->select('t.id, t.name, t.shoe_id, t.title, t.name, t.mart, t.baoliao_id, t.price, t.url, t.memo, t.shop_id, t.is_recommend, t.rank, t.freight_payer, t.height, t.width, t.hupu_username, t.publish_date, t.created_at, t.img_url, t.sold_count,t.like_count')
            ->from('TrdItemAll t')
            ->where('t.is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('t.status = ?', TrdItemTable::STATUS_NORMAL)
            ->limit($limit)
            ->orderBy('t.publish_date desc')
            ->execute();
    }


    public function getitemsByIds($ids)
    {
        $objs = self::getInstance()
            ->createQuery('t')
            ->select('t.id, t.title, t.price ,t.img_url,t.heat,t.publish_date,t.memo')
            ->where('t.is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('t.status = ?', TrdItemTable::STATUS_NORMAL)
            ->andWhere('t.is_soldout = 0')
            ->andWhere('t.id in (' . $ids . ')')
            ->execute();
        $items = array();
        foreach ($objs as $re) {
            $items[$re->getId()] = array(
                'id' => $re->getId(),
                'title' => $re->getTitle(),
                'price' => $re->getPrice(),
                'heat' => $re->getHeat(),
                'img_url' => $re->getImg_url(),
                'publish_date' => $re->getPublishDate(),
                'memo' => $re->getMemo()
            );
        }
        return $items;
    }

    public function getYesterdayHottestByField($field)
    {
        $connection = Doctrine_Manager::getInstance()->getConnection('trade');
        $time = time() - 86400;
        switch ($field) {
            case 'brand':
                $theField = "SUBSTRING_INDEX(attr_collect,',',1)AS theField";
                break;
            case 'type':
                $theField = "SUBSTRING_INDEX(SUBSTRING_INDEX(attr_collect,',',2),',' ,- 1)AS theField";
                break;
            default:
                return 'unkown argument';
        }
        $query = "SELECT
	" . $theField . ",
	count(*)AS ct
FROM
	trd_items_all
WHERE
	publish_date >= '" . $time . "'
	AND attr_collect != ''
AND is_hide = " . TrdItemTable::SHOW . "
AND status = " . TrdItemTable::STATUS_NORMAL . "
AND is_soldout = 0
GROUP BY theField
ORDER BY
	ct DESC
LIMIT 1";
        $statement = $connection->execute($query);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getItemsByField($attr,$ids = array())
    {
        $query = self::getInstance()
            ->createQuery('t')
            ->select('t.id, t.title, t.price, t.tag_collect ,t.img_url,t.heat,t.publish_date')
            ->where('t.is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('t.status = ?', TrdItemTable::STATUS_NORMAL)
            ->andWhere('t.is_soldout = 0')
            ->andWhere("FIND_IN_SET('" . $attr . "',attr_collect)")
            ->andWhere('t.publish_date >= ?', time() - 317 * 86400)//七天内
            ->andWhere("LENGTH(t.tag_collect) > 0");
            //->andWhere("LENGTH(TRIM(BOTH ',' FROM tag_collect)) - LENGTH(REPLACE(TRIM(BOTH ',' FROM tag_collect),',','')) + 1 >= 3");//标签数量大于等于3
        if ($ids){
            $query->whereNotIn('t.id',$ids);
        }
        $objs = $query->orderBy('t.publish_date DESC')
            ->limit(4)
            ->execute();
        $items = array();
        foreach ($objs as $re) {
            $items[] = array(
                'id' => $re->getId(),
                'title' => $re->getTitle(),
                'price' => $re->getPrice(),
                'tag_collect' => $re->getTagCollect(),
                'heat' => $re->getHeat(),
                'img_url' => $re->getImg_url(),
                'publish_date' => $re->getPublishDate()
            );
        }
        return $items;
    }

    //获取随机前7天的商品
    public function getProductByRand($root_id = 1, $children_id = 8, $limit = 50, $day = 7)
    {
        $query = self::getInstance()
            ->createQuery()
            ->select('t.id, t.name, t.title, t.price, t.url, t.img_url, t.heat, t.tag_collect, t.height, t.width')
            ->from('TrdItemAll t')
            ->where('t.is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('t.status = ?', TrdItemTable::STATUS_NORMAL);
        if ($root_id) {
            $query->andWhere('t.root_id =?', $root_id);
        }
        if ($children_id) {
            $query->andWhere('t.children_id =?', $children_id);
        }
        $time = strtotime('-' . $day . ' days');
        $query->andWhere('publish_date > ?', $time);
        $return = $query->orderBy('rand()')
            ->limit($limit)
            ->execute();
        return $return;
    }

    public function getYsetdayTopShoeItemsIds()
    {
        $stime  = date("Y-m-d")." 00:00:00";
        $tmp  =  strtotime($stime)-3600*24;
        $etime  =  date("Y-m-d H:i:s",$tmp);

        $sql = 'SELECT COUNT( * ) AS total, ia.id AS item_id
                FROM  `trd_find_click_info` AS fci, trd_items_all AS ia
                WHERE fci.item_id = ia.id
                AND fci.clicktime >  "'.$etime.'"
                AND fci.clicktime <  "'.$stime.'"
                AND children_id = 8
                AND ia.is_hide = 0
                AND ia.status = 0
                GROUP BY ia.item_id
                ORDER BY total DESC
                LIMIT 0 , 4';

        $connection = Doctrine_Manager::getInstance()->getConnection('trade');
        $res  = $connection->execute($sql);
        $info =  $res->fetchAll(Doctrine_Core::FETCH_ASSOC);

        $ids = array();
        foreach($info AS $key => $val)
        {
            $ids[] = $val['item_id'];
        }
        return $ids;
    }

    public function getNewShoeItems($limit)
    {

        $objs = self::getInstance()
            ->createQuery('t')
            ->select('*')
            ->where('t.is_hide = 0')
            ->andWhere('t.status = 0')
            ->andWhere('t.children_id = 8')
            ->orderBy('created_at desc')
            ->limit($limit)
            ->execute();

        $items = array();
        foreach ($objs as $re) {

            $tag_collect = $re->getTag_collect();
            $tags = array();
            if($tag_collect)
            {
                $tags = explode(",",$tag_collect,3);
            }

            $items[$re->getId()] = array(
                'id' => $re->getId(),
                'title' => mb_substr($re->getTitle(),0,19,'UTF-8'),
                'price' => $re->getPrice(),
                'img_url' => $re->getImg_url(),
                'tags' => $tags,
                'url' =>  $re->getLink()
            );
        }

        return $items;
    }

    public function getYsetdayTopFindItemsIds()
    {
        $stime  = date("Y-m-d")." 00:00:00";
        $tmp  =  strtotime($stime)-3600*24;
        $etime  =  date("Y-m-d H:i:s",$tmp);

        $sql = 'SELECT COUNT( * ) AS total, ia.id AS item_id
                FROM  `trd_find_click_info` AS fci, trd_items_all AS ia
                WHERE fci.item_id = ia.id
                AND fci.clicktime >  "'.$etime.'"
                AND fci.clicktime <  "'.$stime.'"
                AND children_id <> 8
                AND ia.is_hide = 0
                AND ia.status = 0
                GROUP BY ia.item_id
                ORDER BY total DESC
                LIMIT 0 , 4';

        $connection = Doctrine_Manager::getInstance()->getConnection('trade');
        $res  = $connection->execute($sql);
        $info =  $res->fetchAll(Doctrine_Core::FETCH_ASSOC);

        $ids = array();
        foreach($info AS $key => $val)
        {
            $ids[] = $val['item_id'];
        }
        return $ids;
    }

    public function getNewFindItems($limit)
    {
        $objs = self::getInstance()
            ->createQuery('t')
            ->select('*')
            ->where('t.is_hide = 0')
            ->andWhere('t.status = 0')
            ->andWhere('t.children_id <> 8')
            ->orderBy('created_at desc')
            ->limit($limit)
            ->execute();

        $items = array();
        foreach ($objs as $re) {

            $tag_collect = $re->getTag_collect();
            $tags = array();
            if($tag_collect)
            {
                $tags = explode(",",$tag_collect,3);
            }

            $items[$re->getId()] = array(
                'id' => $re->getId(),
                'title' => mb_substr($re->getTitle(),0,19,'UTF-8'),
                'price' => $re->getPrice(),
                'img_url' => $re->getImg_url(),
                'tags' => $tags,
                'url' =>  $re->getLink()
            );
        }

        return $items;
    }

    public function getIndexItemsByIds($ids)
    {
        $objs = self::getInstance()
            ->createQuery('t')
            ->select('*')
            ->where('t.is_hide = 0')
            ->andWhere('t.status = 0')
            ->andWhere('t.id in (' . $ids . ')')
            ->execute();
        $items = array();
        foreach ($objs as $re) {

            $tag_collect = $re->getTag_collect();
            $tags = array();
            if($tag_collect)
            {
                $tags = explode(",",$tag_collect,3);
            }

            $items[$re->getId()] = array(
                'id' => $re->getId(),
                'title' => mb_substr($re->getTitle(),0,19,'UTF-8'),
                'price' => $re->getPrice(),
                'img_url' => $re->getImg_url(),
                'tags' => $tags,
                'url' =>  $re->getLink()
            );
        }

        return $items;
    }

    public static function getItemByRank($limit=4)
    {
        return self::getInstance()
            ->createQuery('t')
            ->select('*')
            ->where('t.is_hide = 0')
            ->andWhere('t.status = 0')
            ->orderBy('rank desc')
            ->limit($limit)
            ->execute();
    }

    public static function getShoeByRank($limit=4)
    {
        return self::getInstance()
            ->createQuery('t')
            ->select('*')
            ->where('t.is_hide = 0')
            ->andWhere('t.status = 0')
            ->andWhere('t.children_id = 8')
            ->orderBy('rank desc')
            ->limit($limit)
            ->execute();
    }

    public static function getHotQuery($day,$limit) {
        $query = self::getInstance()
            ->createQuery('t')
            ->select('t.id, t.name, t.title, t.price,  t.publish_date, t.created_at, t.img_url, t.sold_count,t.like_count')
            ->where('t.is_hide = ?', 0)
            ->andWhere('t.publish_date >= ?',  strtotime("-".$day." day"))
            ->andWhere('t.is_soldout = 0')
            ->andWhere('t.status = ?', 0)
            ->orderBy('t.click_count desc')
            ->limit($limit);
        return $query->execute();
    }

    public function getItemListByIds($ids)
    {
        if (empty($ids)) {
            return array();
        }
        return $this->createQuery()
            ->where('id IN (' . join(',', $ids) . ')')
            ->addWhere('status = 0')
            ->orderBy('publish_date DESC')
            ->execute();
    }

    /**
     * @param int $page
     * @param int $page_size
     * @param $root_id
     * @param $children_id
     * @param string $order
     * @return mixed
     */
    public function getItemAlls($page = 1, $page_size = 30, $root_id, $children_id, $order = 'new', $date = '')
    {

        $query =  self::getInstance()->createQuery('t')
            ->andWhere('t.is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('t.status = ?', TrdItemTable::STATUS_NORMAL)
            ->andWhere('t.is_soldout = 0')
            ->limit($page_size);
        if(empty($root_id) && empty($children_id))
        {
            $query = $query->andWhere('t.root_id != 1')
                ->andWhere('t.children_id != 8');
        }
        if ($date) {
            $query = $query->andWhere('t.publish_date < ?', $date);
        } else {
            $offset = ($page - 1) * $page_size;
            $query = $query->offset($offset);
        }
        if ($order == 'hot') {
            $query = $query->orderBy('t.rank desc');
        } else {
            $query = $query->orderBy('t.publish_date desc');
        }

        if ($root_id) $query = $query->andWhere('t.root_id = ?',$root_id);
        if ($children_id) $query = $query->andWhere('t.children_id = ?',$children_id);
        return  $query->execute();
    }
}
