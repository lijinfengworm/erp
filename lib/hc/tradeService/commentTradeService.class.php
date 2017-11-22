<?php

/**
 * Class commentTradeService
 * version:1.0
 */
class commentTradeService extends tradeService{
    private $allow_num = 5;
    private $appid = 10002;
    private $appkey =  '9ccc3f21a7987267301b1e5427dff898';
    private $permits_error_code =  array(
        '1'=>'用户可信度太低,请绑定手机或邮箱',
        '-7000'=>'未知错误',
        '-7004'=>'Appid不存在',
        '-7006'=>'签名不正确',
    );
    private static $toUserId; //被回复用户

    /*
    *保存评论
    **/
    public function executeCommentSave()
    {
        $version = $this->getRequest()->getParameter('version','');
        $typeId = $this->getRequest()->getParameter('typeId','');
        $productId = $this->getRequest()->getParameter('productId','');
        $content = $this->getRequest()->getParameter('content','');
        $imgsAttr = $this->getRequest()->getParameter('imgsAttr','');
        $source = $this->getRequest()->getParameter('source','pc');
        $userId = $this->getUser()->getAttribute('uid','');
        $userName = $this->getUser()->getAttribute('username','');
        $userIp = ip2long(FunBase::get_client_ip());      //ip


        #检查数据格式
        $errData = $this->checkData(array($userId=>'login',$userName=>'login',$typeId=>'number',$productId=>'number',$content=>'require',$version=>'version'));
        if($errData) return $errData;

        #用户可信度验证
        if($source == 'pc'){
            $passData = $this->passport($userId);
            if($passData) return $passData;
        }

        #封禁检测
        $prohibitData = $this->prohibit($userId,$userIp);
        if($prohibitData)  return $prohibitData;

        #类型过滤
        $typeData = $this->typeFilter($typeId);
        if($typeData) return $typeData;

        #时间限制
        $postStatus = $this->commentLimit($userId);
        if($postStatus) return $postStatus;

        #处理内容
        $content =  $this->splitContent($content);

        #保存回复
        $comment = new trdComment();
        $comment->setTypeId($typeId);
        $comment->setProductId($productId);
        $comment->setUserId($userId);
        $comment->setUserName($userName);
        $comment->setContent($content);
        $comment->setImgsAttr(json_encode($imgsAttr));
        $comment->setIp($userIp);
        $comment->save();

        #回写评论数(news表)
        $count = self::getCurrentAllNumByPid($typeId,$productId,true);
        if(1 == $typeId){
            $news = trdNewsTable::getInstance()->find($productId);
            if($news){
                $news->setReplyCount($count);
                $news->setLastReplyDate(date('Y-m-d H:i:s'));
                $news->save();
            }
            $toUserId = $news->getAuthorId();
            $replyContent = $news->getTitle();
        }elseif(2 == $typeId){//shaiwu
            $shaiwuProduct = trdShaiwuProductTable::getInstance()->find($comment->getProductId());

            if($shaiwuProduct){
                $shaiwuProduct->setCommentCount($count);
                $shaiwuProduct->save();
            }

            $toUserId = $shaiwuProduct->getAuthorId();
            $replyContent = $shaiwuProduct->getTitle();
        }elseif(3 == $typeId){//journal
            $journalData = TrdSpecialTable::getInstance()->find($comment->getProductId());
            if($journalData){
                $journalData->setCommentCount($count);
                $journalData->save();
            }
            $toUserId = 0;
        }elseif(5 == $typeId){//findDetail
            $find = TrdFindTable::getInstance()->find($comment->getProductId());
            if($find){
                $find->setReplyCount($count);
                $find->save();
            }
            $toUserId = 0;
        }

        #发送信息
        if(!empty($toUserId)) {
            $this->sendMessage($userId, $toUserId, $productId, $content, $replyContent, $typeId, $comment->getId());
        }

        # 评论加积分
        $message['uid'] = $userId;
        $message['actionid'] = $comment->id;
        $message['action'] = "comment";
        $this->sendMqMessage($message,'shihuo_comment_queue2','shihuo_comment_jifen');

        #返回数据
        $return =  array();
        $return['id'] = $comment->getId();
        $return['content'] = $content;
        $return['imgsAttr'] = $imgsAttr;
        $return['num'] = $this->getCurrentNumByPid($typeId,$productId,true);
        $return['praise'] = 0;
        $return['against'] = 0;
        $return['date'] = '刚刚';
        $return['user'] = array();
        $return['user']['userid'] = $userId;
        $return['user']['username'] = $userName;
        $return['user']['userhead'] = 'http://bbs.hupu.com/bbskcy/api_new_image.php?uid='.$userId;

        return $this->success($return,200,'发表评论成功');
    }

