<?php
class QueryUser
{
  private $userName = '';
  private $url = 'http://passport.hupu.com/ucenter/getUidByUsername.api?username=';
  
  public function __construct($userName)
  {
    $this->userName = urlencode($userName);
  }
  
  public function setQueryUrl($url)
  {
    $this->url = $url;
    
    return $this;
  }
    
  /**
   * Check if user exists
   * 
   * @return Boolean
   */ 
  public function exists()
  {
    return (bool)$this->getUserId();
  }

 public function getUserId()
 {
    $redis = sfContext::getInstance()->getDatabaseConnection('gamepayRedis');
    $prefix = "user_";
    $uid = $redis->get($prefix.$this->userName);
    if(empty($uid)) {
        $objPublicFun = new PublicFun;
        $uid = $objPublicFun->curl_get($this->url . $this->userName);
        if (empty($uid))
        {
            gameLog::error('uid error', array(
                'uid' => $uid,
                'userName' => $this->userName
            ));
            return 0;
        }
        $redis->setex($prefix.$this->userName, 60*30, $uid);
    }
    return (int)trim($uid);
 }
}