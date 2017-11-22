<?php

class voiceFrontPageUpdateRankTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'star'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'voice'),
      new sfCommandOption('day', null, sfCommandOption::PARAMETER_REQUIRED, 'The day num', 10),
      // add your own options here
    ));

    $this->namespace        = 'voice';
    $this->name             = 'voiceFrontPageUpdateRank';
    $this->briefDescription = '更新指定天内的头条的分数';
    $this->detailedDescription = <<<EOF
The [voiceFrontPageUpdateRank|INFO] task does things.
Call it with:

  [php symfony voiceFrontPageUpdateRank|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    // 
    $day = $options['day'];
    $startId = 0;
    while (1) {
      $voiceFrontPage = voiceFrontPageTable::getInstance()->createQuery()->where('created_at > ?',date('Y-m-d',time()-$day*86400))->andWhere('id > ?',$startId)->orderBy('id asc')->limit(100)->execute();
      if($voiceFrontPage->count())
      {
        foreach ($voiceFrontPage as $key => $value) {

          $value->setRank($value->getRankByBaseData());
          $value->save();
          $startId = $value->getId();
          $this->log('startId:'.$startId);
        }
      }else
      {
        break;
      }
    }
    $this->log($day);
  }
}
