<?php

/**
 *   识货公共服务  各种小的接口 不值得单独建立服务 都可以写在这里
 *   最新版本：1.0
 *   最后更新时间  2015-06-13
 *   创建时间  2015-06-13
 */
class globalTradeService extends tradeService {

    private $errorCode = 400;

    private $userId = NULL;

    /* 可用收藏类型  */
    private $_collection_type = array('youhui','haitao','daigou','shoe','groupon','find','newfind');




    /**
     *  全站收藏服务
        $serviceRequest = new tradeServiceClient();
        $serviceRequest->setMethod('global.user.collection');
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setApiParam('collection_id', $_id);
        $serviceRequest->setApiParam('type', $_type);
        $serviceRequest->setUserToken($request->getCookie('u'));
     */
    public function executeUserAddCollection() {

        $version = $this->getRequest()->getParameter('version','');
        //用户id
        $userId = $this->getUser()->getAttribute('uid');
        //收藏类型
        $_type = $this->getRequest()->getParameter('type');
        //收藏ID
        $_collection_id = $this->getRequest()->getParameter('collection_id');
        try {
            if (empty($userId)) throw new Exception('未登录',501);

            if (empty($_collection_id)) throw new Exception('缺少收藏ID',501);

            if (empty($_type) || !in_array(strtolower($_type),$this->_collection_type)) throw new Exception('错误的收藏类型！',401);

            //判断是否收藏过了
            if( TrdUserCollectionsTable::getColloectionByUid($userId, $_collection_id, $_type) ) {
                throw new Exception('已经收藏过了！',401);
            }
            //插入收藏表
            $trdUserCollection = new TrdUserCollections();
            $trdUserCollection->setHupuUid($userId);
            $trdUserCollection->setCollectionId($_collection_id);
            $trdUserCollection->setType(strtolower($_type));
            $trdUserCollection->save();

            /* 回调  */
            if(!empty($_type)) {
                //判断回调函数是否填写
                $_callback_name = $_type.'AddCallBack';
                if(method_exists('collectionTradeCallBack',$_callback_name)) {
                    try {
                        call_user_func_array( array( 'collectionTradeCallBack' , $_callback_name ) , array($_collection_id,$_type,$userId));
                    } catch(Exception $ee) {
                        //调用回调失败 删除收藏
                        $trdUserCollection->delete();
                        throw new Exception($ee->getMessage(),402);
                    }
                }
            }
            //收藏成功
            return $this->success();
        } catch(Exception $e)  {
            return $this->error($e->getCode(), $e->getMessage());
        }
        return $this->success();
    }



    /**
     * 删除某个商品
     */
    public function executeUserDeleteCollection() {
        $version = $this->getRequest()->getParameter('version','');
        //用户id
        $userId = $this->getUser()->getAttribute('uid');
        //收藏ID
        $_collection_id = $this->getRequest()->getParameter('collection_id');
        try {
            if (empty($userId)) throw new Exception('未登录',501);
            if (empty($_collection_id)) throw new Exception('缺少收藏ID',501);

            //获取收藏记录
            $collectionData = TrdUserCollectionsTable::getColloectionByUidId($userId, $_collection_id);
            if(empty($collectionData)) throw new Exception('没有该收藏记录！',401);
            //获取收藏类型
            $_type = $collectionData->getType();
            if (in_array(strtolower($_type),$this->_collection_type)) {
                //判断回调函数是否填写
                $_callback_name = $_type.'DelCallBack';
                if(method_exists('collectionTradeCallBack',$_callback_name)) {
                    try {
                        call_user_func_array( array( 'collectionTradeCallBack' , $_callback_name ) , array($collectionData->getCollectionId(),$_type,$userId));
                    } catch(Exception $ee) {
                        throw new Exception($ee->getMessage(),402);
                    }
                }
            }
            //删除收藏
            $collectionData->delete();
            return $this->success();
        } catch(Exception $e)  {
            return $this->error($e->getCode(), $e->getMessage());
        }
        return $this->success();
    }




    /**
     * 判断是否有收藏某个商品
     */
    public function  executeHasUserCollection() {
        $version = $this->getRequest()->getParameter('version','');
        //用户id
        $userId = $this->getUser()->getAttribute('uid');
        //收藏类型
        $_type = $this->getRequest()->getParameter('type');
        //收藏ID
        $_collection_id = $this->getRequest()->getParameter('collection_id');
        try {
            if (empty($userId)) throw new Exception('未登录',501);

            if (empty($_type) || !in_array($_type,$this->_collection_type)) throw new Exception('错误的收藏类型！',401);

            //判断是否收藏过了
            if( TrdUserCollectionsTable::getColloectionByUid($userId, $_collection_id, $_type) ) {
                return $this->success(array('is_collection'=>1));
            } else {
                return $this->success(array('is_collection'=>0));
            }
        } catch(Exception $e)  {
            return $this->error($e->getCode(), $e->getMessage());
        }
        return $this->success(array('is_collection'=>0));
    }







}