<?php
class TaeShihuoTopClient extends TopClient
{
    /**
     * 初始化链接类
     * @param type $tbsandbox 是否是沙箱环境
     */
    public function __construct($tbsandbox = FALSE) {

//        if($tbsandbox == TRUE){
//            //沙箱环境
//            $config = ConfigTaobao::getTaobaoConfig(true);
//        }else{
//            $config = ConfigTaobao::getTaobaoConfig();
//        }
        //$this->gatewayUrl = $config['gatewayUrl'];
        $this->appkey = '23022129';
        $this->secretKey = '1967fd81240e0ed73ff1c911fa45d2c3';
        $this->format = 'json';
    }
}
