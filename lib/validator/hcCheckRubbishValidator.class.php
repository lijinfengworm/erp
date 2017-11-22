<?php
class hcCheckRubbishValidator extends sfValidatorString
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
    $this->addMessage('is_rubbish', '内容中包含非法词');
    $this->addOption('config');
    $this->addOption('uid');
    $this->addOption('ip');
    //附加验证字符串
    $this->addOption('add_string');
    $this->addOption('fromurl');
    $this->addOption('stop_submit');
    $this->addMessage('stop_submit','stop submit');
    return parent::configure($options,$messages);
  }
  
  public function clean($value)
  {
    
    if($this->getOption('stop_submit'))
    {
        throw new sfValidatorError($this, 'stop_submit', array('value' => $value));
    }
    $config = $this->getOption('config');
    $antiSpamArrays = array(
        'uid' => $this->getOption('uid'),
        'ip' => $this->getOption('ip'),
        'content' => $value.$this->getOption('add_string'),
        'fromurl' => $this->getOption('fromurl'),
        'sendtime' => time(),
        'charset' => 'utf-8'
    );
    // 请求垃圾信息判断接口
    $isSpam = SnsInterface::getContents('antispam',$config['app_id'],$config['app_key'], $antiSpamArrays, 'POST');
    if(!empty($isSpam) && is_array($isSpam))
    {
        throw new sfValidatorError($this, 'is_rubbish', array('value' => $value));
    }
    return parent::clean($value);
  }
}
?>