    /*
    *
    获取评论
    **/
    public function executeCommentGet()
    {
        $lightDefault = 5;

        $version = $this->getRequest()->getParameter('version','');
        $typeId = $this->getRequest()->getParameter('typeId','');
        $productId = $this->getRequest()->getParameter('productId','');
        $order = $this->getRequest()->getParameter('order','ASC');
        $page =  $this->getRequest()->getParameter('page',1);
        $pageSzie =  $this->getRequest()->getParameter('pageSize',20);
        $reply = $this->getRequest()->getParameter('reply',false);
        $replySzie = $this->getRequest()->getParameter('replySize',5);
        $replyOrder = $this->getRequest()->getParameter('replyOrder','ASC');
        $light = $this->getRequest()->getParameter('light',false);
        $lightSize = $this->getRequest()->getParameter('lightSize',5);
        $msgCommentId = $this->getRequest()->getParameter('msgCommentId',0);
        $msgReplyId = $this->getRequest()->getParameter('msgReplyId',0);
        $order = $this->getRequest()->getParameter('order','ASC');

        $userId = $this->getUser()->getAttribute('uid');
        $userName = $this->getUser()->getAttribute('username');

        #数据限制
        $pageSzie =  $pageSzie > 50 ? 50 : $pageSzie;
        $replySzie = $replySzie > 10 ? 10 : $replySzie;
        $lightSize = $lightSize > 10 ? 10 : $lightSize;

        switch($version) {
            case '1.0'://1.0版本
                //检查数据格式
                $errData = $this->checkData(array($typeId => 'number', $productId => 'number', $version => 'version'));
                if ($errData) return $errData;

                $return = array();

                //总条数
                $num = self::getCurrentAllNumByPid($typeId, $productId);
                $return['num'] = $num;

                //评论
                if ($num > 0)
                    $return['res'] = trdCommentTable::getMessage(array('select' => 'id,type_id,product_id,user_id,user_name,content,imgs_attr,praise,against,reply_count,created_at', 'typeId' => $typeId, 'productId' => $productId, 'limit' => $pageSzie, 'page' => $page, 'order' => 'created_at ' . $order, 'arr' => true));
                else
                    $return['res'] = array();


                //第几页
                if ($num > 0) {
                    $commentNum = self::getCurrentNumByPid($typeId, $productId);
                    $return['page'] = ceil($commentNum / $pageSzie);
                }else
                    $return['page'] = 0;


                //需要子回复
                if (($num > 0) && $reply) {
                    foreach ($return['res'] as $k => $v) {
                        $return['res'][$k]['imgs_attr'] = json_decode($return['res'][$k]['imgs_attr'], true);

                        if($return['res'][$k]['reply_count'] > 0)
                            $return['res'][$k]['reply'] = trdCommentClusterTable::getMessage(array('select' => 'id,user_id,user_name,content,imgs_attr,created_at', 'commentId' => $v['id'], 'productId' => $productId, 'limit' => $replySzie, 'order' => 'created_at ' . $replyOrder, 'arr' => true));
                        else
                            $return['res'][$k]['reply'] = array();

                    }
                }

                //需要高亮
                if (($num > 0) && $light) {
                    $return['light'] = trdCommentTable::getMessage(array('select' => 'id,type_id,product_id,user_id,user_name,content,imgs_attr,praise,against,reply_count,created_at', 'typeId' => $typeId, 'productId' => $productId, 'praise' => $lightDefault, 'limit' => $lightSize, 'order' => 'praise DESC, created_at DESC', 'arr' => true));
                    foreach ($return['light'] as $k => $v) {
                        $return['light'][$k]['imgs_attr'] = json_decode($return['light'][$k]['imgs_attr'], true);
                    }
                } else
                    $return['light'] = array();


                //用户信息
                $return['user'] = array();
                if ($userId) {
                    $return['user']['status'] = true;
                    $return['user']['userid'] = $userId;
                    $return['user']['username'] = $userName;
                    $return['user']['userhead'] = 'http://bbs.hupu.com/bbskcy/api_new_image.php?uid=' . $userId;
                } else
                    $return['user']['status'] = false;

                break;

            case '1.1'://1.1版本
                //检查数据格式
                $errData = $this->checkData(array($typeId => 'number', $productId => 'number', $version => 'version'));
                if ($errData) return $errData;

                $return = array();

                //总条数
                $num = self::getCurrentAllNumByPid($typeId, $productId);
                $return['num'] = $num;

                //如有$msgCommendId获取当前页数
                if ($msgCommentId) {
                    $msgCommentCount = trdCommentTable::getCountById($typeId, $productId, $msgCommentId);
                    $msgCommentPage = ceil($msgCommentCount / $pageSzie);
                    $page = $msgCommentPage;
                    $return['msgCommentPage'] = $msgCommentPage;
                    $return['msgCommentId'] = $msgCommentId;
                }

                //如有$msgCommendId获取当前页数
                if ($msgReplyId) {
                    $msgReplyCount = trdCommentClusterTable::getCountById($typeId, $msgCommentId, $msgReplyId);
                    $msgReplyPage = ceil($msgReplyCount / $pageSzie);
                    $return['msgReplyPage'] = $msgReplyPage;
                }


                //评论
                if ($num > 0) {
                    $return['res'] = trdCommentTable::getMessage(array(
                        'select' => 'id,type_id,product_id,user_id,user_name,content,imgs_attr,praise,against,reply_count,created_at',
                        'typeId' => $typeId,
                        'productId' => $productId,
                        'limit' => $pageSzie,
                        'page' => $page,
                        'order' => 'created_at ' . $order,
                        'arr' => true
                    ));
                } else {
                    $return['res'] = array();
                }

                //第几页
                if ($num > 0) {
                    $commentNum = self::getCurrentNumByPid($typeId, $productId);
                    $return['page'] = ceil($commentNum / $pageSzie);
                } else
                    $return['page'] = 0;


                //需要子回复
                if (($num > 0) && $reply) {
                    foreach ($return['res'] as $k => $v) {
                        $return['res'][$k]['imgs_attr'] = json_decode($return['res'][$k]['imgs_attr'], true);

                        if ($return['res'][$k]['reply_count'] > 0) {
                            $return['res'][$k]['reply'] = trdCommentClusterTable::getMessage(array(
                                'select' => 'id,user_id,user_name,content,imgs_attr,created_at',
                                'commentId' => $v['id'], 'productId' => $productId, 'limit' => $replySzie,
                                'order' => 'created_at ' . $replyOrder,
                                'arr' => true
                            ));
                        } else
                            $return['res'][$k]['reply'] = array();
                    }
                }

                //需要高亮
                if (($num > 0) && $light) {
                    $return['light'] = trdCommentTable::getMessage(array('select' => 'id,type_id,product_id,user_id,user_name,content,imgs_attr,praise,against,reply_count,created_at', 'typeId' => $typeId, 'productId' => $productId, 'praise' => $lightDefault, 'limit' => $lightSize, 'order' => 'praise DESC', 'arr' => true));
                    foreach ($return['light'] as $k => $v) {
                        $return['light'][$k]['imgs_attr'] = json_decode($return['light'][$k]['imgs_attr'], true);
                    }
                } else
                    $return['light'] = array();

                //用户信息
                $return['user'] = array();
                if ($userId) {
                    $return['user']['status'] = true;
                    $return['user']['userid'] = $userId;
                    $return['user']['username'] = $userName;
                    $return['user']['userhead'] = 'http://bbs.hupu.com/bbskcy/api_new_image.php?uid=' . $userId;
                } else
                    $return['user']['status'] = false;

            break;
        }

        return $this->success($return,200,'获取评论成功');
    }

