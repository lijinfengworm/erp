<?php


class commentKaluliService extends kaluliService {

    /**
     * 发表评价
     */
    public function executeAdd()
    {
        try
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
                throw new Exception('未登录',501);
            }

            if (!$orderNumber || !$pid || !$gid || !$content) {
                throw new Exception('参数错误',400);
            }

            $content = strip_tags($content);
            $content = trim($content);
            $contentLen = FunBase::utf8_strlen($content);
            if ($contentLen < 2 || $contentLen > 300) {
                throw new Exception('评价数在2到300个字之间',401);
            }

            /*$tagsCount = count($tagsArray);
            if (!is_array($tagsArray) || $tagsCount < 1 || $tagsCount > 6) {
                throw new Exception('只能选一到六个标签',402);
            }*/

            $orderInfo = KaluliOrderTable::getOrderSuccessinfo($orderNumber, $pid, $gid, $hupuUid);
            if ($orderInfo) {
                if($hupuUid != $orderInfo->getHupuUid()){
                    throw new Exception('非法评价',403);
                } else if (1 == $orderInfo->getIsComment()){
                    throw new Exception('已经评论过了,请不要重复评价',404);
                }
            } else {
                throw new Exception('该订单无法评价',405);
            }
            $orderAttr = KaluliOrderAttrTable::getInstance()->createQuery()
                ->andWhere('order_number = ?',$orderNumber)
                ->andWhere('order_id = ?',$orderInfo->id)
                ->fetchOne();

            $tags_attr_arr = array();
            $tagIds = array();
            /*foreach($tagsArray as $k=>$v){
                $tags = KllCommentTagsDetailTable::getInstance()->findOneBy('name', $v);
                if(empty($tags)) continue;
                $tagIds[] = $tags['id'];
                $tags_attr_arr[$tags['id']] = $v;
            }*/
            # 获取商品信息
            $productsAttr = KaluliItemAttrTable::getInstance()->find($pid);
            if(empty($productsAttr))
            {
                throw new Exception('未找到商品信息',411);
            }

