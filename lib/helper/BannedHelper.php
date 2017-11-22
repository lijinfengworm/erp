<?php
    function isBanned($userId = 0){
        if(!$userId){
            return false;
        }
        $userInfo = replyBlackListTable::getInstance()->findOneByUserId($userId);
        
        if($userInfo){
            if(time() > $userInfo->getExpireTime()){
                $userInfo->delete();
                return false;
            }
            return true;
        }else{
            return false;
        }
    }