    /*
     *保存回复
     **/
    public function executeReplySave()
    {
        $version = $this->getRequest()->getParameter('version','');
        $typeId = $this->getRequest()->getParameter('typeId','');
        $productId = $this->getRequest()->getParameter('productId','');
        $commentId =  $this->getRequest()->getParameter('commentId','');
        $replyId =  $this->getRequest()->getParameter('replyId',0);
        $content = $this->getRequest()->getParameter('content','');
        $source = $this->getRequest()->getParameter('source','pc');
        $userId = $this->getUser()->getAttribute('uid','');
        $userName = $this->getUser()->getAttribute('username','');
        $userIp = FunBase::get_client_ip();            //ip

        #数据格式检查
        $errData = $this->checkData(array($userId=>'login',$userName=>'login',$productId=>'number',$content=>'require',$version=>'version',$replyId=>'number'));
        if($errData) return $errData;

        #用户可信度验证
        if($source == 'pc'){
            $passData = $this->passport($userId);
            if($passData) return $passData;
        }

        #封禁检测
        $prohibitData = $this->prohibit($userId,ip2long($userIp));
        if($prohibitData)  return $prohibitData;

        #类型过滤
        $typeData = $this->typeFilter($typeId);
        if($typeData) return $typeData;

        #时间限制
        $postStatus = $this->replyLimit($userId);
        if($postStatus) return $postStatus;

        #处理内容
        $content =  $this->splitContent($content);


        #查询评论是否存在
        $comment = trdCommentTable::getInstance()->find($commentId);
        if(!$comment) return $this->error('401','评论不存在');

        #保存回复
        $commentCluster = new trdCommentCluster();
        $commentCluster->setCommentId($commentId);
        $commentCluster->setProductId($productId);
        $commentCluster->setReplyId($replyId);
        $commentCluster->setUserId($userId);
        $commentCluster->setUserName($userName);
        $commentCluster->setContent($content);
        $commentCluster->setIp(ip2long($userIp));
        $commentCluster->save();

        #评论表评论数加1
        $replyCount = self::getCurrentNumByCid($commentId,true);
        $comment->setReplyCount($replyCount);
        $comment->save();

        #回写评论数(news表)
        $count = self::getCurrentAllNumByPid($typeId,$productId,true);
        if(1 == $typeId){
            $news = trdNewsTable::getInstance()->find($productId);

            if($news){
                $news->setReplyCount($count);
                $news->setLastReplyDate(date('Y-m-d H:i:s'));
                $news->save();
            }
        }elseif(2 == $typeId){//shaiwu
            $shaiwuProduct = trdShaiwuProductTable::getInstance()->find($comment->getProductId());

            if($shaiwuProduct){
                $shaiwuProduct->setCommentCount($count);
                $shaiwuProduct->save();
            }
        }elseif(3 == $typeId){//shaiwu
            $journalData = TrdSpecialTable::getInstance()->find($comment->getProductId());
            if($journalData){
                $journalData->setCommentCount($count);
                $journalData->save();
            }
        }elseif(5 == $typeId){//findDetail
            $find = TrdFindTable::getInstance()->find($comment->getProductId());
            if($find){
                $find->setReplyCount($count);
                $find->save();
            }
        }

        #发送信息至用户
        if($replyId && self::$toUserId){
            $reply = trdCommentClusterTable::getInstance()->find($replyId);
            if($reply){
                $replyContent =  $reply->getContent();
            } else{
                $replyContent = $comment->getContent();
            }
        }else{
            $replyContent = $comment->getContent();
        }
        self::$toUserId || self::$toUserId = $comment->getUserId();
        $this->sendMessage($userId, self::$toUserId, $productId, $content, $replyContent, $typeId, $commentId, $commentCluster->getId());

        #返回数据
        $return =  array();
        $return['id'] = $commentCluster->getId();
        $return['content'] = $content;
        $return['date'] = '刚刚';
        $return['num'] = $comment->getReplyCount();
        $return['user'] = array();
        $return['user']['userid'] = $userId;
        $return['user']['username'] = $userName;
        $return['user']['userhead'] = 'http://bbs.hupu.com/bbskcy/api_new_image.php?uid='.$userId;

        return $this->success($return,200,'发表回复成功');
    }

