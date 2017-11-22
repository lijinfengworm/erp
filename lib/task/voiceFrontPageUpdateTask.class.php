<?php
/**
 * 每过一段时间运行一次。 更新这个阶段内产生变化的 头条 对应的
 */
class voiceFrontPageUpdateTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','star'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name','voice'),
      // add your own options here
    ));

    $this->namespace        = 'voice';
    $this->name             = 'voiceFrontPageUpdate';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [voiceFrontPageUpdate|INFO] task does things.
Call it with:

  [php symfony voiceFrontPageUpdate|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration('star', $options['env'], true));
    // add your code here
    $redis = sfContext::getInstance()->getDatabaseConnection('voiceRedis');
    $last_update_key = voiceObject::$last_update_key;
    
    $cache_key = 'voiceFrontPageUpdateTask_lasttime';
    $last_time = $redis->get($cache_key);
    if(empty($last_time))
    {
        $last_time = time() - 60*5;
    }
    $this->log('last_time:'.date('Y-m-d H:i:s',$last_time));
    $now = time();
    $lists = voiceObjectFrontPageTable::getInstance()->getByFrontPageLastUpdateTime(date('Y-m-d H:i:s',$last_time),date('Y-m-d H:i:s',$now));
    foreach($lists as $list)
    {
        $list->getVoiceObject()->updateFrontPageListToRedis();
        
        $this->log('updateFrontPageListToRedis object_id'.$list->getVoiceObjectId());
        //轮询队列
        $redis->ZADD($last_update_key,  time(),  json_encode(array('id'=>$list->getVoiceObjectId(),'type'=>'object_front_page_list_update_time')));
    }
    $redis->setex($cache_key,86400,$now);
  }
}
