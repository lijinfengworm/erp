<?php
class hcCaptchaWidget extends sfWidgetForm
{
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('redis_store');
    $this->addOption('client_public_key');
    $this->addOption('timeout');
    //过期时间
    $this->setOption('timeout', 60*60);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $redis = $this->getOption('redis_store');
    $client_public_key = $this->getOption('client_public_key');
    $timeout = $this->getOption('timeout');
    $client_token = md5(microtime().rand(0,100000));
    $redis->setex($client_token,$timeout,1);
    return $this->renderTag('input',array('type' => 'hidden', 'name' => 'captcha[client_public_key]', 'value' => $client_public_key)).
           $this->renderTag('input',array('type' => 'hidden', 'name' => 'captcha[client_token]', 'value' => $client_token))
    ;
  }
}