    /*
     *获取回复
     **/
    public function executeReplyGet()
    {
        $version = $this->getRequest()->getParameter('version','');
        $commentId = $this->getRequest()->getParameter('commentId');
        $productId = $this->getRequest()->getParameter('productId');
        $order = $this->getRequest()->getParameter('order','ASC');

        $replyPage =  $this->getRequest()->getParameter('replyPage',1);
        $replySize =  $this->getRequest()->getParameter('replySize',5);

        #数据限制
        $replySize = $replySize > 50 ? 50 : $replySize;

        #检查数据格式
        $errData = $this->checkData(array($commentId=>'number',$productId=>'number',$version=>'version'));
        if($errData) return $errData;

        $res = array();
        $res['res'] = trdCommentClusterTable::getMessage(array('select'=>'id,product_id,comment_id,user_id,user_name,content,created_at','commentId'=>$commentId,'productId'=>$productId,'limit'=>$replySize,'page'=>$replyPage,'order'=>'created_at '.$order,'arr'=>true));
        $comment = trdCommentTable::getInstance()->find($commentId);
        if($comment){
            $res['num'] = $comment->getReplyCount();
        }else{
            $res['num'] = 0;
        }

        return $this->success($res,200,'获取回复成功');
    }

    /*
    * 评论点赞or反对
    */
    public function executePraiseorgainstSave(){
        $version = $this->getRequest()->getParameter('version','');
        $productId = $this->getRequest()->getParameter('productId','');
        $commentId = $this->getRequest()->getParameter('commentId','');
        $type = $this->getRequest()->getParameter('type');
        $userId = $this->getUser()->getAttribute('uid','');


        if($type == 'praise'){
            $status = 0;
        }elseif($type == 'against'){
            $status = 1;
        }else{
            $status = 0;
        }

        #数据格式验证
        $errData = $this->checkData(array($userId=>'login',$commentId=>'number',$productId=>'number',$type=>'string',$version=>'version'));
        if($errData) return $errData;

        #保存点赞数反对
        $commentPraise = trdCommentPraiseTable::getPraise($commentId,$userId);

        if($commentPraise)
            return $this->error('410','已执行过操作');
        else{
            $commentPraise = new trdCommentPraise();
            $commentPraise->setProductId($productId);
            $commentPraise->setCommentId($commentId);
            $commentPraise->setUserId($userId);
            $commentPraise->setStatus($status);
            $commentPraise->save();

            #改变回复的点赞返回数量
            $comment = trdCommentTable::getInstance()->find($commentId);
            if($comment){
                if($status == 0){
                    $comment->setPraise($comment->getPraise()+1);
                } else{
                    $comment->setAgainst($comment->getAgainst()+1);
                }
                $comment->save();
            }

            if($commentPraise->getId()){
                $num = ($status == 0) ? $comment->getPraise() : $comment->getAgainst();
                $message = array();
                # 被点赞踩的用户
                $message['uid'] = $userId;
                $message['toUid'] = $comment->user_id;
                $message['actionid'] = $commentId;
                $message['action'] = "commentAgainst";
                $message['type'] = $status;
                $this->sendMqMessage($message,'shihuo_comment_queue','shihuo_comment_jifen');
                return $this->success(array('num'=>$num),'200','保存成功');
            }else{
                return $this->error('500','服务器错误');
            }
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

    /*
     * 删除评论
    */
    public function executeCommentDelete(){
        $version = $this->getRequest()->getParameter('version','');
        $commentId = $this->getRequest()->getParameter('commentId','');

        #验证数据
        $errData = $this->checkData(array($commentId=>'number',$version=>'version'));
        if($errData) return $errData;

        #删除
        $comment = trdCommentTable::getInstance()->createQuery()->where('id=?',$commentId)->andWhere('status != 2')->fetchOne();
        if($comment){
            $comment->setStatus(2);
            $comment->save();

            if(1 == $comment->getTypeId()){     //news
                trdNewsTable::syncComment($comment->getProductId(), $comment->getReplyCount());
            }elseif(2 == $comment->getTypeId()){//shaiwu
                trdShaiwuProductTable::syncComment($comment->getProductId(), $comment->getReplyCount());
            }elseif(3 == $comment->getTypeId()){//journal
                TrdSpecialTable::syncComment($comment->getProductId(), $comment->getReplyCount());
            }elseif(5 == $comment->getTypeId()){//findDetail
                TrdFindTable::syncComment($comment->getProductId(), $comment->getReplyCount());
            }

            self::getCurrentAllNumByPid($comment->getTypeId(),$comment->getProductId(),true);

            # 删除评论扣积分
            $message['uid'] = $comment->user_id;
            $message['actionid'] = $comment->id;
            $message['action'] = "commentDel";
            $this->sendMqMessage($message,'shihuo_comment_queue4','shihuo_comment_jifen');
            return $this->success(array(),200,'删除成功');
        }else{
            return $this->error('408','未找到评论');
        }
    }

    /*
     *删除回复
     *
     **/
    public function executeReplyDelete(){
        $version = $this->getRequest()->getParameter('version','');
        $replyId = $this->getRequest()->getParameter('replyId','');

        $errData = $this->checkData(array($replyId=>'number',$version=>'version'));
        if($errData) return $errData;

        $commentCluster = trdCommentClusterTable::getInstance()->createQuery()->where('id=?',$replyId)->andWhere('status != 2')->fetchOne();
        if($commentCluster){
            //改回复状态
            $commentCluster->setStatus(2);
            $commentCluster->save();

            //减去评论回复数量
            $comment = trdCommentTable::getInstance()->createQuery()->where('id=?',$commentCluster->getCommentId())->andWhere('status != 2')->fetchOne();
            if($comment){
                $comment->setReplyCount($comment->getReplyCount() -  1);
                $comment->save();

                //减去产品总回复数量
                if(1 == $comment->getTypeId()){//news
                    $news = trdNewsTable::getInstance()->find($comment->getProductId());
                    if($news){
                        $news->setReplyCount($news->getReplyCount() - 1);
                        $news->save();
                    }
                }elseif(2 == $comment->getTypeId()){//shaiwu
                    $shaiwuProduct = trdShaiwuProductTable::getInstance()->find($comment->getProductId());
                    if($shaiwuProduct){
                        $shaiwuProduct->setCommentCount($shaiwuProduct->getCommentCount() - 1);
                        $shaiwuProduct->save();
                    }
                }elseif(3 == $comment->getTypeId()){//shaiwu
                    $journalOjb = TrdSpecialTable::getInstance()->find($comment->getProductId());
                    if($journalOjb) {
                        $journalOjb->setCommentCount($journalOjb->getCommentCount() - 1);
                        $journalOjb->save();
                    }
                }elseif(5 == $comment->getTypeId()){//finddetail
                    $find = TrdFindTable::getInstance()->find($comment->getProductId());
                    if($find) {
                        $find->setReplyCount($find->getReplyCount() - 1);
                        $find->save();
                    }
                }

                self::getCurrentAllNumByPid($comment->getTypeId(), $comment->getProductId(), true);
            }

            return $this->success(array(),200,'删除成功');
        }else{
            return $this->error('408','未找到回复');
        }
    }


    /*
    *
    * 清除用户所有信息
    */
    public  function executeCommentClear(){
        $version = $this->getRequest()->getParameter('version','');
        $userId = $this->getRequest()->getParameter('userId','');

        #验证数据
        $errData = $this->checkData(array($userId=>'number',$version=>'version'));
        if($errData) return $errData;

        try{
            #清除评论表
            $comment = trdCommentTable::getInstance()->createQuery()->where('user_id = ?',$userId)->execute();
            if($comment){
                foreach($comment as $comment_v){
                    $comment_v->setStatus(2);
                    $comment_v->save();
                }
            }

            #清除回复数
            $commentCluster = trdCommentClusterTable::getInstance()->createQuery()->where('user_id = ?',$userId)->execute();
            if($commentCluster){
                foreach($commentCluster as $commentCluster_v){
                    $commentCluster_v->setStatus(2);
                    $commentCluster_v->save();
                }
            }
        }catch(Exception $e){
            return $this->error($e->getCode(),$e->getMessage());
        }

        return $this->success(array(),200,'成功');
    }

    /*数据检查*/
    private function checkData($data){

        if(is_array($data)){
            foreach($data as $k=>$v){
                $k =  $k ? $k : 0;

                if($v == 'login'){
                    if(empty($k)) return $this->error(501,'未登录');
                }elseif($v == 'version'){
                    if(empty($k)) return $this->error(402,'版本号不能为空');
                }elseif($v == 'require'){
                    if(empty($k)) return $this->error(401,$k.'不能为空');
                }elseif($v == 'number'){
                    if(!is_numeric($k)) return $this->error(401,$k.'不符合数字类型');
                }elseif($v == 'string'){
                    if(!is_string($k)) return $this->error(401,$k.'不符合字符串类型');
                }
            }
        }
    }

    /*根据用户名获取用户ID*/
    private static function getUserInfoByUsername($userName){
        $args = array("username" => $userName,'a'=>'getInfoByUsername');
        $userInfo = SnsInterface::getContents("getuserbaseinfo", "84", "62c7c5ccd161d52", $args, 'POST');

        if(is_array($userInfo)) {
            $userInfo = array_values($userInfo);
            if(isset($userInfo[0]['uid']))
                return  $userInfo[0]['uid'];
            else
                return false;
        }else{
            return false;
        }
    }


    /*给用户发送信息*/
    private  function sendMessage($userId, $toUserId, $productId, $content, $reply_content, $typeId, $commentId, $replyId = null){
        if($toUserId != $userId){                    //不发给自己
            $messgae = array();
            $messgae['type'] = 1;                     //评论为1
            $messgae['uid'] = $toUserId;
            $messgae['sender_uid'] = $userId;
            $messgae['product_id'] = $productId;
            $messgae['content'] = $content;
            $messgae['reply_content'] = $reply_content;
            $messgae['time'] = time();
            if ($typeId == 1){
                $messgae['from'] = 1;
            }elseif($typeId == 2){
                $messgae['from'] = 2;
            }
            $messgae['attr'] = array(
                'msgCommentId' => $commentId,
                'msgReplyId' => $replyId,
            );

            //发送消息队列
            $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
            $connection = new AMQPConnection(
                $amqpParams['params']['host'],
                $amqpParams['params']['port'], $amqpParams['params']['user'],
                $amqpParams['params']['pass'], $amqpParams['params']['vhost']
            );
            $channel = $connection->channel();
            $arguments = array(
                "x-dead-letter-exchange" => array("S", "amq.topic"),
                "x-message-ttl" => array("I", 2000),
                "x-dead-letter-routing-key" => array("S", "shihuo.notice.use")
            );
            $channel->queue_declare('comment_notice_deferred', false, true, false, false, false, $arguments);

            $msg = new AMQPMessage(json_encode($messgae));
            $channel->basic_publish($msg, '', 'comment_notice_deferred');
        }
    }

    /*处理内容 分割出用户*/
    private function splitContent($content){
        $xss = new XssHtml(trim($content),' utf-8', array('a', 'img', 'br', 'strong', 'b', 'code', 'pre', 'p', 'div', 'em', 'span',  'h2', 'h3', 'h4', 'h5', 'h6', 'li', 'ul'));
        $xss->m_AllowAttr = array('title', 'src', 'href', 'id', 'class', 'width', 'height', 'alt', 'target', 'align');
        $content =  $xss->getHtml();

        if($content){
            $patternOne = '/回复\s{1}:\s{1}(\S+?)\s{1}/se';
            $patternTwo = '/(@\S+?)\s{1}/se';

            $content =  preg_replace($patternOne,"self::matchUsername('\\1',1)",$content);
            $content =  preg_replace($patternTwo,"self::matchUsername('\\1',2)",$content);
        }

        return trim($content);
    }


    /*根据产品ID获取包括回复条数*/
    private static function getCurrentAllNumByPid($typeId,$productId,$change = false){
        $key = 'trade.comment.getCurrentAllNumByPid.t'.$typeId.'p'.$productId;
        $cacheTime = 60*10;

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(5);
        $count = $redis->get($key);
        if((!$count && $count !== 0) || $change){
            $commentTable = trdCommentTable::getInstance()->createQuery()->select('count(id) count,sum(reply_count) sum')->where('type_id = ?',$typeId)->andWhere('product_id = ?',$productId)->andWhere('status = 1')->fetchArray();
            if($commentTable)
                $count = (int)$commentTable[0]['count'] + (int)$commentTable[0]['sum'];
            else
                $count = 0;

            $redis->set($key,$count,$cacheTime);
        }

        return $count;
    }

    /*
    * 根据产品ID获取不包括回复条数
    */
    private static function getCurrentNumByPid($typeId,$productId,$change = false){
        $key = 'trade.comment.getCurrentNumByPid.t'.$typeId.'p'.$productId;
        $cacheTime = 60*10;

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(5);
        $count = $redis->get($key);
        if((!$count && $count !== 0) || $change){
            $count = trdCommentTable::getInstance()->createQuery()->where('type_id = ?',$typeId)->andWhere('product_id = ?',$productId)->andWhere('status = 1')->count();
            $redis->set($key,$count,$cacheTime);

        }

        return $count;
    }


    /*
    * 根据评论ID获取回复条数
    */
    private static function getCurrentNumByCid($commentId,$change = false){
        $key = 'trade.comment.getCurrentNumByCid.c.'.$commentId;
        $cacheTime = 60*10;

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(5);
        $count = $redis->get($key);
        if((!$count && $count !== 0) || $change){
            $count = trdCommentClusterTable::getInstance()->createQuery()->where('comment_id = ?',$commentId)->andWhere('status = ?',1)->count();
            $redis->set($key,$count,$cacheTime);
        }

        return $count;
    }


    /*评论限制*/
    private function commentLimit($user_id){
        $limitDate = date('Y-m-d H:i:s',time() - 60);

        $count = trdCommentTable::getInstance()->createQuery('t')->where('user_id = ?',$user_id)->andWhere('created_at > ?',$limitDate)->count();
        if($count > $this->allow_num)
             return $this->error(406,'评论太频繁,请稍后再操作');
        else
            return false;
    }

    /*回复限制*/
    private function replyLimit($user_id){
        $limitDate = date('Y-m-d H:i:s',time() - 60);
        $count = trdCommentClusterTable::getInstance()->createQuery('t')->where('user_id = ?',$user_id)->andWhere('created_at > ?',$limitDate)->count();
        if($count >= $this->allow_num)
            return $this->error(407,'回复太频繁,请稍后再操作');
        else
            return false;
    }

    /*类型过滤*/
    private function typeFilter($typeId){
        $link = Doctrine_Manager::getInstance()->getConnection('trade');
        $result = Doctrine_Query::create($link)
            ->setResultCacheLifeSpan(60*60*2)
            ->useResultCache()
            ->select('t.id,t.name')
            ->from('TrdCommentType t')
            ->fetchArray();

        $typeArr = array();
        foreach($result as $k=>$v){
            $typeArr[] = $v['id'];
        }

        if(!in_array($typeId,$typeArr)){
            return $this->error(405,'类型不存在');
        }
    }

    /*pass信用验证*/
    public function  passport($uid){
        $timeline = time();
        $arrays = array(
            'appid'    => $this->appid,
            'ip'       => FunBase::getIp(),
            'method'   => 'GET',
            'timeline' => $timeline,
            'uid'      => $uid,
        );

        //生成签名
        $arrays['sign'] = md5($this->appid.$this->appkey.$timeline.$uid);
        $params = http_build_query($arrays);

        $status = tradeCommon::requestUrl('http://passport.hupu.com/ucenter/permits.api?'.$params, 'GET');
        $status = json_decode($status, true);

        if (is_numeric($status)){
            if(isset($this->permits_error_code[$status])){
                return $this->error(403,$this->permits_error_code[$status]);
            }
        }elseif(is_array($status)){
            if(isset($status['safety']) && $status['safety'] < 4){
                return $this->error(403,$this->permits_error_code[1]);
            }
        }
    }

    /*封禁检测*/
    private function prohibit($userId,$userIp){
        $prohibitUserId = trdCommentProhibitTable::getInstance()->createQuery()->where('type = ?',1)->andWhere('type_num = ? ',$userId)->fetchOne();
        if($prohibitUserId && strtotime($prohibitUserId->getAllowDate()) > time())
            return $this->error(409,'你的账户被封禁至'.$prohibitUserId->getAllowDate());

        $prohibitIp = trdCommentProhibitTable::getInstance()->createQuery()->where('type = ?',2)->andWhere('type_num = ? ',$userIp)->fetchOne();
        if($prohibitIp && strtotime($prohibitIp->getAllowDate()) > time())
            return $this->error(409,'你的IP被封禁至'.$prohibitIp->getAllowDate());

        return false;
    }

    /*匹配替换用户*/
    private  static function matchUsername($toUsername,$type){
        $toUserId = self::getUserInfoByUsername(trim(strip_tags($toUsername),'@'));

        if($toUserId){
            $toUsername =  '<uid uid="'.$toUserId.'">'.$toUsername.'</uid>';
            if($type == 1){
                self::$toUserId = $toUserId;  //被回复用户

                return '回复 : '.$toUsername.' ';
            } else{
                return $toUsername.' ';
            }
        }else{
            return $toUsername.' ';
        }

    }

}