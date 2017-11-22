<?php

class syncCommentAction extends sfAction
{

    public function execute($request)
    {
        $offset = $request->getParameter('offset',0);
        $limit = $request->getParameter('limit',20);

        $res = tradeCommon::requestUrl("http://comment.hupu.com/interface/comment/showlistShihuoSpecified?offset={$offset}&limit={$limit}",'GET',NULL,NULL,5);
        if($res) {
            $res = json_decode($res, true);

            foreach($res as $res_v){
                $comment = trdCommentTable::getInstance()->find($res_v['comment_id']);
                if (!$comment) {
                    $comment = new trdComment();
                    $comment->setId($res_v['comment_id']);
                    $comment->setTypeId(1);
                    $comment->setProductId($res_v['topic_id']);
                    $comment->setUserId($res_v['uid']);
                    $comment->setUserName($res_v['username']);
                    $comment->setContent($res_v['contents']);
                    $comment->setIp(ip2long($res_v['ip']));
                    $comment->setImgsAttr(null);
                    $comment->setPraise($res_v['light_num']);
                    $comment->setCreatedAt(date('Y-m-d H:i:s', $res_v['publish_time']));
                    $comment->setUpdatedAt(date('Y-m-d H:i:s', $res_v['publish_time']));
                    $comment->save();
                }
                echo $res_v['comment_id'].PHP_EOL;
            }
        }

        return sfView::NONE;
    }
}