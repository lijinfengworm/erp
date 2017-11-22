<?php

class kaluliService
{
    public $request;
    public $user;

    public function __construct(kaluliServiceRequest $request, kaluliServiceUser $user)
    {
        $this->request = $request;
        $this->user = $user;
        $this->_init();
    }

    public function _init()
    {

    }

    public function error($status, $msg, $data = array())
    {
        $return = array('status' => $status, 'msg' => $msg);
        if(!empty($data))
        {
            $return['data'] = $data;
        }
        return $return;
    }

    public function success($data = array(), $status = 200, $msg = 'ok')
    {
        return array('status' => $status, 'msg' => $msg, 'data' => $data);
    }

    /**
     * @return kaluliServiceUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return sfWebRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    public static function commonServiceCall($service,$func,$params =[]) {
        $serviceRequest = new kaluliServiceClient();
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setMethod($service.".".$func);
        if(!empty($params)){
            foreach($params as $k => $v){
                $serviceRequest->setApiParam($k, $v);
            }
        }
        $response = $serviceRequest->execute();
        return  $response;
    }
}