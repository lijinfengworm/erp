<?php
class hcCaptchaValidator extends sfValidatorString
{
  /*
   *
   * 
   * 
   *  
   * @see sfValidatorBase
   */
  
  public function configure($options = array(), $messages = array())
  {
    $this->addMessage('input_error', '验证码错误');
    $this->addMessage('timeout', '离开的页面时间太长了');
    $this->setOption('required',false);
    $this->addOption('redis_store');
    $this->addOption('captcha');
    $this->addOption('client_private_key');
    return parent::configure($options,$messages);
  }
  
  public function clean($value)
  {

    $clean = (string) $value;
    $redis_store = $this->getOption('redis_store');
    $client_private_key = $this->getOption('client_private_key');
    $captcha = $this->getOption('captcha');
    $client_token = $captcha['client_token'];
    $choice_list = $captcha['choice_list'];
    $check_str = $captcha['check_str'];
    if(empty($client_private_key) || empty($client_token) || empty($choice_list) || empty($check_str))
    {
        throw new sfValidatorError($this, 'input_error', array('value' => $value));  
    }
    if(!$redis_store->get($client_token))
    {
        throw new sfValidatorError($this, 'timeout', array('value' => $value));
    }
    $redis_store->del($client_token);
    $choice_list = implode('', explode(',', $choice_list));
    if(md5($client_private_key.$client_token.$choice_list) != $check_str)
    {
        throw new sfValidatorError($this, 'input_error', array('value' => $value));
    }
    return parent::clean($value);
  }
}
?>
