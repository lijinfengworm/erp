<?php

/**
 * Class daigoucommentTradeService
 * version: 1.0
 */
class daigoucommentTradeService extends tradeService {

    /**
     * 发表评价
     */
    public function executeAdd()
    {
        $v = $this->getRequest()->getParameter('version');
        $orderNumber = $this->getRequest()->getParameter('order_number');
        $pid = $this->getRequest()->getParameter('pid');
        $gid = $this->getRequest()->getParameter('gid');
        $tagsArray = $this->getRequest()->getParameter('tags');
        $content = $this->getRequest()->getParameter('content');
        $imgs = $this->getRequest()->getParameter('imgs');
        $hupuUid =  $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');

        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }

        if (!$orderNumber || !$pid || !$gid || !$content || !$tagsArray) {
            return $this->error(400, '参数错误');
        }

        $content = strip_tags($content);
        $content = trim($content);
        $contentLen = FunBase::utf8_strlen($content);
        if ($contentLen < 10 || $contentLen > 300) {
            return $this->error(401, '评价数在10到300个字之间');
        }

        $tagsCount = count($tagsArray);
        if (!is_array($tagsArray) || $tagsCount < 3 || $tagsCount > 6) {
            return $this->error(402, '只能选三到六个标签');
        }
        $ordersInfo = TrdOrderTable::getInstance()->getOrderSuccessinfo($orderNumber, $pid, $gid, $hupuUid);
        if ($ordersInfo->getData()) {
            $orderInfo = $ordersInfo[0];
            if($hupuUid != $orderInfo->getHupuUid()){
                return $this->error(403, '非法评价');
            } else if (1 == $orderInfo->getIsComment()){
                return $this->error(404, '已经评论过了,请不要重复评价');
            }
        } else {
            return $this->error(405, '该订单无法评价');
        }

        $tags_attr_arr = array();
        $tagIds = array();
        foreach($tagsArray as $k => $v){
            $tags = TrdTagsTable::getInstance()->findOneBy('name', $v);
            $tagIds[] = $tags['id'];
            $tags_attr_arr[$tags['id']] = $v;
        }