            $comment = new KllComment();
            $comment->setUserId($hupuUid);
            $comment->setProductId($pid);
            $comment->setUserName($hupuUname);
            $comment->setContent($content);
            $imgs && $comment->setImgs(json_encode($imgs));
            $comment->setTagsAttr(json_encode($tags_attr_arr));
            if($orderAttr->attr)$comment->setAttr($orderAttr->attr);
            $comment->save();
            if ($commentId = $comment->getId()) {
                //同步代购商品表回复数和tag
                $tagsAttr_arr = array();
                if($tagsAttr = $productsAttr->getCommentTagsCount()) {
                    $tagsAttr_arr = json_decode($tagsAttr, true);
                }

                /*foreach($tagsArray as $k => $v){
                    if (array_key_exists($v, $tagsAttr_arr)){
                        $tagsAttr_arr[$v] += 1;
                    } else {
                        $tagsAttr_arr[$v] = 1;
                    }
                }*/

                # 同步图片评论总数
                if(!empty($imgs)){
                    $productsAttr->setCommentImgsCount($productsAttr->getCommentImgsCount() + 1);
                }
                # 同步评论总数
                $productsAttr->setCommentCount($productsAttr->getCommentCount() + 1);
                # 冗余评论标签数量
                $productsAttr->setCommentTagsCount(json_encode($tagsAttr_arr));

                $productsAttr->save();

                foreach ($tagIds as $tagId) {
                    $tagDaigouCommentTags = new KllCommentTagsCount();
                    $tagDaigouCommentTags->setProductId($pid);
                    $tagDaigouCommentTags->setTagId($tagId);
                    $tagDaigouCommentTags->setCommentId($commentId);

                    $tagDaigouCommentTags->save();
                }
                //保存回复状态

                $orderInfo->setIsComment(1);
                $orderInfo->save();

                return $this->success();
            } else {
                throw new Exception('系统错误,发表评论失败',508);
            }
        }
        catch( Exception $e )
        {
            return $this->error($e->getCode(), $e->getMessage());
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

        if (!$pid) {
            return $this->error(400, '参数错误');
        }
        $product = KaluliItemTable::getInstance()->findOneBy('id', $pid);
        if (!$product) {
            return $this->error(400, '参数错误');
        }

        $relateTags = KaluliTagsRelateTable::getInstance()->createQuery()->andWhere('type = ?',1)->andWhere('pid =?',$product->id)->fetchArray();

        if(count($relateTags) == 2)
        {
            foreach($relateTags as $v)
            {
                $tagIds[] = $v['tag_id'];
            }
            $tags = KaluliTagsTable::getInstance()->createQuery()->whereIn('id',$tagIds)->fetchArray();
            if(!empty($tags))
            {
                foreach($tags as $v)
                {
                    if($v['type'] == 1)
                    {
                        $rootName = $v['name'];
                    }
                    elseif($v['type'] == 2)
                    {
                        $childName = $v['name'];
                    }
                }
            }

            if( !empty($rootName) && !empty($childName) )
            {
                $commentTags = KllCommentTagsTable::getInstance()->createQuery()->andWhere('root_name = ?',$rootName)->andWhere('child_name = ?',$childName)->fetchOne();
                if(!empty($commentTags) && $attrs = json_decode($commentTags->attrs,true))
                {
                    foreach($attrs as $k=>$v)
                    {
                        $tags_names[] = array(
                            'id' => $k,
                            'name' => $v
                        );
                    }
                }
            }
        }
        else
        {
            $tags_names = array();
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
        $uid = $this->getRequest()->getParameter('uid');
//        $imgWidth = $this->getRequest()->getParameter('imgWidth', 0);
//        $imgHeight = $this->getRequest()->getParameter('imgHeight', 0);

        if (!$pid) {
            return $this->error(401, '参数错误');
        }

        $product = KaluliItemTable::getInstance()->findOneBy('id', $pid);

        if (!$product) {
            return $this->error(402, '参数错误');
        }
        if ($pageSize > 100) $pageSize = 100;
        if (!is_numeric($page) || (int) $page < 1) {
            return $this->error(403, '参数错误');
        }
        if (!is_numeric($pageSize) || (int) $pageSize < 1) {
            return $this->error(404, '参数错误');
        }
        $tagId = 0;
        if ($tagName) {
            $tag = KllCommentTagsDetailTable::getInstance()->findOneBy('name', $tagName);
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
        $commentKey = 'kaluli_detail_comment_proid_' . $pid . '_tag' . $tagId . '_img' . $has_img_flag . '_p' . $page . '_ps' . $pageSize;
        $commentInfo = unserialize($redis->get($commentKey));
        if (!$commentInfo) {
            $commentInfo = array(
                'lists'     => array(),
                'pageNum'   => 0
            );

            $commentRes = KllCommentTable::getInstance()->getCommentList($pid, $hasImg, $tagId, $page, $pageSize,$uid);
            $commentPage = ceil(KllCommentTable::getInstance()->getCommentListCount($pid, $hasImg, $tagId,$uid) / $pageSize);
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
                    if(empty($imgsArray)) $imgsArray = array();
                    //                  $imgsCollection = array();
//                    if ($imgsArray) {
//                        foreach ($imgsArray as $imgsVal) {
//                            $imgsCollection[] = $imgsVal . '?imageView2/1/w/' . $imgWidth . '/h/' . $imgHeight;
//                        }
//                    }
                    //查看是否有回复

                    $reply = self::getReplyForComment($comment_info_v['id']);
                    $commentRes[$comment_info_k] = array(
                        'user_head' => tradeCommon::getQiNiuProxyPath('http://bbs.hupu.com/bbskcy/api_new_image.php?uid=' . $comment_info_v['user_id'] . '&type=big'),
                        'user_name' => mb_substr($comment_info_v['user_name'], 0, 3) . '***',
                        'attr'      => $attr,
                        'content'   => $comment_info_v['content'],
                        'created_at'    => date("Y-m-d", strtotime($comment_info_v['created_at'])),
                        'tags_attr'      => $tagsCollection,
                        'imgs'      => $imgsArray,
                        'reply'     => $reply
                    );

                }
                $commentInfo['lists'] = $commentRes;
                $commentInfo['pageNum'] = $commentPage;
            }
            $redis->set($commentKey, serialize($commentInfo), $cacheTime);
        }
        return $this->success(array('comments' => $commentInfo));
    }
    private static function getReplyForComment($cid){
        $reply = '';
        $res = KllCommentTable::getInstance()->findOneByCid($cid);
        if($res){
            $reply = $res->getContent();
        }
        return $reply;
    }

}