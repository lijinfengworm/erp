<?php

class voiceVoice_error502_pageTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'voice'),
      // add your own options here
    ));

    $this->namespace        = 'voice';
    $this->name             = 'voice_error502_page';
    $this->briefDescription = '生成502静态页';
    $this->detailedDescription = <<<EOF
The [voice:voice_error502_page|INFO] task does things.
Call it with:

  [php symfony voice:voice_error502_page|INFO]
EOF;
  }

 protected function execute($arguments = array(), $options = array())
  {
      sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration('star', $options['env'], true));
      $this->runStatus = sfContext::getInstance()->getDatabaseConnection('liangleMemcache');
      
      if ($this->getRunningStatus()){
          $this->log('the task is running !');
          return ;
      }
    
      $this->log('start the task !');
      $this->setRunningStatus(TRUE);
      
      $pageinfo = @file_get_contents('http://voice.hupu.com/error/502');
      
      if (!$pageinfo || strpos($page, '服务器出错了') !== false){
          $this->log('there is en error when try to get the page\'s info.');
      } else {
          $pagedir = sfConfig::get('sf_web_dir') . '/error';
          
          if (!is_dir($pagedir)){
              mkdir($pagedir, 0777, true);
          }
          
          if (file_exists($pagedir . '/voice_error502.html')){
              $status = copy($pagedir . '/voice_error502.html', $pagedir . '/voice_error502.backup.html');
              
              if (!$status){
                  $this->log('backup file failed !');
              } else {
                  $this->log('buckup file success !');
              }
          }
          
        if (file_put_contents($pagedir . '/voice_error502.html', $pageinfo)){
            $this->log('create file success !');
        } else {
            $this->log('create file failed !');
        }
      }
      
      $this->log('task over');
      $this->setRunningStatus(FALSE);
  }
  
  /**
   *获得memcache值
   * 
   * @return boolean 
   */
  private function getRunningStatus(){
      return $this->runStatus->get($this->name);
  }
  
  /**
   * 设置memcache值
   * 
   * @param boolean $status  可选值为true,false
   * 
   */
  private function setRunningStatus($status){
      return $this->runStatus->set($this->name, $status, 1800);
  }
}
