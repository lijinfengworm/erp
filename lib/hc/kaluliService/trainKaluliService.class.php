<?php
/**
 * Created by PhpStorm.
 * User: lilei
 * Date: 16/8/11
 * Time: 上午10:15
 */

class trainKaluliService extends kaluliService{
    

    //自定义调取服务函数
    public static function commonCall($func, $params = []){
        $serviceRequest = new kaluliServiceClient();
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setMethod('train.'.$func);
        if(!empty($params)){
            foreach($params as $k => $v){
                $serviceRequest->setApiParam($k, $v);
            }
        }
        $response = $serviceRequest->execute();
        return  $response->getData();
    }
    //获得训练计划详情
    public function executeGetTrainByTid(){
        $tid = $this->getRequest()->getParameter("tid");

        $train = KllArticlesTable::getInstance()->findOneById($tid);

        return $train;
    }
    public static function getCategory($cid){
        if($cid){
            $cate = KllCategoryTable::getInstance()->findOneById($cid);
            if(!empty($cate)){
                return $cate->getName();
            }
        }

        return '未分类';
    }

}