        $daigouCommen = new trdDaigouComment();
        $daigouCommen->setUserId($hupuUid);
        $daigouCommen->setProductId($pid);
        $daigouCommen->setUserName($hupuUname);
        $daigouCommen->setContent($content);
        $imgs && $daigouCommen->setImgs(json_encode($imgs));
        $daigouCommen->setTagsAttr(json_encode($tags_attr_arr));
        $daigouCommen->setAttr($orderInfo['attr']);
        $daigouCommen->save();
        if ($daigouCommentId = $daigouCommen->getId()) {
            //同步代购商品表回复数和tag
            $productsAttr = trdProductAttrTable::getInstance()->findOneBy('id', $pid);

            $tagsAttr_arr = array();
            if($tagsAttr = $productsAttr->getTagsAttr()) {
                $tagsAttr_arr = json_decode($tagsAttr, true);
            }

            foreach($tagsArray as $k => $v){
                if (array_key_exists($v, $tagsAttr_arr)){
                    $tagsAttr_arr[$v] += 1;
                } else {
                    $tagsAttr_arr[$v] = 1;
                }
            }
            if(!empty($imgs)){
                $productsAttr->setCommentCountImg($productsAttr->getCommentCountImg() + 1);
            }
            $productsAttr->setCommentCount($productsAttr->getCommentCount() + 1);
            $productsAttr->setTagsAttr(json_encode($tagsAttr_arr));
            try {
                $productsAttr->save();
                foreach ($tagIds as $tagId) {
                    $tagDaigouCommentTags = new TrdDaigouCommentTags();
                    $tagDaigouCommentTags->setDaigouCommentId($daigouCommentId);
                    $tagDaigouCommentTags->setTagId($tagId);
                    $tagDaigouCommentTags->save();
                }

                //保存回复状态
                foreach ($ordersInfo as $itemOrder) {
                    $itemOrder->setIsComment(1);
                    $itemOrder->save();
                }
                return $this->success();
            } catch (Exception $e) {
                return $this->error(500, '系统错误,发表评论失败');
            }
        } else {
            return $this->error(500, '系统错误,发表评论失败');
        }
    }

    /**
     * 获得代购商品的标签
     * @param int pid 主商品id
     * @param int num 获得标签的数量
     */
    public function executeTagsGet()
    {
        $v = $this->getRequest()->getParameter('version');
        $pid = $this->getRequest()->getParameter('pid');
        $num = $this->getRequest()->getParameter('num', 8);

        if (!$pid) {
            return $this->error(400, '参数错误');
        }
        if (!is_numeric($num)) {
            return $this->error(400, '参数错误');
        }
        $product = TrdProductAttrTable::getInstance()->findOneBy('id', $pid);
        if (!$product) {
            return $this->error(400, '参数错误');
        }

        $rootId = $product['root_id'];
        $childrenId = $product['children_id'];
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $tags_key = 'trade_haitao_comment_tags_r' . $rootId . '_c' . $childrenId;
        $tags_names = unserialize($redis->get($tags_key));
        if (!$tags_names) {
            $daigou_tags = trdDaigouTagsTable::getTags($rootId, $childrenId);

            if (!$daigou_tags) {
                $defaultChildrenId = 48;
                $daigou_tags = trdDaigouTagsTable::getTags(7, $defaultChildrenId);
            }
            $tags_attrs = json_decode($daigou_tags['tags_attr'], true);
            if (count($tags_attrs) > $num) {
                $tagKeys = array_keys($tags_attrs);
                shuffle($tagKeys);
                $tagKeys = array_slice($tagKeys, 0, $num); //最多取$num个
                $randTags = array();
                foreach ($tagKeys as $key) {
                    $randTags[$key] = $tags_attrs[$key];
                }
                $tags_attrs = $randTags;
            }
            $tags_names = array();

            if ($tags_attrs) {
                foreach ($tags_attrs as $id => $name) {
                    $tags_names[] = array(
                        'id' => $id,
                        'name' => $name
                    );
                }
            }
            $redis->set($tags_key,serialize($tags_names), 1);
        }
        return $this->success(array('tags' => $tags_names));
    }

    /**
     * 获得代购商品评价中使用的标签
     * @param int pid 主商品id
     * @param int num 获得标签的数量
     */
    public function executeDetailTagsGet()
    {
        $v = $this->getRequest()->getParameter('version');
        $pid = $this->getRequest()->getParameter('pid');
        $num = $this->getRequest()->getParameter('num', 6);

        if (!$pid) {
            return $this->error(400, '参数错误');
        }
        if (!is_numeric($num)) {
            return $this->error(400, '参数错误');
        }
        $product = TrdProductAttrTable::getInstance()->findOneBy('id', $pid);
        if (!$product) {
            return $this->error(400, '参数错误');
        }

        $total_comments = TrdDaigouCommentTable::getInstance()->getCommentCount($pid, false);
        $tags = array();
        if ($tagsStr = $product->getTagsAttr()) {
            $tagsAttr = json_decode($tagsStr, true);
            arsort($tagsAttr);
            $tagsInfo = array_slice($tagsAttr, 0, $num);
            foreach ($tagsInfo as $key => $val) {
                $tags[] = array(
                    'name' => $key,
                    'count' => $val
                );
            }
        }
        return $this->success(array('total_comments' => $total_comments, 'tags' => $tags));
    }

    /**
     * 获取商品评价列表
     * @param int pid 主商品id
     */
    public function executeList()
    {
        $v = $this->getRequest()->getParameter('version');
        $pid = $this->getRequest()->getParameter('pid');
        $page = $this->getRequest()->getParameter('page', 1);
        $pageSize = $this->getRequest()->getParameter('pageSize', 20);
        $hasImg = (int) $this->getRequest()->getParameter('hasImg', 0);
        $tagName = $this->getRequest()->getParameter('tag');
        $imgWidth = $this->getRequest()->getParameter('imgWidth', 88);
        $imgHeight = $this->getRequest()->getParameter('imgHeight', 88);

        if (!$pid) {
            return $this->error(400, '参数错误');
        }
        $product = TrdProductAttrTable::getInstance()->findOneBy('id', $pid);
        if (!$product) {
            return $this->error(400, '参数错误');
        }
        if ($pageSize > 100) $pageSize = 100;
        if (!is_numeric($page) || (int) $page < 1) {
            return $this->error(400, '参数错误');
        }
        if (!is_numeric($pageSize) || (int) $pageSize < 1) {
            return $this->error(400, '参数错误');
        }
        $tagId = 0;
        if ($tagName) {
            $tag = TrdTagsTable::getInstance()->findOneBy('name', $tagName);
            if (!$tag) {
                return $this->error(400, '参数错误');
            }
            $tagId = $tag->getId();
        }

        $cacheTime = 1;
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(1);

        if ($hasImg) {
            $has_img_flag = 't';
        } else {
            $has_img_flag = 'f';
        }
        $commentKey = 'trade_hiatao_daigou_detail_comment_proid_' . $pid . '_tag' . $tagId . '_img' . $has_img_flag . '_p' . $page . '_ps' . $pageSize;
        $commentInfo = unserialize($redis->get($commentKey));
        if (!$commentInfo) {
            $commentInfo = array(
                'lists'     => array(),
                'pageNum'   => 0
            );
            $commentRes = trdDaigouCommentTable::getInstance()->getCommentList($pid, $hasImg, $tagId, $page, $pageSize);
            $commentPage = ceil(trdDaigouCommentTable::getInstance()->getCommentListCount($pid, $hasImg, $tagId) / $pageSize);
            if ($commentRes) {
                foreach ($commentRes as $comment_info_k => $comment_info_v) {
                    $attr = json_decode($comment_info_v['attr'], true);
                    if ($attr) {
                        unset($attr['name']);
                        unset($attr['img']);
                        unset($attr['price']);
                    }
                    $tagsAttr = json_decode($comment_info_v['tags_attr'], true);
                    $tagsCollection = array();
                    foreach ($tagsAttr as $tagsAttrKey => $tagsAttrVal) {
                        $tagsCollection[] = $tagsAttrVal;
                    }
                    $imgsArray = json_decode($comment_info_v['imgs'], true);
                    $imgsCollection = array();
                    if ($imgsArray) {
                        foreach ($imgsArray as $imgsVal) {
                            $imgsCollection[] = $imgsVal . '?imageView2/1/w/' . $imgWidth . '/h/' . $imgHeight;
                        }
                    }
                    $commentRes[$comment_info_k] = array(
                        'user_head' => tradeCommon::getQiNiuProxyPath('http://bbs.hupu.com/bbskcy/api_new_image.php?uid=' . $comment_info_v['user_id'] . '&type=big'),
                        'user_name' => mb_substr($comment_info_v['user_name'], 0, 3) . '***',
                        'attr'      => $attr,
                        'content'   => $comment_info_v['content'],
                        'create'    => $comment_info_v['created_at'],
                        'tags'      => $tagsCollection,
                        'imgs'      => $imgsCollection
                    );
                }
                $commentInfo['lists'] = $commentRes;
                $commentInfo['pageNum'] = $commentPage;
            }
            $redis->set($commentKey, serialize($commentInfo), $cacheTime);
        }
        return $this->success(array('comments' => $commentInfo));
    }
}