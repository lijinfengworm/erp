<?php
class sfCaptchaGDValidator extends sfValidatorString
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * length: The Length of the string
   *
   * Available error codes:
   *
   *  * length
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorBase
   */
  
  public function configure($options = array(), $messages = array())
  {
    $this->addMessage('length', '"%value%" must be %length% characters long.');
    
    $this->addMessage('invalid', 'The numbers you typed in are invalid.');
    
    $this->addMessage('active', '抱歉！你在新声中的活跃度不够！');
    
    $this->addMessage('fast', '发布太快，确保发布质量，请稍后再发吧');
    
    $this->addOption('length');

    $this->setOption('empty_value', '');
  }
  
  protected function doClean($value)
  { 
    $clean = (string) $value;
    
    $length = function_exists('mb_strlen') ? mb_strlen($clean, $this->getCharset()) : strlen($clean);

    if ($this->hasOption('length') && $length != $this->getOption('length'))
    {
      throw new sfValidatorError($this, 'length', array('value' => $value, 'length' => $this->getOption('length')));
    }
        
//    if (sfContext::getInstance()->getUser()->getAttribute('captcha', NULL) != $clean)
//    {
//      sfContext::getInstance()->getUser()->setAttribute('captcha', NULL);
//      throw new sfValidatorError($this, 'invalid', array('value' => $value));
//    }
     $request  = sfContext::getInstance()->getRequest();
     $c_value = $request->getCookie('front_captcha');
     $r_key = md5($c_value . 'voice#@#hupu');
     $redis = sfContext::getInstance()->getDatabaseConnection('voiceRedis');
     $captcha_str = $redis->get($r_key) ? trim($redis->get($r_key)) : 0;
     if(empty($captcha_str) || $captcha_str != trim($clean)){
         $redis->del($r_key);
         throw new sfValidatorError($this, 'invalid', array('value' => $value));
     }
     
     //判断发布用户在新声的活跃程度
     $user = sfContext::getInstance()->getUser();
     $user_id = $user->hasAttribute('uid') ? $user->getAttribute('uid') : 0;
     $active_result = TwitterReplyLightUserTable::isActiveByUid($user_id);
     if(!$active_result){
         throw new sfValidatorError($this, 'active', array('value' => $value));
     }
     $user_level = $user->getUserLevelInfo();
     if($user_level == 0)
     {
         throw new sfValidatorError($this, 'active', array('value' => $value));         
     }
        //晚上是3小时 白天是1小时
        $h =  date('H');
        if($h >= 23 || $h <= 10)
        {
            $h_count = 3;
        }else{
            $h_count = 1;
        }

        $count = voiceFrontPageTable::getCountByUidTime($user_id,time()-60*60*$h_count);

        //2级用户最多发2个 其他最多6个
        if($user_level < 2)
        {
            $max = 1;
        }else{
            $max = 5;
        }
        if($count > $max)
        {
            throw new sfValidatorError($this, 'fast', array('value' => $value));
        }
     
     
    return $clean;
  }
}
?>
