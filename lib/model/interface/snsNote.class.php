<?php

/**
 * 发布社区碎碎念
 */
class snsNote {
    
    public static function setNote($param, $config){
        $result = SnsInterface::getContents($config['apiname'], $config['appid'], $config['key'], $param, 'POST');
        return $result < 0 ? false : true;
    }
}

?>
