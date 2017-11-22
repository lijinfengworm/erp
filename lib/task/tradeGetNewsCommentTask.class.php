<?php

class tradeGetNewsCommentTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'trade';
    $this->name             = 'GetNewsComment';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:GetNewsComment|INFO] task does things.
Call it with:

  [php symfony trade:GetNewsComment|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);    
    
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $appid = sfConfig::get('app_comment_appid');
        $interface_url = 'http://comment.hupu.com/interface/comment/NewCommentTotal?appid='.$appid;
        $result = file_get_contents($interface_url);
        $data = json_decode($result,true);
        if ($data['code'] == 1){
            foreach($data['data'] as $k=>$v){
                TrdNewsTable::getInstance()->createQuery('m')
                        ->update()
                        ->set('m.reply_count', $v['total'])
                        ->where('m.id =?', $v['topic_id'])
                        ->execute();
            }
        }
        exit;
  }
}
