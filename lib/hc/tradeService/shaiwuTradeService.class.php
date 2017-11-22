<?php

class shaiwuTradeService extends tradeService
{
    # 分页数据
    public function executeList()
    {
        try {
            $page = $this->request->getParameter('page', 1);
            $type = $this->request->getParameter('type', 0);
            $isHot = $this->request->getParameter('isHot', 0);
            $pageSize = $this->request->getParameter('pageSize', 30);
            $lastTime = $this->request->getParameter('lastTime', 0);
            $keywords = $this->request->getParameter('keywords', null);
            $date = $this->request->getParameter('date');//date用来m站数据拉取
            reset:
            $tmp = trdShaiwuProductTable::getInstance()->getList($page, $type, $isHot, $pageSize, $lastTime, $date,$keywords);
            if(!empty($keywords))
            {
                if(empty($tmp))
                {

                    $keywords = '';
                    goto reset;
                }
                $data = $tmp ;
            }
            else
            {
                $data['list'] = $tmp;
            }


            if (empty($data['list'])) {
                throw new Exception('数据为空', 502);
            }
            $data['pageSize'] = $pageSize;

            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    # 获取用户的晒物积分和金币
    public function executeCredit()
    {
        try {
            $uid = (int)$this->request->getParameter('uid');
            if (empty($uid)) {
                throw new Exception('缺少参数', 402);
            }
            $data = TrdAccountTable::getShaiwuByUid($uid);

            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    # 点赞和反对
    public function executeSupportAgaist()
    {
        try {
            $uid = $this->user->getAttribute('uid');
            if (empty($uid)) {
                throw new Exception('未登陆', 501);
            }

            $id = (int)$this->request->getParameter('id', null);
            $type = (int)$this->request->getParameter('type', null);

            if (empty($id) || empty($type) || !in_array($type, array(1, 2))) {
                throw new Exception('参数有误', 401);
            }

            $product = trdShaiwuProductTable::getInstance()->findOneById($id);//获取该条message
            if (empty($product)) {
                throw new Exception('物品不存在', 502);
            }

            $userRecommend = trdShaiwuUserRecommendTable::getInstance()->getRecommend($uid, $id);//判断是否已存在该操作记录

            if (!empty($userRecommend)) {
                $supportOrAgaist = $userRecommend->getRecommendType();//获取操作的类型 支持 or 反对
                # 操作相同
                if ($supportOrAgaist == $type) {
                    try {
                        $userRecommend->delete();

                        # 更新新闻支持反对数量
                        if ($type == 1) {
                            $num = $product->getSupport() - 1;
                            $product->setSupport(($num > 0) ? $num : 0);
                        } else {
                            $num = $product->getAgaist() - 1;
                            $product->setAgaist(($num > 0) ? $num : 0);
                        }
                        $product->save();//取消累计数
                    } catch (Exception $db) {
                        throw new Exception('数据库错误', 500);
                    }
                } #  相反操作
                else {
                    try {
                        $userRecommend->setRecommendType($type);
                        $userRecommend->save();

                        if ($type == 1) {
                            $product->setSupport($product->getSupport() + 1);
                            $num = $product->getAgaist() - 1;
                            $product->setAgaist(($num > 0) ? $num : 0);
                        } else {
                            $product->setAgaist($product->getAgaist() + 1);
                            $num = $product->getSupport() - 1;
                            $product->setSupport(($num > 0) ? $num : 0);
                        }
                        $product->save();//取消累计数
                    } catch (Exception $db) {
                        throw new Exception('数据库错误', 500);
                    }
                }
            } # 未点击过
            else {

                try {
                    $userRecommend = new trdShaiwuUserRecommend();
                    $userRecommend->fromArray(array(
                        'user_id' => $uid,
                        'recommend_type' => $type,
                        'product_id' => $id,
                    ));
                    $userRecommend->save();//记录用户操作

                    if ($type == 1) {
                        $product->setSupport($product->getSupport() + 1);
                    } else {
                        $product->setAgaist($product->getAgaist() + 1);
                    }
                    $product->save();
                    # 被点赞踩的用户
                    $message['toUid'] = $product->author_id;
                    $message['uid'] = $uid;
                    $message['actionid'] = $product->id;
                    $message['action'] = "shaiwuAgainst";
                    $message['type'] = ($type == 1)?0:1;
                    $this->sendMqMessage($message,'shihuo_comment_queue3','shihuo_comment_jifen');
                } catch (Exception $e) {
                    throw new Exception('数据库错误', 500);
                }
            }

            $returnData = array(
                'type' => $type,
                'snum' => $product->getSupport(),
                'anum' => $product->getAgaist(),
            );

            # 成功，返回数据
            return $this->success($returnData);
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    private function sendMqMessage($message,$queueName,$exchange)
    {
        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);
        $channel = $connection->channel();
        $arguments = array(
            "x-dead-letter-exchange" => array("S", "amq.topic"),
            "x-message-ttl" => array("I", 2000),
            "x-dead-letter-routing-key" => array("S", $exchange)
        );
        $channel->queue_declare($queueName, false, true, false, false, false, $arguments);
        $msg = new AMQPMessage(json_encode($message));
        $channel->basic_publish($msg, '', $queueName);
    }

    # 调用晒物信息
    public function executeProduct()
    {
        try {
            $productId = (int)$this->request->getParameter('id');
            $type = (int)$this->request->getParameter('type');
            if (empty($productId)) {
                throw new Exception('缺少参数', 401);
            }

            # 只获取内容
            if (1 === $type) {
                $tmp = trdShaiwuProductContentTable::getInstance()->getContentById($productId);
                $data['shaiwu']['content'] = $tmp['content'];

                $data['shaiwu']['urls'] = !empty($tmp['urls'])?json_decode($tmp['urls'],true):'';
            } else {
                $data['shaiwu'] = trdShaiwuProductTable::getInstance()->getShaiwuById($productId);

                if ($data['shaiwu']['status'] != 1) {
                    throw new Exception('内容未通过', 506);
                }
            }

            if (empty($data)) {
                throw new Exception('数据不存在', 505);
            }

            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }

    # 调用个人晒物列表
    public function executeUserlistGet()
    {
        try {
            $uid = $this->user->getAttribute('uid');
            if (empty($uid)) {
                throw new Exception('未登陆', 501);
            }

            $page = (int)$this->request->getParameter('page', 1);
            $pageSize = (int)$this->request->getParameter('pageSize');
            if (empty($pageSize)) {
                $pageSize = 20;
            }

            $data['list'] = trdShaiwuProductTable::getInstance()->getUserList($page, $uid, $pageSize);

            if (empty($data['list'])) {
                throw new Exception('数据不存在', 505);
            }

            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }

    # 晒物浏览数统计
    public function executeHits()
    {
        try {
            $productId = $this->request->getParameter('id');
            if (empty($productId)) {
                throw new Exception('缺少参数', 402);
            }

            //msg点击数(浏览数)统计
            tradeWebPageHitsCount::getInstance()->tradeMsgHitsCount($productId, 'shaiwuDetail', 300);
            $data['hits'] = true;

            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /*
     *发布晒物 & 修改晒物
     *
     **/
    public function executeShaiwuSubmit()
    {
        $version = $this->request->getParameter('version');
        $id = $this->request->getParameter('id');
        $title = $this->request->getParameter('title');
        $content = $this->request->getParameter('content');
        $frontPic = $this->request->getParameter('frontPic');
        $source = $this->request->getParameter('source', 'pc');
        $activityId = (int)$this->request->getParameter('activity_id');
        $urls = $this->request->getParameter('urls',array());

        $userId = $this->getUser()->getAttribute('uid');
        $userName = $this->getUser()->getAttribute('username');

        #判断
        if (!$version) {
            return $this->error(405, '未设置版本号');
        }
        if ($id && !is_numeric($id)) {
            return $this->error(401, 'id值错误');
        }
        if (!$title) {
            return $this->error(401, '标题不能为空');
        }
        if (!$content) {
            return $this->error(401, '内容不能为空');
        }
        if (!$frontPic) {
            return $this->error(401, '封面不能为空');
        }
        if (!$userId || !$userName) {
            return $this->error(501, '未登录');
        }
        if(!empty($urls)){
            if(!is_array($urls)){
                return $this->error(401, '链接参数有误');
            }

            if(count($urls)>5){
                return $this->error(401, '链接最多5个哦');
            }

            foreach($urls as $k=>$url){
                if( false === $this->check_url($url) )
                {
                    $urls[$k] = 'http://'.$url; 
                }
            }
        }


        #过滤
        $xssHtml = new XssHtml(trim($content), 'utf-8', array('a', 'img', 'br', 'strong', 'b', 'code', 'pre', 'p', 'div', 'em', 'span',  'h2', 'h3', 'h4', 'h5', 'h6', 'li', 'ul'));
        $xssHtml->m_AllowAttr = array(
            'title',
            'src',
            'href',
            'id',
            'class',
            'width',
            'height',
            'alt',
            'target',
            'align'
        );

        if (stristr($frontPic, '?')) {
            $frontPic = strstr($frontPic, '?', true);
        }
        $content = trim($xssHtml->getHtml());
        $intro = FunBase::getsubstrutf8(trim(strip_tags($content)),0, 300);
        $publishTime = date('Y-m-d H:i:s');
        //echo $content;exit;
        #写入数据库
        try {
            if($id){
                $shaiwuPorduct = trdShaiwuProductTable::getInstance()->find($id);

                if(!$shaiwuPorduct){
                    throw new Exception('没有找到此ID,请稍后再试', 401);
                } else{
                    if($shaiwuPorduct->getStatus() == 1){
                        throw new Exception('审核已通过，请不要重复提交', 401);
                    }
                    if($shaiwuPorduct->getStatus() != 2){
                        throw new Exception('目前不能修改哦', 401);
                    }
                    if($userId != $shaiwuPorduct->author_id)
                    {
                        throw new Exception('没有修改权限', 401);
                    }
                }
            }else{
                $shaiwuPorduct = new trdShaiwuProduct();
            }
            if(!empty($activityId))
            {
                $activity = TrdShaiwuActivityTable::getInstance()->find($activityId);
                if(empty($activity))
                {
                    throw new Exception('活动不存在', 505);
                }
                $now = time();
                if( $now > $activity->etime || $now < $activity->stime )
                {
                    throw new Exception('活动已结束，请选择其他活动吧', 505);
                }
                if(  $now < $activity->stime )
                {
                    throw new Exception('活动未开始，请选择其他活动吧', 505);
                }
                $shaiwuPorduct->activity_id = $activityId;
                $title = '#'.$activity->title.'#'.$title;
            }
            else
            {
                $shaiwuPorduct->activity_id = 0;
            }
            $shaiwuPorduct->setTitle($title);
            $shaiwuPorduct->setFrontPic($frontPic);
            $shaiwuPorduct->setIntro($intro);
            $shaiwuPorduct->setStatus(0);
            $shaiwuPorduct->setAuthorId($userId);
            $shaiwuPorduct->setAuthorName($userName);
            $shaiwuPorduct->setPublishTime($publishTime);
            $shaiwuPorduct->setSource($source);

            $shaiwuPorduct->save();

            if ($shaiwuPorduct->getId()) {
                $shaiwuPorductContent = trdShaiwuProductContentTable::getInstance()->findOneBy('product_id',$shaiwuPorduct->getId());
                if(empty($shaiwuPorductContent))
                {
                    $shaiwuPorductContent = new trdShaiwuProductContent();
                }
                $shaiwuPorductContent->setProductId($shaiwuPorduct->getId());
                $shaiwuPorductContent->setContent($content);
                if(!empty($urls)){
                    $shaiwuPorductContent->setUrls(json_encode($urls));
                }
                $shaiwuPorductContent->save();

                $return = array('id' => $shaiwuPorduct->getId(), 'url' => 'http://www.shihuo.cn/ucenter/myShaiwu');

                return $this->success($return, 200, '提交晒物成功');
            } else {
                throw new Exception('服务器出错,请稍后再试', 500);
            }

        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }
    protected  function check_url($url){
        if(!preg_match('/http|https:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$url)){
            return false;
        }
        return true;
    }

    # 获取晒物活动
    public function executeGetActivityDetail()
    {
        try {
            $id = (int)$this->request->getParameter('id');
            if (empty($id)) {
                throw new Exception('缺少参数', 402);
            }
            $data = TrdShaiwuActivityTable::find($id);
            if(empty($data))
            {
                throw new Exception('数据为空', 403);
            }
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    # 获取晒物活动列表
    public function executeGetActivityList()
    {
        try {
            $activity_id = (int)$this->request->getParameter('id');
            $page = $this->request->getParameter('page', 1);
            $pageSize = $this->request->getParameter('pageSize', 10);
            $rank = $this->request->getParameter('rank', 0);
            if (empty($activity_id)) {
                throw new Exception('缺少参数', 402);
            }
            $data['list'] = trdShaiwuProductTable::getActivityList($activity_id,$page,$pageSize,$rank);
            if(empty($data['list']))
            {
                throw new Exception('数据为空', 403);
            }
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    # 获取正在进行的活动
    public function executeGetIngAcitivity()
    {
        try {
            $data['list'] = TrdShaiwuActivityTable::getIngActivity();
            if(empty($data['list']))
            {
                throw new Exception('数据为空', 403);
            }
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    # 获取本周晒物之星
    public function executeStar()
    {
        try {
            $data = TrdShaiwuStarTable::getStar();
            if(empty($data))
            {
                throw new Exception('数据为空', 403);
            }
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }
}