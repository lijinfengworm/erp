<?php

class tradeService
{
    public $request;
    public $user;

    public function __construct(tradeServiceRequest $request, tradeServiceUser $user)
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
     * @return tradeServiceUser
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
}