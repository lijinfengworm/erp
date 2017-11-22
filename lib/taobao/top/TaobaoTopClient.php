<?php
class TaoBaoTopClient extends TopClient
{
    /**
     * 初始化链接类
     * @param type $tbsandbox 是否是沙箱环境
     */
    public function __construct($tbsandbox = FALSE) {
        
        if($tbsandbox == TRUE){
            //沙箱环境
            $config = ConfigTaobao::getTaobaoConfig(true);
        }else{
            $config = ConfigTaobao::getTaobaoConfig();
        }
        $this->gatewayUrl = $config['gatewayUrl'];
        $this->appkey = $config['taobaoke']['key'];
        $this->secretKey = $config['taobaoke']['secret'];
        $this->format = 'json';
    }
}
