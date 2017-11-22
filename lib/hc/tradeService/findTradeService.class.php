<?php

/**
 * Class findTradeService
 * @version:1.0
 * @date: 1015/10/13
 * @author 韩晓林
 */
class findTradeService extends tradeService
{
    /**
     * 发现 喜欢
     */
    public function executeLike() {

        $findId   = (int)$this->getRequest()->getParameter('id');
        $userId   = (int)$this->getUser()->getAttribute('uid');
        $userName = $this->getUser()->getAttribute('username');

        if(empty($findId) || empty($userId)) {
            return $this->error(401, '参数缺失.');
        }

        $find = trdFindTable::getInstance()->find($findId);
        if(!$find || ($find->getStatus() != 1)){
            return $this->error(402, '不存在的商品.');
        }

        $findLike = trdFindLikeTable::getMessge($findId, $userId);
        if($findLike){
            return $this->error(403, '你已经点过赞啦~');
        }else{
            $findLike = new trdFindLike();
            $findLike->setFId($findId);
            $findLike->setHupuUid($userId);
            $findLike->setHupuUsername($userName);
            $findLike->save();

            //同步喜欢数
            $find->setLikeCount( $find->getLikeCount() + 1 );
            $find->save();
        }

        return $this->success(array(
            'status'=>true,
            'count'=>$find->getLikeCount())
        );
    }
}