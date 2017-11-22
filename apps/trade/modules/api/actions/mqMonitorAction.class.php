<?php
/*
 * rabbitmq  监控
 * @author  韩晓林
 * @date 2015/2/13
 * */
Class mqMonitorAction extends sfAction
{
    public function execute($request) {
        sfConfig::set('sf_web_debug', false);

        $monitorsArr = sfConfig::get('app_mabbitmq_options_shihuo');
        $monitorsHost = $monitorsArr['params']['host'];
        $monitorsPermission = array('username'=>$monitorsArr['params']['user'],'pwd'=>$monitorsArr['params']['pass'], $monitorsArr['params']['vhost']);
        $monitorPort = 15672;

        $responseArr   = array();//接收请求的内容
        $statusArr = array('status'=>200,'msg'=>'good status');//返回客户端的内容

        //循获取对应的MQ信息
        $method = 'GET';
        $responseArr = $this->http($monitorsHost.':'.$monitorPort.'/api/overview', $method,$monitorsPermission);

        //处理信息
        if(!empty($responseArr)){
            //获取queue_totals
            $jsonArr = json_decode($responseArr, TRUE);
            if(empty($jsonArr)){//json格式错误了
                $statusArr['status'] = 500;
                $statusArr['msg'] = 'host:'.$monitorsHost." json error-------";
            }
            if($jsonArr['queue_totals']['messages']>=1000){
                $statusArr['status'] = 500;
                $statusArr['msg'] = 'host:'.$monitorsHost." messages_unacknowledged > 1000  -------";
            }
        }else{
            $statusArr['status'] = 500;
            $statusArr['msg'] = $monitorsHost.'error...';

        }


        //相应信息
//        $this->response->setHttpHeader('status',$statusArr['status']);
        $this->response->setHttpHeader('Content-Type', 'application/json');
        $this->response->setHttpHeader('Message', $statusArr['msg']);

        return $this->renderText(json_encode($statusArr));
    }



    /**
     *
     * @param type $url
     * @param type $method
     * @param type $paramArr['username']:用户名密码 $paramArr['pwd']：密码
     * @param type $headers
     * @return type
     */
    private function http($url, $method,$paramArr = array(), $headers = array()) {

        $this->http_info = array();
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_ENCODING, "UTF-8");
        curl_setopt($ci, CURLOPT_HEADER, FALSE);
        curl_setopt($ci, CURLOPT_URL, $url);
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE);
        curl_setopt($ci, CURLOPT_USERPWD, $paramArr['username'].':' . $paramArr['pwd'] );
        $response = curl_exec($ci);
        curl_close($ci);
        return $response;
    }




}
