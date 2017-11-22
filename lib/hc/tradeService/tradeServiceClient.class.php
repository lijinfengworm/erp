<?php
class tradeServiceClient{
    private $apiParams = array();
    private $systemParams = array();
    private $method;
    private $version;
    private $user;
    private $files = array();
    public function setMethod($method)
    {
        $this->method = $method;
        $this->systemParams['method'] = $method;
        return $this;
    }
    public function setVersion($version)
    {
        $this->version = $version;
        $this->systemParams['version'] = $version;
        return $this;
    }
    public function setApiParam($name,$value)
    {
        $this->apiParams[$name] = $value;
        return $this;
    }

    public function setApiParams($params)
    {
        if(!empty($params) && isset($params)) {
            foreach($params as $key => $val) {
                $this->setApiParam($key, $val);
            }
        }
        return $this;
    }

    public function setFile($name,$value)
    {
        $this->files[$name] = $value;
        return $this;
    }
    public function setUserToken($token)
    {
        $passportClient = new PassportClientForToken(null,$token);
        if($passportClient->iflogin())
        {
            $userInfo = $passportClient->userinfo();
            $this->setUser($userInfo['uid'],$userInfo['username']);
        }
        return $this;
    }
    public function setUser($uid,$username)
    {
        $this->user = new tradeServiceUser(array('uid'=>$uid,'username'=>$username));
        return $this;
    }
    /**
     * @return tradeServiceUser
     */
    public function getUser()
    {
        if(!$this->user)
        {
            return new tradeServiceUser(array());
        }
        return $this->user;
    }

    public function execute()
    {
        if(!$this->version)
        {
            return new tradeServiceResponse(array('msg'=>'tradeRequest has no version','status'=>500));
        }
        $methodInfo = explode('.',$this->method);
        $serviceName = $methodInfo[0];
        $serviceAction = '';
        for($i=1;$i<count($methodInfo);$i++)
        {
            $serviceAction .=ucfirst($methodInfo[$i]);
        }
        $service_file = sfConfig::get('sf_root_dir').'/lib/hc/tradeService/'.$serviceName.'TradeService.class.php';
        if(!is_readable($service_file))
        {
            $msg = sprintf('There is no  class "%s".', $serviceName);
            return new tradeServiceResponse(array('msg'=>$msg,'status'=>500));
        }
        if (!in_array('execute'.ucfirst($serviceAction), get_class_methods($serviceName.'TradeService')))
        {
            $msg = sprintf('There is no "%s" method in your action class "%s".', 'execute'.ucfirst($serviceAction), $serviceName);
            return new tradeServiceResponse(array('msg'=>$msg,'status'=>500));
        }
        $className = $serviceName.'TradeService';
        $service = new $className(new tradeServiceRequest($this->systemParams,$this->apiParams,$this->files),$this->getUser());
        $actionName = 'execute'.$serviceAction;
        $return = new tradeServiceResponse($service->$actionName());

        $this->apiParams = $this->systemParams = $this->files = array();
        $this->method = $this->version = $this->user = '';
        return $return;
    }

}