<?php
/**
 * Created by PhpStorm.
 * User: kworm
 * Date: 3/29/16
 * Time: 4:31 AM
 */
class logKaluliService extends kaluliService{
    //插入操作
    function executeAdd(){
        $optID = $this->getRequest()->getParameter('opt_id');
        $uid = $this->getRequest()->getParameter('uid');
        $optJson = $this->getRequest()->getParameter('opt_json');
        $optURI = $this->getRequest()->getParameter('opt_uri');
        KaluliOptLogTable::getInstance()->insertOptLogForLook($optID, $uid, $optJson, $optURI);
    }
    /**
     * erp系统日志写入
     * kworm
     * type=1---BB项目， type=2---ERP项目
     */
    public static function writeLog($message){
       
    	$order_number = $message['order_number'];
    	$msg_body = base64_encode(json_encode($message['body']));
    	$author = isset($message['author']) ? $message['author']:'000000';
        $type = isset($message['type']) ? intval($message['type']) : 1;
        try {
            $logObj = new KllBBOrderLog();
            $logObj->setOrderNumber($order_number)->setType($type)->setContent($msg_body)->setCreatTime(time())->setUpdateTime(time())->setOperationUser($author);
            $logObj->save();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());  
            
        }
    	
    }   
    /**
     * 获得日志文件
     */
    public static function getLog($order_number, $type=1){
        $res = KllBBOrderLogTable::getInstance()->getAllByOrderNumber($order_number);
        return $res;
    }
}
    