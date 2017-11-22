<?php

class voiceUpdate_topics_replyTask extends sfBaseTask
{
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'star'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'voice'),
    ));

    $this->namespace        = 'voice';
    $this->name             = 'update_topics_reply';
    $this->briefDescription = 'update replies of topics that can be seen in homepage';
    $this->detailedDescription = <<<EOF
The [voice:update_topics_reply|INFO] task does things.
Call it with:

  [php symfony voice:update_topics_reply|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration('star', $options['env'], true));
    $this->work();
  }
  
  private function work(){
      try{
          $cache = sfContext::getInstance()->getDatabaseConnection('liangleMemcache');
          $key = $this->name;
          $status = $cache->get($key);
          if($status){
              echo 'The last task is still running!' . "\n";
              exit;
          }else{
              $status = $cache->set($key, 1, 600);
              @file_get_contents('http://voice.hupu.com/index/generate_topic_replies');
              $status = $cache->set($key, 0, 600);
          }
      }catch (Exception $e){
          echo 'Some error happened' . "\n";
      }
  }
}
