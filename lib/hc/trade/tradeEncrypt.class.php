<?php
/*
 * 加密
 *@author  韩晓林
 * */
Class Encrypt{

    private static function getKey(){
        return md5('shihuo_user_task');
    }

    public  static function _encrypt($value){
        $td = mcrypt_module_open('tripledes', '', 'ecb', '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);
        $key = substr(self::getKey(), 0, mcrypt_enc_get_key_size($td));
        mcrypt_generic_init($td, $key, $iv);
        $ret = base64_encode(mcrypt_generic($td, $value));
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $ret;
    }

    public static function _dencrypt($value){
        $td = mcrypt_module_open('tripledes', '', 'ecb', '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);
        $key = substr(self::getKey(), 0, mcrypt_enc_get_key_size($td));
        mcrypt_generic_init($td, $key, $iv);
        $ret = trim(mdecrypt_generic($td, base64_decode($value))) ;
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $ret;
    }
}