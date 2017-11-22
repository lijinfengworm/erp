<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tradeGetUserInfo
 *
 * @author Administrator
 */
class tradeUserUtil {
    static function getUserBaseInfo($uid) {
        $args = array("uid" => $uid);
        $rs = SnsInterface::getContents("getuserbaseinfo", "84", "62c7c5ccd161d52", $args, 'GET');
        $rs["username"] = mb_convert_encoding($rs["username"], "utf-8", "gbk");
        return $rs;
    }
    //put your code here
}

?>
