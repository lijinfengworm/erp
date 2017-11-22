<?php
class kaluliServiceClient{
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
    }
    public function setVersion($version)
    {
        $this->version = $version;
        $this->systemParams['version'] = $version;
    }
    public function setApiParam($name,$value)
    {
        $this->apiParams[$name] = $value;
    }

    public function setApiParams($params)
    {
        if(!empty($params) && isset($params)) {
            foreach($params as $key => $val) {
                $this->setApiParam($key, $val);
            }
        }
    }

    public function setFile($name,$value)
    {
        $this->files[$name] = $value;
    }
    public function setUserToken($token)
    {
        $passportClient = new KaluliPassortClient($token);
        if($passportClient->iflogin())
        {
            $userInfo = $passportClient->userinfo();
            $this->setUser($userInfo['uid'],$userInfo['username']);
        }else{

        }
    }
    public function setUser($uid,$username)
    {
        $this->user = new kaluliServiceUser(array('uid'=>$uid,'username'=>$username));
    }
    /**
     * @return kaluliServiceUser
     */
    public function getUser()
    {
        
        if(!$this->user)
        {
            return new kaluliServiceUser(array());
        }
        return $this->user;
    }

    public function execute()
    {
        if(!$this->version)
        {
            return new kaluliServiceResponse(array('msg'=>'kaluliRequest has no version','status'=>500));
        }
        $methodInfo = explode('.',$this->method);
        $serviceName = $methodInfo[0];
        $serviceAction = '';
        for($i=1;$i<count($methodInfo);$i++)
        {
            $serviceAction .=ucfirst($methodInfo[$i]);
        }
        $service_file = sfConfig::get('sf_root_dir').'/lib/hc/kaluliService/'.$serviceName.'KaluliService.class.php';
        if(!is_readable($service_file))
        {
            $msg = sprintf('There is no  class "%s".', $serviceName);
            return new kaluliServiceResponse(array('msg'=>$msg,'status'=>500));
        }
        if (!in_array('execute'.ucfirst($serviceAction), get_class_methods($serviceName.'KaluliService')))
        {
            $msg = sprintf('There is no "%s" method in your action class "%s".', 'execute'.ucfirst($serviceAction), $serviceName);
            return new kaluliServiceResponse(array('msg'=>$msg,'status'=>500));
        }
        $className = $serviceName.'KaluliService';
        $service = new $className(new kaluliServiceRequest($this->systemParams,$this->apiParams,$this->files),$this->getUser());
        $actionName = 'execute'.$serviceAction;
        $return = new kaluliServiceResponse($service->$actionName());

        $this->apiParams = $this->systemParams = $this->files = array();
        $this->method = $this->version = $this->user = '';
        return $return;
    }

}