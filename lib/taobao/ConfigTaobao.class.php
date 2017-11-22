<?php

class ConfigTaobao{
    public static function getTaobaoConfig($tbsandbox = FALSE){
        $configArray = sfYaml::load(sfConfig::get("sf_root_dir")."/apps/trade/config/taobao.yml");
        if($tbsandbox){
            $config = $configArray['default']['.tbsandbox']['tbsandbox'];
        }else{
            $config = $configArray['default']['.taobao']['taobao'];
        }
        return $config;
    }
}
?>
